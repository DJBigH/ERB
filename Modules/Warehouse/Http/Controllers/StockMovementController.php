<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Warehouse\Entities\StockMovement;

class StockMovementController extends Controller
{
    /**
     * @OA\Get(path="/stock-movements", tags={"Stock Movement (Lịch sử biến động)"},
     *   summary="Lịch sử biến động tồn kho (lọc theo kho/SKU/loại/thời gian/chứng từ)",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="warehouse_id", in="query", @OA\Schema(type="integer")),
     *   @OA\Parameter(name="sku_id", in="query", @OA\Schema(type="integer")),
     *   @OA\Parameter(name="movement_type", in="query", description="1 Nhập,2 Xuất,3 ĐC xuất,4 ĐC nhập,5 Điều chỉnh", @OA\Schema(type="integer")),
     *   @OA\Parameter(name="from", in="query", @OA\Schema(type="string", format="date")),
     *   @OA\Parameter(name="to", in="query", @OA\Schema(type="string", format="date")),
     *   @OA\Parameter(name="document_id", in="query", description="Lọc theo phiếu nguồn", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK"))
     */
    public function index(Request $request)
    {
        $query = StockMovement::query()->with(['sku', 'warehouse', 'performer']);

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        if ($request->filled('sku_id')) {
            $query->where('sku_id', $request->sku_id);
        }
        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->movement_type);
        }
        if ($request->filled('document_id')) {
            $query->where('source_document_type', 'stock_document')->where('source_document_id', $request->document_id);
        }
        if ($request->filled('from')) {
            $query->where('created_at', '>=', $request->from . ' 00:00:00');
        }
        if ($request->filled('to')) {
            $query->where('created_at', '<=', $request->to . ' 23:59:59');
        }

        return response()->json([
            'status' => 'success',
            'data'   => $query->orderByDesc('id')->paginate($request->get('limit', 20)),
        ]);
    }
}
