<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Warehouse\Entities\Sku;

class SkuController extends Controller
{
    /**
     * @OA\Get(
     *     path="/skus",
     *     tags={"SKUs"},
     *     summary="Lấy danh sách sản phẩm (SKU)",
     *     description="Danh sách SKU hỗ trợ tìm kiếm theo mã/tên, lọc theo nhóm hàng, trạng thái, có serial và phân trang.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="page", in="query", required=false, description="Trang", @OA\Schema(type="integer", default=1)),
     *     @OA\Parameter(name="limit", in="query", required=false, description="Số bản ghi/trang", @OA\Schema(type="integer", default=15)),
     *     @OA\Parameter(name="search", in="query", required=false, description="Tìm theo mã hoặc tên SKU", @OA\Schema(type="string")),
     *     @OA\Parameter(name="category", in="query", required=false, description="Lọc theo nhóm sản phẩm", @OA\Schema(type="string")),
     *     @OA\Parameter(name="has_serial", in="query", required=false, description="Quản lý serial (1: Có, 0: Không)", @OA\Schema(type="integer", enum={0,1})),
     *     @OA\Parameter(name="status", in="query", required=false, description="Trạng thái (1: Hoạt động, 0: Ngừng)", @OA\Schema(type="integer", enum={0,1})),
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
     *                         @OA\Property(property="code", type="string", example="SP001"),
     *                         @OA\Property(property="name", type="string", example="Áo thun nam"),
     *                         @OA\Property(property="unit", type="string", example="cái"),
     *                         @OA\Property(property="category", type="string", nullable=true, example="Thời trang"),
     *                         @OA\Property(property="has_serial", type="boolean", example=false),
     *                         @OA\Property(property="status", type="integer", example=1)
     *                     )
     *                 ),
     *                 @OA\Property(property="total", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Chưa xác thực tài khoản")
     * )
     */
    public function index(Request $request)
    {
        $query = Sku::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->has('has_serial') && $request->has_serial !== null && $request->has_serial !== '') {
            $query->where('has_serial', (bool) $request->has_serial);
        }
        if ($request->has('status') && $request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $limit = $request->get('limit', 15);
        $skus = $query->orderBy('id')->paginate($limit);

        return response()->json([
            'status' => 'success',
            'data' => $skus,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/skus",
     *     tags={"SKUs"},
     *     summary="Tạo SKU mới",
     *     description="Tạo sản phẩm. Mã SKU (code) là duy nhất toàn hệ thống.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"code","name","unit"},
     *             @OA\Property(property="code", type="string", example="SP001"),
     *             @OA\Property(property="name", type="string", example="Áo thun nam"),
     *             @OA\Property(property="unit", type="string", example="cái"),
     *             @OA\Property(property="category", type="string", nullable=true, example="Thời trang"),
     *             @OA\Property(property="has_serial", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", enum={0,1}, example=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Tạo SKU thành công"),
     *     @OA\Response(response=400, description="Dữ liệu không hợp lệ"),
     *     @OA\Response(response=401, description="Chưa xác thực tài khoản")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'       => 'required|string|max:100|unique:skus,code',
            'name'       => 'required|string|max:255',
            'unit'       => 'required|string|max:50',
            'category'   => 'nullable|string|max:255',
            'has_serial' => 'nullable|boolean',
            'status'     => 'nullable|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Dữ liệu đầu vào không hợp lệ',
                'errors'  => $validator->errors(),
            ], 400);
        }

        $data = $validator->validated();
        $data['has_serial'] = $request->boolean('has_serial');
        $data['status'] = $request->get('status', 1);

        $sku = Sku::create($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Tạo SKU thành công',
            'data'    => $sku,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/skus/{id}",
     *     tags={"SKUs"},
     *     summary="Xem chi tiết SKU",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Thành công"),
     *     @OA\Response(response=404, description="Không tìm thấy SKU")
     * )
     */
    public function show($id)
    {
        $sku = Sku::withCount(['balances', 'movements'])->find($id);

        if (!$sku) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Không tìm thấy SKU',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $sku,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/skus/{id}",
     *     tags={"SKUs"},
     *     summary="Cập nhật SKU",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="SP001"),
     *             @OA\Property(property="name", type="string", example="Áo thun nam (mới)"),
     *             @OA\Property(property="unit", type="string", example="cái"),
     *             @OA\Property(property="category", type="string", nullable=true, example="Thời trang"),
     *             @OA\Property(property="has_serial", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", enum={0,1}, example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cập nhật thành công"),
     *     @OA\Response(response=400, description="Dữ liệu không hợp lệ"),
     *     @OA\Response(response=404, description="Không tìm thấy SKU")
     * )
     */
    public function update(Request $request, $id)
    {
        $sku = Sku::find($id);

        if (!$sku) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Không tìm thấy SKU',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'code'       => 'sometimes|required|string|max:100|unique:skus,code,' . $id,
            'name'       => 'sometimes|required|string|max:255',
            'unit'       => 'sometimes|required|string|max:50',
            'category'   => 'nullable|string|max:255',
            'has_serial' => 'nullable|boolean',
            'status'     => 'sometimes|required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Dữ liệu chỉnh sửa không hợp lệ',
                'errors'  => $validator->errors(),
            ], 400);
        }

        $sku->update($validator->validated());

        return response()->json([
            'status'  => 'success',
            'message' => 'Cập nhật SKU thành công',
            'data'    => $sku,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/skus/{id}",
     *     tags={"SKUs"},
     *     summary="Xóa SKU",
     *     description="Chỉ xóa được khi SKU chưa phát sinh tồn/giao dịch. Khuyến nghị dùng Ngừng hoạt động thay vì xóa.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Xóa thành công"),
     *     @OA\Response(response=404, description="Không tìm thấy SKU"),
     *     @OA\Response(response=409, description="SKU đang được sử dụng, không thể xóa")
     * )
     */
    public function destroy($id)
    {
        $sku = Sku::withCount(['balances', 'movements'])->find($id);

        if (!$sku) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Không tìm thấy SKU',
            ], 404);
        }

        if ($sku->balances_count > 0 || $sku->movements_count > 0) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Không thể xóa SKU đã phát sinh tồn/giao dịch. Hãy ngừng hoạt động SKU.',
            ], 409);
        }

        $sku->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Xóa SKU thành công',
        ]);
    }

    /**
     * @OA\Patch(
     *     path="/skus/{id}/disable",
     *     tags={"SKUs"},
     *     summary="Ngừng hoạt động SKU",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Đã ngừng hoạt động"),
     *     @OA\Response(response=404, description="Không tìm thấy SKU")
     * )
     */
    public function disable($id)
    {
        return $this->setStatus($id, 0, 'Đã ngừng hoạt động SKU');
    }

    /**
     * @OA\Patch(
     *     path="/skus/{id}/enable",
     *     tags={"SKUs"},
     *     summary="Kích hoạt lại SKU",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Đã kích hoạt"),
     *     @OA\Response(response=404, description="Không tìm thấy SKU")
     * )
     */
    public function enable($id)
    {
        return $this->setStatus($id, 1, 'Đã kích hoạt lại SKU');
    }

    /** Đặt trạng thái hoạt động cho SKU. */
    private function setStatus($id, int $status, string $message)
    {
        $sku = Sku::find($id);

        if (!$sku) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Không tìm thấy SKU',
            ], 404);
        }

        $sku->update(['status' => $status]);

        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => $sku,
        ]);
    }
}
