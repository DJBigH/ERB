<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Warehouse\Entities\WarehouseUserScope;

class WarehouseScopeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/warehouse-scopes",
     *     tags={"Warehouse Scopes"},
     *     summary="Lấy danh sách phân quyền user theo kho (data scope)",
     *     description="Liệt kê các gán user ↔ kho. Lọc theo user_id hoặc warehouse_id. Hỗ trợ tích hợp phân quyền Module 10.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="user_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="warehouse_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="limit", in="query", required=false, @OA\Schema(type="integer", default=50)),
     *     @OA\Response(response=200, description="Lấy danh sách thành công"),
     *     @OA\Response(response=401, description="Chưa xác thực tài khoản")
     * )
     */
    public function index(Request $request)
    {
        $query = WarehouseUserScope::with(['user', 'warehouse']);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        $scopes = $query->orderByDesc('id')->paginate($request->get('limit', 50));

        return response()->json(['status' => 'success', 'data' => $scopes]);
    }

    /**
     * @OA\Post(
     *     path="/warehouse-scopes",
     *     tags={"Warehouse Scopes"},
     *     summary="Gán quyền truy cập kho cho user",
     *     description="Gán 1 user vào 1 hoặc nhiều kho. Bỏ qua các cặp đã tồn tại (không lỗi).",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id","warehouse_ids"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="warehouse_ids", type="array", @OA\Items(type="integer"), example={1,2,3})
     *         )
     *     ),
     *     @OA\Response(response=201, description="Gán quyền thành công"),
     *     @OA\Response(response=400, description="Dữ liệu không hợp lệ"),
     *     @OA\Response(response=401, description="Chưa xác thực tài khoản")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'         => 'required|integer|exists:users,id',
            'warehouse_ids'   => 'required|array|min:1',
            'warehouse_ids.*' => 'integer|exists:warehouses,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Dữ liệu đầu vào không hợp lệ',
                'errors'  => $validator->errors(),
            ], 400);
        }

        $userId = $request->user_id;
        $createdBy = Auth::id();
        $assigned = [];

        foreach (array_unique($request->warehouse_ids) as $warehouseId) {
            $scope = WarehouseUserScope::firstOrCreate(
                ['user_id' => $userId, 'warehouse_id' => $warehouseId],
                ['created_by' => $createdBy]
            );
            $assigned[] = $scope;
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Gán quyền truy cập kho thành công',
            'data'    => $assigned,
        ], 201);
    }

    /**
     * @OA\Delete(
     *     path="/warehouse-scopes/{id}",
     *     tags={"Warehouse Scopes"},
     *     summary="Thu hồi quyền truy cập kho",
     *     description="Xóa 1 bản ghi gán user ↔ kho theo id.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Thu hồi thành công"),
     *     @OA\Response(response=404, description="Không tìm thấy bản ghi phân quyền")
     * )
     */
    public function destroy($id)
    {
        $scope = WarehouseUserScope::find($id);

        if (!$scope) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy bản ghi phân quyền'], 404);
        }

        $scope->delete();

        return response()->json(['status' => 'success', 'message' => 'Thu hồi quyền truy cập kho thành công']);
    }
}
