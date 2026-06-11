<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Warehouse\Entities\InventoryReservation;

/**
 * Đặt giữ tồn kho (inventory reservations) — chức năng non-MVP, đã sẵn sàng.
 * Hiện cung cấp endpoint read-only; chưa wire vào luồng xuất kho.
 */
class ReservationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/inventory-reservations",
     *     tags={"Inventory Reservations"},
     *     summary="Lấy danh sách đặt giữ tồn kho",
     *     description="Danh sách reservation. Lọc theo kho/SKU/trạng thái. (Non-MVP: bảng để sẵn, chưa wire vào engine xuất kho.)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="warehouse_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="sku_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="status", in="query", required=false, description="1: Đang giữ, 2: Đã nhả, 3: Đã dùng", @OA\Schema(type="integer", enum={1,2,3})),
     *     @OA\Parameter(name="limit", in="query", required=false, @OA\Schema(type="integer", default=15)),
     *     @OA\Response(response=200, description="Lấy danh sách thành công"),
     *     @OA\Response(response=401, description="Chưa xác thực tài khoản")
     * )
     */
    public function index(Request $request)
    {
        $query = InventoryReservation::with(['warehouse', 'sku', 'sourceDocument']);

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        if ($request->filled('sku_id')) {
            $query->where('sku_id', $request->sku_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservations = $query->orderByDesc('id')->paginate($request->get('limit', 15));

        return response()->json(['status' => 'success', 'data' => $reservations]);
    }

    /**
     * @OA\Get(
     *     path="/inventory-reservations/{id}",
     *     tags={"Inventory Reservations"},
     *     summary="Xem chi tiết một đặt giữ tồn kho",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Thành công"),
     *     @OA\Response(response=404, description="Không tìm thấy bản ghi đặt giữ")
     * )
     */
    public function show($id)
    {
        $reservation = InventoryReservation::with(['warehouse', 'sku', 'sourceDocument'])->find($id);

        if (!$reservation) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy bản ghi đặt giữ'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $reservation]);
    }
}
