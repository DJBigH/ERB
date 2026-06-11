<?php

namespace Modules\Warehouse\Services;

use Illuminate\Support\Str;
use Modules\Warehouse\Entities\InventoryBalance;
use Modules\Warehouse\Entities\StockDocument;
use Modules\Warehouse\Entities\StockDocumentLine;
use Modules\Warehouse\Entities\StockMovement;
use Modules\Warehouse\Exceptions\WarehouseException;

/**
 * Engine cập nhật tồn (F13). Mọi thay đổi tồn PHẢI đi qua đây.
 * Caller (service nghiệp vụ) chịu trách nhiệm mở DB::transaction + khóa phiếu.
 *
 * movement_type: 1 Nhập, 2 Xuất, 3 ĐC xuất, 4 ĐC nhập, 5 Điều chỉnh.
 */
class StockLedger
{
    /** Nhập kho: + available tại kho đích. */
    public function inbound(StockDocument $doc, StockDocumentLine $line, int $userId, string $idemKey): ?StockMovement
    {
        return $this->mutate($doc->dest_warehouse_id, $line->sku_id, $line->quantity, 1, $doc, $userId, $idemKey, '+', null);
    }

    /** Xuất kho: - available tại kho nguồn (guard đủ tồn). */
    public function outbound(StockDocument $doc, StockDocumentLine $line, int $userId, string $idemKey): ?StockMovement
    {
        return $this->mutate($doc->source_warehouse_id, $line->sku_id, $line->quantity, 2, $doc, $userId, $idemKey, '-', null);
    }

    /** Xuất điều chuyển: kho nguồn available -q, in_transit +q (guard đủ tồn). */
    public function transferOut(StockDocument $doc, StockDocumentLine $line, int $userId, string $idemKey): ?StockMovement
    {
        return $this->mutate($doc->source_warehouse_id, $line->sku_id, $line->quantity, 3, $doc, $userId, $idemKey, '-', 'in_transit_up');
    }

    /** Nhận điều chuyển: kho nguồn in_transit -rq, kho đích available +rq. */
    public function transferIn(StockDocument $doc, StockDocumentLine $line, string $rq, int $userId, string $idemKey): ?StockMovement
    {
        if ($mv = StockMovement::where('idempotency_key', $idemKey)->first()) {
            return $mv;
        }
        // Khóa 2 bản ghi tồn theo thứ tự warehouse_id (chống deadlock)
        [$src, $dst] = $this->lockPair($doc->source_warehouse_id, $doc->dest_warehouse_id, $line->sku_id);

        if (bccomp((string) $src->in_transit_qty, (string) $rq, 2) < 0) {
            throw new WarehouseException('Số lượng In-Transit không đủ để nhận.', 422);
        }
        $src->in_transit_qty = bcsub((string) $src->in_transit_qty, (string) $rq, 2);
        $src->save();

        $before = (string) $dst->available_qty;
        $dst->available_qty = bcadd($before, (string) $rq, 2);
        $dst->save();

        return $this->writeMovement($doc, $dst->warehouse_id, $line->sku_id, 4, (string) $rq, $before, (string) $dst->available_qty, $userId, $idemKey);
    }

    /** Trả lại điều chuyển: kho nguồn in_transit -q, available +q. */
    public function transferReturn(StockDocument $doc, StockDocumentLine $line, int $userId, string $idemKey): ?StockMovement
    {
        if ($mv = StockMovement::where('idempotency_key', $idemKey)->first()) {
            return $mv;
        }
        $src = $this->lockBalance($doc->source_warehouse_id, $line->sku_id);
        $qty = (string) $line->quantity;
        if (bccomp((string) $src->in_transit_qty, $qty, 2) < 0) {
            throw new WarehouseException('In-Transit không đủ để trả lại.', 422);
        }
        $src->in_transit_qty = bcsub((string) $src->in_transit_qty, $qty, 2);
        $before = (string) $src->available_qty;
        $src->available_qty = bcadd($before, $qty, 2);
        $src->save();

        return $this->writeMovement($doc, $src->warehouse_id, $line->sku_id, 4, $qty, $before, (string) $src->available_qty, $userId, $idemKey);
    }

    // ----------------------------------------------------------------

    /**
     * Áp 1 biến động lên available của một kho (dùng cho Nhập/Xuất/ĐC xuất).
     * $sign '+' hoặc '-'. $extra: 'in_transit_up' để đồng thời +in_transit (điều chuyển xuất).
     */
    private function mutate($warehouseId, $skuId, $qty, int $movementType, StockDocument $doc, int $userId, string $idemKey, string $sign, ?string $extra): ?StockMovement
    {
        if ($mv = StockMovement::where('idempotency_key', $idemKey)->first()) {
            return $mv; // idempotent no-op
        }
        $qty = (string) $qty;
        $bal = $this->lockBalance($warehouseId, $skuId);
        $before = (string) $bal->available_qty;

        if ($sign === '-') {
            if (bccomp($before, $qty, 2) < 0) {
                throw new WarehouseException('Tồn kho khả dụng không đủ (cần ' . $qty . ', còn ' . $before . ').', 422);
            }
            $bal->available_qty = bcsub($before, $qty, 2);
        } else {
            $bal->available_qty = bcadd($before, $qty, 2);
        }

        if ($extra === 'in_transit_up') {
            $bal->in_transit_qty = bcadd((string) $bal->in_transit_qty, $qty, 2);
        }
        $bal->save();

        $signedQty = $sign === '-' ? '-' . $qty : $qty;

        return $this->writeMovement($doc, $warehouseId, $skuId, $movementType, $signedQty, $before, (string) $bal->available_qty, $userId, $idemKey);
    }

    /** Khóa (tạo nếu chưa có) bản ghi tồn theo (kho, SKU). */
    private function lockBalance(int $warehouseId, int $skuId): InventoryBalance
    {
        try {
            InventoryBalance::firstOrCreate(
                ['warehouse_id' => $warehouseId, 'sku_id' => $skuId],
                ['available_qty' => 0, 'in_transit_qty' => 0, 'reserved_qty' => 0]
            );
        } catch (\Illuminate\Database\QueryException $e) {
            // race tạo trùng -> UNIQUE chặn, bỏ qua rồi khóa lại
        }
        return InventoryBalance::where('warehouse_id', $warehouseId)
            ->where('sku_id', $skuId)->lockForUpdate()->firstOrFail();
    }

    /** Khóa 2 bản ghi tồn (nguồn, đích) theo thứ tự warehouse_id tăng dần. */
    private function lockPair(int $srcW, int $dstW, int $skuId): array
    {
        if ($srcW <= $dstW) {
            $src = $this->lockBalance($srcW, $skuId);
            $dst = $this->lockBalance($dstW, $skuId);
        } else {
            $dst = $this->lockBalance($dstW, $skuId);
            $src = $this->lockBalance($srcW, $skuId);
        }
        return [$src, $dst];
    }

    private function writeMovement(StockDocument $doc, int $warehouseId, int $skuId, int $movementType, string $signedQty, string $before, string $after, int $userId, string $idemKey): StockMovement
    {
        return StockMovement::create([
            'code'                 => 'MV-' . strtoupper(Str::random(12)),
            'source_document_type' => 'stock_document',
            'source_document_id'   => $doc->id,
            'movement_type'        => $movementType,
            'warehouse_id'         => $warehouseId,
            'sku_id'               => $skuId,
            'quantity'             => $signedQty,
            'qty_before'           => $before,
            'qty_after'            => $after,
            'performed_by'         => $userId,
            'idempotency_key'      => $idemKey,
        ]);
    }
}
