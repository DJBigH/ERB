<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Warehouse\Entities\InventoryBalance;
use Modules\Warehouse\Entities\InventoryReportSnapshot;

/**
 * Snapshot tồn kho cuối kỳ — tối ưu tính tồn đầu kỳ cho báo cáo.
 * Cung cấp endpoint xem danh sách và chốt snapshot từ tồn hiện tại.
 */
class InventorySnapshotController extends Controller
{
    /**
     * @OA\Get(
     *     path="/inventory-snapshots",
     *     tags={"Inventory Snapshots"},
     *     summary="Lấy danh sách snapshot tồn kho",
     *     description="Danh sách bản chốt tồn theo ngày/kho/SKU. Lọc theo ngày chốt, kho, SKU.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="snapshot_date", in="query", required=false, @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="warehouse_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="sku_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="limit", in="query", required=false, @OA\Schema(type="integer", default=50)),
     *     @OA\Response(response=200, description="Lấy danh sách thành công"),
     *     @OA\Response(response=401, description="Chưa xác thực tài khoản")
     * )
     */
    public function index(Request $request)
    {
        $query = InventoryReportSnapshot::with(['warehouse', 'sku']);

        if ($request->filled('snapshot_date')) {
            $query->whereDate('snapshot_date', $request->snapshot_date);
        }
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        if ($request->filled('sku_id')) {
            $query->where('sku_id', $request->sku_id);
        }

        $snapshots = $query->orderByDesc('snapshot_date')->orderBy('warehouse_id')->paginate($request->get('limit', 50));

        return response()->json(['status' => 'success', 'data' => $snapshots]);
    }

    /**
     * @OA\Post(
     *     path="/inventory-snapshots/generate",
     *     tags={"Inventory Snapshots"},
     *     summary="Chốt snapshot tồn kho theo ngày",
     *     description="Sao chụp toàn bộ tồn hiện tại (available/in_transit/reserved) thành snapshot cho ngày chỉ định. Chạy lại cùng ngày sẽ ghi đè (upsert theo ràng buộc duy nhất ngày+kho+SKU).",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"snapshot_date"},
     *             @OA\Property(property="snapshot_date", type="string", format="date", example="2026-06-08"),
     *             @OA\Property(property="warehouse_id", type="integer", nullable=true, description="Chỉ chốt 1 kho; bỏ trống = tất cả kho", example=null)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Chốt snapshot thành công"),
     *     @OA\Response(response=400, description="Dữ liệu không hợp lệ"),
     *     @OA\Response(response=401, description="Chưa xác thực tài khoản")
     * )
     */
    public function generate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'snapshot_date' => 'required|date',
            'warehouse_id'  => 'nullable|integer|exists:warehouses,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Dữ liệu đầu vào không hợp lệ',
                'errors'  => $validator->errors(),
            ], 400);
        }

        $date = $request->snapshot_date;

        $balanceQuery = InventoryBalance::query();
        if ($request->filled('warehouse_id')) {
            $balanceQuery->where('warehouse_id', $request->warehouse_id);
        }
        $balances = $balanceQuery->get();

        $count = 0;
        foreach ($balances as $balance) {
            InventoryReportSnapshot::updateOrCreate(
                [
                    'snapshot_date' => $date,
                    'warehouse_id'  => $balance->warehouse_id,
                    'sku_id'        => $balance->sku_id,
                ],
                [
                    'available_qty'  => $balance->available_qty,
                    'in_transit_qty' => $balance->in_transit_qty,
                    'reserved_qty'   => $balance->reserved_qty,
                ]
            );
            $count++;
        }

        return response()->json([
            'status'  => 'success',
            'message' => "Đã chốt snapshot tồn kho ngày {$date}",
            'data'    => ['snapshot_date' => $date, 'records' => $count],
        ]);
    }
}
