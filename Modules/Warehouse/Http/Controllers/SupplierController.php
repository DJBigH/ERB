<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Warehouse\Entities\Supplier;

class SupplierController extends Controller
{
    /**
     * @OA\Get(
     *     path="/suppliers",
     *     tags={"Suppliers"},
     *     summary="Lấy danh sách nhà cung cấp",
     *     description="Danh sách nhà cung cấp, hỗ trợ tìm kiếm theo mã/tên/điện thoại, lọc trạng thái và phân trang.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="page", in="query", required=false, @OA\Schema(type="integer", default=1)),
     *     @OA\Parameter(name="limit", in="query", required=false, @OA\Schema(type="integer", default=15)),
     *     @OA\Parameter(name="search", in="query", required=false, description="Tìm theo mã/tên/điện thoại", @OA\Schema(type="string")),
     *     @OA\Parameter(name="status", in="query", required=false, description="1: Hoạt động, 0: Ngừng", @OA\Schema(type="integer", enum={0,1})),
     *     @OA\Response(response=200, description="Lấy danh sách thành công"),
     *     @OA\Response(response=401, description="Chưa xác thực tài khoản")
     * )
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        if ($request->has('status') && $request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $suppliers = $query->orderBy('id')->paginate($request->get('limit', 15));

        return response()->json(['status' => 'success', 'data' => $suppliers]);
    }

    /**
     * @OA\Post(
     *     path="/suppliers",
     *     tags={"Suppliers"},
     *     summary="Tạo nhà cung cấp mới",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"code","name"},
     *             @OA\Property(property="code", type="string", example="NCC001"),
     *             @OA\Property(property="name", type="string", example="Công ty TNHH ABC"),
     *             @OA\Property(property="phone", type="string", nullable=true, example="0901234567"),
     *             @OA\Property(property="address", type="string", nullable=true, example="123 Lê Lợi, Q1, TP.HCM"),
     *             @OA\Property(property="status", type="integer", enum={0,1}, example=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Tạo thành công"),
     *     @OA\Response(response=400, description="Dữ liệu không hợp lệ"),
     *     @OA\Response(response=401, description="Chưa xác thực tài khoản")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'    => 'required|string|max:225|unique:suppliers,code',
            'name'    => 'required|string|max:225',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:225',
            'status'  => 'nullable|integer|in:0,1',
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

        $supplier = Supplier::create($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Tạo nhà cung cấp thành công',
            'data'    => $supplier,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/suppliers/{id}",
     *     tags={"Suppliers"},
     *     summary="Xem chi tiết nhà cung cấp",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Thành công"),
     *     @OA\Response(response=404, description="Không tìm thấy nhà cung cấp")
     * )
     */
    public function show($id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy nhà cung cấp'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $supplier]);
    }

    /**
     * @OA\Put(
     *     path="/suppliers/{id}",
     *     tags={"Suppliers"},
     *     summary="Cập nhật nhà cung cấp",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="NCC001"),
     *             @OA\Property(property="name", type="string", example="Công ty TNHH ABC (mới)"),
     *             @OA\Property(property="phone", type="string", nullable=true, example="0901234567"),
     *             @OA\Property(property="address", type="string", nullable=true, example="456 Nguyễn Huệ"),
     *             @OA\Property(property="status", type="integer", enum={0,1}, example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cập nhật thành công"),
     *     @OA\Response(response=400, description="Dữ liệu không hợp lệ"),
     *     @OA\Response(response=404, description="Không tìm thấy nhà cung cấp")
     * )
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy nhà cung cấp'], 404);
        }

        $validator = Validator::make($request->all(), [
            'code'    => 'sometimes|required|string|max:225|unique:suppliers,code,' . $id,
            'name'    => 'sometimes|required|string|max:225',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:225',
            'status'  => 'sometimes|required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Dữ liệu chỉnh sửa không hợp lệ',
                'errors'  => $validator->errors(),
            ], 400);
        }

        $supplier->update($validator->validated());

        return response()->json([
            'status'  => 'success',
            'message' => 'Cập nhật nhà cung cấp thành công',
            'data'    => $supplier,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/suppliers/{id}",
     *     tags={"Suppliers"},
     *     summary="Xóa nhà cung cấp",
     *     description="Khuyến nghị dùng Ngừng hoạt động thay vì xóa nếu nhà cung cấp đã gắn với phiếu nhập.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Xóa thành công"),
     *     @OA\Response(response=404, description="Không tìm thấy nhà cung cấp")
     * )
     */
    public function destroy($id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy nhà cung cấp'], 404);
        }

        $supplier->delete();

        return response()->json(['status' => 'success', 'message' => 'Xóa nhà cung cấp thành công']);
    }

    /**
     * @OA\Patch(
     *     path="/suppliers/{id}/disable",
     *     tags={"Suppliers"},
     *     summary="Ngừng hoạt động nhà cung cấp",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Đã ngừng hoạt động"),
     *     @OA\Response(response=404, description="Không tìm thấy nhà cung cấp")
     * )
     */
    public function disable($id)
    {
        return $this->setStatus($id, 0, 'Đã ngừng hoạt động nhà cung cấp');
    }

    /**
     * @OA\Patch(
     *     path="/suppliers/{id}/enable",
     *     tags={"Suppliers"},
     *     summary="Kích hoạt lại nhà cung cấp",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Đã kích hoạt"),
     *     @OA\Response(response=404, description="Không tìm thấy nhà cung cấp")
     * )
     */
    public function enable($id)
    {
        return $this->setStatus($id, 1, 'Đã kích hoạt lại nhà cung cấp');
    }

    /** Đặt trạng thái hoạt động cho nhà cung cấp. */
    private function setStatus($id, int $status, string $message)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy nhà cung cấp'], 404);
        }

        $supplier->update(['status' => $status]);

        return response()->json(['status' => 'success', 'message' => $message, 'data' => $supplier]);
    }
}
