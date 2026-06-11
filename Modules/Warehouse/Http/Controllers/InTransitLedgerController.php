<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Warehouse\Entities\InTransitLedger;

/**
 * Sổ In-Transit theo cặp (kho nguồn → kho đích). Read-only.
 * Theo dõi chi tiết hàng đang vận chuyển khi điều chuyển nội bộ.
 */
class InTransitLedgerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/in-transit-ledger",
     *     tags={"In-Transit Ledger"},
     *     summary="Lấy sổ hàng đang vận chuyển (in-transit)",
     *     description="Danh sách dòng điều chuyển theo cặp kho nguồn→đích. Lọc theo kho nguồn/đích, SKU, trạng thái, phiếu.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="source_warehouse_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="dest_warehouse_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="sku_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="stock_document_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="status", in="query", required=false, description="1: Đang vận chuyển, 2: Hoàn tất, 3: Đã trả lại", @OA\Schema(type="integer", enum={1,2,3})),
     *     @OA\Parameter(name="limit", in="query", required=false, @OA\Schema(type="integer", default=15)),
     *     @OA\Response(response=200, description="Lấy danh sách thành công"),
     *     @OA\Response(response=401, description="Chưa xác thực tài khoản")
     * )
     */
    public function index(Request $request)
    {
        $query = InTransitLedger::with(['document', 'sku', 'sourceWarehouse', 'destWarehouse']);

        if ($request->filled('source_warehouse_id')) {
            $query->where('source_warehouse_id', $request->source_warehouse_id);
        }
        if ($request->filled('dest_warehouse_id')) {
            $query->where('dest_warehouse_id', $request->dest_warehouse_id);
        }
        if ($request->filled('sku_id')) {
            $query->where('sku_id', $request->sku_id);
        }
        if ($request->filled('stock_document_id')) {
            $query->where('stock_document_id', $request->stock_document_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rows = $query->orderByDesc('id')->paginate($request->get('limit', 15));

        return response()->json(['status' => 'success', 'data' => $rows]);
    }
}
