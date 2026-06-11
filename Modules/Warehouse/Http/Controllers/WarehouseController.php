<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Warehouse\Entities\Warehouse;

class WarehouseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/warehouses",
     *     tags={"Warehouses"},
     *     summary="Lấy danh sách kho",
     *     description="Danh sách kho hỗ trợ tìm kiếm theo mã/tên, lọc theo công ty, chi nhánh, loại kho, kho cha, trạng thái và phân trang.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="page", in="query", required=false, description="Trang", @OA\Schema(type="integer", default=1)),
     *     @OA\Parameter(name="limit", in="query", required=false, description="Số bản ghi/trang", @OA\Schema(type="integer", default=15)),
     *     @OA\Parameter(name="search", in="query", required=false, description="Tìm theo mã hoặc tên kho", @OA\Schema(type="string")),
     *     @OA\Parameter(name="company_id", in="query", required=false, description="Lọc theo công ty", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="branch_id", in="query", required=false, description="Lọc theo chi nhánh", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="warehouse_type", in="query", required=false, description="Loại kho (1: Kho tổng, 2: Kho con)", @OA\Schema(type="integer", enum={1,2})),
     *     @OA\Parameter(name="parent_id", in="query", required=false, description="Lọc theo kho cha", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="status", in="query", required=false, description="Trạng thái (1: Hoạt động, 0: Ngừng hoạt động)", @OA\Schema(type="integer", enum={0,1})),
     *     @OA\Response(
     *         response=200,
     *         description="Lấy danh sách thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="data", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="company_id", type="integer", example=1),
     *                         @OA\Property(property="branch_id", type="integer", nullable=true, example=1),
     *                         @OA\Property(property="parent_id", type="integer", nullable=true, example=null),
     *                         @OA\Property(property="code", type="string", example="WH001"),
     *                         @OA\Property(property="name", type="string", example="Kho Tổng HCM"),
     *                         @OA\Property(property="warehouse_type", type="integer", example=1),
     *                         @OA\Property(property="address", type="string", example="123 Nguyễn Huệ, Quận 1"),
     *                         @OA\Property(property="manager_id", type="integer", nullable=true, example=1),
     *                         @OA\Property(property="status", type="integer", example=1)
     *                     )
     *                 ),
     *                 @OA\Property(property="total", type="integer", example=4)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Chưa xác thực tài khoản")
     * )
     */
    public function index(Request $request)
    {
        $query = Warehouse::query()->with(['parent', 'children', 'manager']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->filled('warehouse_type')) {
            $query->where('warehouse_type', $request->warehouse_type);
        }
        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }
        if ($request->has('status') && $request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $limit = $request->get('limit', 15);
        $warehouses = $query->orderBy('id')->paginate($limit);

        return response()->json([
            'status' => 'success',
            'data' => $warehouses,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/warehouses",
     *     tags={"Warehouses"},
     *     summary="Tạo kho mới",
     *     description="Tạo kho tổng hoặc kho con. Mã kho (code) là duy nhất toàn hệ thống.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"company_id","code","name","warehouse_type"},
     *             @OA\Property(property="company_id", type="integer", example=1),
     *             @OA\Property(property="branch_id", type="integer", nullable=true, example=1),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, description="Kho cha (chỉ với kho con)", example=null),
     *             @OA\Property(property="code", type="string", example="WH010"),
     *             @OA\Property(property="name", type="string", example="Kho Quận 7"),
     *             @OA\Property(property="warehouse_type", type="integer", enum={1,2}, example=2),
     *             @OA\Property(property="address", type="string", nullable=true, example="Quận 7, TP.HCM"),
     *             @OA\Property(property="manager_id", type="integer", nullable=true, example=1),
     *             @OA\Property(property="status", type="integer", enum={0,1}, example=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Tạo kho thành công"),
     *     @OA\Response(response=400, description="Dữ liệu không hợp lệ"),
     *     @OA\Response(response=401, description="Chưa xác thực tài khoản")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id'     => 'required|integer|exists:companies,id',
            'branch_id'      => 'nullable|integer|exists:branches,id',
            'parent_id'      => 'nullable|integer|exists:warehouses,id',
            'code'           => 'required|string|max:225|unique:warehouses,code',
            'name'           => 'required|string|max:225',
            'warehouse_type' => 'required|integer|in:1,2',
            'address'        => 'nullable|string|max:225',
            'manager_id'     => 'nullable|integer|exists:users,id',
            'status'         => 'nullable|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Dữ liệu đầu vào không hợp lệ',
                'errors'  => $validator->errors(),
            ], 400);
        }

        $data = $validator->validated();
        $data['status'] = $request->get('status', 1);

        $warehouse = Warehouse::create($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Tạo kho thành công',
            'data'    => $warehouse,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/warehouses/{id}",
     *     tags={"Warehouses"},
     *     summary="Xem chi tiết kho",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Thành công"),
     *     @OA\Response(response=404, description="Không tìm thấy kho")
     * )
     */
    public function show($id)
    {
        $warehouse = Warehouse::with(['parent', 'children', 'manager', 'company', 'branch'])->find($id);

        if (!$warehouse) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Không tìm thấy kho',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $warehouse,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/warehouses/{id}",
     *     tags={"Warehouses"},
     *     summary="Cập nhật kho",
     *     description="Cập nhật thông tin kho. KHÔNG cho phép đổi mã kho (code).",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="branch_id", type="integer", nullable=true, example=1),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, example=1),
     *             @OA\Property(property="name", type="string", example="Kho Quận 7 (mới)"),
     *             @OA\Property(property="warehouse_type", type="integer", enum={1,2}, example=2),
     *             @OA\Property(property="address", type="string", nullable=true, example="Quận 7, TP.HCM"),
     *             @OA\Property(property="manager_id", type="integer", nullable=true, example=1),
     *             @OA\Property(property="status", type="integer", enum={0,1}, example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cập nhật thành công"),
     *     @OA\Response(response=400, description="Dữ liệu không hợp lệ"),
     *     @OA\Response(response=404, description="Không tìm thấy kho")
     * )
     */
    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::find($id);

        if (!$warehouse) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Không tìm thấy kho',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'company_id'     => 'sometimes|required|integer|exists:companies,id',
            'branch_id'      => 'nullable|integer|exists:branches,id',
            'parent_id'      => 'nullable|integer|exists:warehouses,id|not_in:' . $id,
            'name'           => 'sometimes|required|string|max:225',
            'warehouse_type' => 'sometimes|required|integer|in:1,2',
            'address'        => 'nullable|string|max:225',
            'manager_id'     => 'nullable|integer|exists:users,id',
            'status'         => 'sometimes|required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Dữ liệu chỉnh sửa không hợp lệ',
                'errors'  => $validator->errors(),
            ], 400);
        }

        // Chống tạo vòng quan hệ kho cha - kho con
        if ($request->filled('parent_id') && $this->wouldCreateCycle($warehouse->id, (int) $request->parent_id)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Không thể chọn kho cha vì sẽ tạo quan hệ vòng (cha-con).',
            ], 400);
        }

        // Bỏ qua mọi thay đổi mã kho (code không được sửa)
        $warehouse->update($validator->safe()->except('code'));

        return response()->json([
            'status'  => 'success',
            'message' => 'Cập nhật kho thành công',
            'data'    => $warehouse,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/warehouses/{id}",
     *     tags={"Warehouses"},
     *     summary="Xóa kho",
     *     description="Chỉ xóa được kho khi không còn kho con và chưa phát sinh tồn/giao dịch. Khuyến nghị dùng Ngừng hoạt động thay vì xóa.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Xóa thành công"),
     *     @OA\Response(response=404, description="Không tìm thấy kho"),
     *     @OA\Response(response=409, description="Kho đang được sử dụng, không thể xóa")
     * )
     */
    public function destroy($id)
    {
        $warehouse = Warehouse::withCount(['children', 'balances', 'movements'])->find($id);

        if (!$warehouse) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Không tìm thấy kho',
            ], 404);
        }

        if ($warehouse->children_count > 0 || $warehouse->balances_count > 0 || $warehouse->movements_count > 0) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Không thể xóa kho đang có kho con hoặc đã phát sinh tồn/giao dịch. Hãy ngừng hoạt động kho.',
            ], 409);
        }

        $warehouse->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Xóa kho thành công',
        ]);
    }

    /**
     * @OA\Patch(
     *     path="/warehouses/{id}/disable",
     *     tags={"Warehouses"},
     *     summary="Ngừng hoạt động kho",
     *     description="Đặt trạng thái kho = 0 (Ngừng hoạt động). Kho ngừng hoạt động bị chặn mọi nghiệp vụ nhập/xuất/điều chuyển.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Đã ngừng hoạt động"),
     *     @OA\Response(response=404, description="Không tìm thấy kho")
     * )
     */
    public function disable($id)
    {
        return $this->setStatus($id, 0, 'Đã ngừng hoạt động kho');
    }

    /**
     * @OA\Patch(
     *     path="/warehouses/{id}/enable",
     *     tags={"Warehouses"},
     *     summary="Kích hoạt lại kho",
     *     description="Đặt trạng thái kho = 1 (Hoạt động).",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Đã kích hoạt"),
     *     @OA\Response(response=404, description="Không tìm thấy kho")
     * )
     */
    public function enable($id)
    {
        return $this->setStatus($id, 1, 'Đã kích hoạt lại kho');
    }

    /** Đặt trạng thái hoạt động cho kho. */
    private function setStatus($id, int $status, string $message)
    {
        $warehouse = Warehouse::find($id);

        if (!$warehouse) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Không tìm thấy kho',
            ], 404);
        }

        $warehouse->update(['status' => $status]);

        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $warehouse,
        ]);
    }

    /** Kiểm tra việc gán $parentId cho kho $id có tạo vòng cha-con không. */
    private function wouldCreateCycle(int $id, int $parentId): bool
    {
        if ($id === $parentId) {
            return true;
        }
        $cursor = Warehouse::find($parentId);
        $guard = 0;
        while ($cursor && $guard++ < 50) {
            if ((int) $cursor->id === $id) {
                return true; // gặp lại chính nó trên đường đi lên -> vòng
            }
            if ($cursor->parent_id === null) {
                break;
            }
            $cursor = Warehouse::find($cursor->parent_id);
        }
        return false;
    }
}
