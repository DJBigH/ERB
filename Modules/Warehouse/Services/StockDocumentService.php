<?php

namespace Modules\Warehouse\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Warehouse\Entities\StockDocument;
use Modules\Warehouse\Entities\Warehouse;
use Modules\Warehouse\Exceptions\WarehouseException;

/**
 * Điều phối vòng đời chứng từ kho (Nhập / Xuất / Điều chuyển) + gọi StockLedger.
 * Idempotency: dùng khóa tất định theo (phiếu, bước, dòng) -> retry không áp tồn lặp.
 */
class StockDocumentService
{
    public function __construct(private StockLedger $ledger) {}

    private const OBJ = [1 => 'inbound', 2 => 'outbound', 3 => 'transfer'];
    private const PREFIX = [1 => 'PN', 2 => 'PX', 3 => 'PDC'];

    /* ===================== Tạo nháp ===================== */
    public function create(int $type, array $data, int $userId, ?string $ip): StockDocument
    {
        return DB::transaction(function () use ($type, $data, $userId, $ip) {
            $src = $data['source_warehouse_id'] ?? null;
            $dst = $data['dest_warehouse_id'] ?? null;

            if ($type === StockDocument::TYPE_TRANSFER && $src && $dst && (int) $src === (int) $dst) {
                throw new WarehouseException('Kho nguồn và kho đích không được trùng nhau.', 422);
            }
            $this->assertWarehouseActive($src);
            $this->assertWarehouseActive($dst);

            $doc = StockDocument::create([
                'code'                => $data['code'] ?? $this->genCode($type),
                'type'                => $type,
                'status'              => StockDocument::ST_DRAFT,
                'source_warehouse_id' => $src,
                'dest_warehouse_id'   => $dst,
                'note'                => $data['note'] ?? null,
                'created_by'          => $userId,
            ]);

            foreach ($data['lines'] as $l) {
                $doc->lines()->create([
                    'sku_id'     => $l['sku_id'],
                    'quantity'   => $l['quantity'],
                    'unit_price' => $l['unit_price'] ?? null,
                    'note'       => $l['note'] ?? null,
                ]);
            }

            AuditLogger::record('CREATE', self::OBJ[$type], $doc->id, $userId, $ip, ['code' => $doc->code]);
            return $doc->load(['lines.sku', 'sourceWarehouse', 'destWarehouse']);
        });
    }

    /* ===================== Gửi duyệt ===================== */
    public function submit(int $id, int $userId, ?string $ip): StockDocument
    {
        return DB::transaction(function () use ($id, $userId, $ip) {
            $doc = $this->lock($id);
            $this->assert($doc, [StockDocument::ST_DRAFT], 'gửi duyệt');
            if ($doc->lines()->count() < 1) {
                throw new WarehouseException('Phiếu phải có ít nhất 1 dòng sản phẩm.', 422);
            }
            $doc->update(['status' => StockDocument::ST_PENDING]);
            AuditLogger::record('SUBMIT', self::OBJ[$doc->type], $doc->id, $userId, $ip);
            return $doc->fresh('lines');
        });
    }

    /* ===================== Duyệt ===================== */
    public function approve(int $id, int $userId, ?string $ip): StockDocument
    {
        return DB::transaction(function () use ($id, $userId, $ip) {
            $doc = $this->lock($id);
            $this->assert($doc, [StockDocument::ST_PENDING], 'duyệt');

            // Kiểm tra sơ bộ tồn khả dụng cho Xuất / Điều chuyển
            if (in_array($doc->type, [StockDocument::TYPE_OUTBOUND, StockDocument::TYPE_TRANSFER], true)) {
                $this->assertWarehouseActive($doc->source_warehouse_id);
                $this->precheckStock($doc);
            }
            if ($doc->type === StockDocument::TYPE_INBOUND) {
                $this->assertWarehouseActive($doc->dest_warehouse_id);
            }

            $doc->update([
                'status'      => StockDocument::ST_APPROVED,
                'approved_by' => $userId,
                'approved_at' => now(),
            ]);
            AuditLogger::record('APPROVE', self::OBJ[$doc->type], $doc->id, $userId, $ip);
            return $doc->fresh('lines');
        });
    }

    /* ===================== Thực hiện Nhập / Xuất ===================== */
    public function confirm(int $id, int $userId, ?string $ip): StockDocument
    {
        return DB::transaction(function () use ($id, $userId, $ip) {
            $doc = $this->lock($id);
            if ($doc->status === StockDocument::ST_COMPLETED) {
                return $doc->fresh('lines'); // idempotent
            }
            $this->assert($doc, [StockDocument::ST_APPROVED], 'thực hiện');

            if ($doc->type === StockDocument::TYPE_INBOUND) {
                $this->assertWarehouseActive($doc->dest_warehouse_id);
                foreach ($doc->lines as $line) {
                    $this->ledger->inbound($doc, $line, $userId, $this->key($doc, 'in', $line->id));
                }
            } elseif ($doc->type === StockDocument::TYPE_OUTBOUND) {
                $this->assertWarehouseActive($doc->source_warehouse_id);
                foreach ($doc->lines as $line) {
                    $this->ledger->outbound($doc, $line, $userId, $this->key($doc, 'out', $line->id));
                }
            } else {
                throw new WarehouseException('Phiếu điều chuyển dùng dispatch/receive, không dùng confirm.', 409);
            }

            $doc->update(['status' => StockDocument::ST_COMPLETED, 'performed_by' => $userId, 'performed_at' => now()]);
            AuditLogger::record('CONFIRM', self::OBJ[$doc->type], $doc->id, $userId, $ip);
            return $doc->fresh('lines');
        });
    }

    /* ===================== Điều chuyển: Xuất (dispatch) ===================== */
    public function dispatch(int $id, int $userId, ?string $ip): StockDocument
    {
        return DB::transaction(function () use ($id, $userId, $ip) {
            $doc = $this->lock($id);
            if ($doc->status === StockDocument::ST_IN_TRANSIT) {
                return $doc->fresh('lines');
            }
            $this->assertTransfer($doc);
            $this->assert($doc, [StockDocument::ST_APPROVED], 'xuất điều chuyển');
            $this->assertWarehouseActive($doc->source_warehouse_id);

            foreach ($doc->lines as $line) {
                $this->ledger->transferOut($doc, $line, $userId, $this->key($doc, 'tout', $line->id));
            }
            $doc->update(['status' => StockDocument::ST_IN_TRANSIT, 'performed_by' => $userId, 'performed_at' => now()]);
            AuditLogger::record('DISPATCH', 'transfer', $doc->id, $userId, $ip);
            return $doc->fresh('lines');
        });
    }

    /* ===================== Điều chuyển: Nhận (receive) ===================== */
    public function receive(int $id, array $receivedByLine, int $userId, ?string $ip): StockDocument
    {
        return DB::transaction(function () use ($id, $receivedByLine, $userId, $ip) {
            $doc = $this->lock($id);
            if ($doc->status === StockDocument::ST_COMPLETED) {
                return $doc->fresh('lines');
            }
            $this->assertTransfer($doc);
            $this->assert($doc, [StockDocument::ST_IN_TRANSIT], 'nhận hàng');
            $this->assertWarehouseActive($doc->dest_warehouse_id);

            $allFull = true;
            foreach ($doc->lines as $line) {
                $rq = isset($receivedByLine[$line->id]) ? (string) $receivedByLine[$line->id] : (string) $line->quantity;
                if (bccomp($rq, (string) $line->quantity, 2) > 0) {
                    throw new WarehouseException('Số thực nhận không được lớn hơn số điều chuyển (dòng #' . $line->id . ').', 422);
                }
                $this->ledger->transferIn($doc, $line, $rq, $userId, $this->key($doc, 'tin', $line->id));
                $line->update(['received_qty' => $rq]);
                if (bccomp($rq, (string) $line->quantity, 2) < 0) {
                    $allFull = false;
                }
            }
            // MVP: nhận đủ -> Hoàn tất; nhận thiếu -> giữ Đang vận chuyển (phần thiếu xử lý ở giai đoạn sau)
            $doc->update([
                'status'       => $allFull ? StockDocument::ST_COMPLETED : StockDocument::ST_IN_TRANSIT,
                'performed_by' => $userId,
                'performed_at' => now(),
            ]);
            AuditLogger::record('RECEIVE', 'transfer', $doc->id, $userId, $ip);
            return $doc->fresh('lines');
        });
    }

    /* ===================== Điều chuyển: Trả lại (return) ===================== */
    public function returnTransfer(int $id, ?string $reason, int $userId, ?string $ip): StockDocument
    {
        return DB::transaction(function () use ($id, $reason, $userId, $ip) {
            $doc = $this->lock($id);
            if ($doc->status === StockDocument::ST_RETURNED) {
                return $doc->fresh('lines');
            }
            $this->assertTransfer($doc);
            $this->assert($doc, [StockDocument::ST_IN_TRANSIT], 'trả lại');

            foreach ($doc->lines as $line) {
                $this->ledger->transferReturn($doc, $line, $userId, $this->key($doc, 'tret', $line->id));
            }
            $doc->update(['status' => StockDocument::ST_RETURNED, 'reason' => $reason, 'performed_by' => $userId, 'performed_at' => now()]);
            AuditLogger::record('RETURN', 'transfer', $doc->id, $userId, $ip);
            return $doc->fresh('lines');
        });
    }

    /* ===================== Hủy (trước biến động) ===================== */
    public function cancel(int $id, ?string $reason, int $userId, ?string $ip): StockDocument
    {
        return DB::transaction(function () use ($id, $reason, $userId, $ip) {
            $doc = $this->lock($id);
            $this->assert($doc, [StockDocument::ST_DRAFT, StockDocument::ST_PENDING, StockDocument::ST_APPROVED], 'hủy');
            $doc->update(['status' => StockDocument::ST_CANCELLED, 'reason' => $reason]);
            AuditLogger::record('CANCEL', self::OBJ[$doc->type], $doc->id, $userId, $ip, ['reason' => $reason]);
            return $doc->fresh('lines');
        });
    }

    /* ===================== Helpers ===================== */
    private function lock(int $id): StockDocument
    {
        $doc = StockDocument::where('id', $id)->lockForUpdate()->first();
        if (!$doc) {
            throw new WarehouseException('Không tìm thấy phiếu.', 404);
        }
        return $doc;
    }

    private function assert(StockDocument $doc, array $allowed, string $action): void
    {
        if (!in_array($doc->status, $allowed, true)) {
            throw new WarehouseException(
                'Không thể ' . $action . ' khi phiếu đang ở trạng thái "' . $doc->status_label . '".', 409
            );
        }
    }

    private function assertTransfer(StockDocument $doc): void
    {
        if ($doc->type !== StockDocument::TYPE_TRANSFER) {
            throw new WarehouseException('Thao tác chỉ áp dụng cho phiếu điều chuyển.', 409);
        }
    }

    private function assertWarehouseActive(?int $warehouseId): void
    {
        if ($warehouseId === null) {
            return;
        }
        $w = Warehouse::find($warehouseId);
        if (!$w) {
            throw new WarehouseException('Kho không tồn tại.', 422);
        }
        if ((int) $w->status !== 1) {
            throw new WarehouseException('Kho "' . $w->name . '" đang ngừng hoạt động, không thể thực hiện nghiệp vụ.', 422);
        }
    }

    private function precheckStock(StockDocument $doc): void
    {
        foreach ($doc->lines as $line) {
            $bal = \Modules\Warehouse\Entities\InventoryBalance::where('warehouse_id', $doc->source_warehouse_id)
                ->where('sku_id', $line->sku_id)->first();
            $available = $bal ? (string) $bal->available_qty : '0';
            if (bccomp($available, (string) $line->quantity, 2) < 0) {
                throw new WarehouseException(
                    'Tồn khả dụng không đủ cho SKU #' . $line->sku_id . ' (cần ' . $line->quantity . ', còn ' . $available . ').', 422
                );
            }
        }
    }

    private function key(StockDocument $doc, string $stage, int $lineId): string
    {
        return 'sd' . $doc->id . ':' . $stage . ':' . $lineId;
    }

    private function genCode(int $type): string
    {
        return self::PREFIX[$type] . '-' . now()->format('ymd') . '-' . strtoupper(Str::random(5));
    }
}
