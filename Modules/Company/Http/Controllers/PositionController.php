<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Company\Entities\Position;
use Illuminate\Support\Facades\Validator;

class PositionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/positions",
     *     tags={"Positions"},
     *     summary="Lấy danh sách chức vụ",
     *     description="Lấy danh sách chức vụ hỗ trợ tìm kiếm theo tên và phân trang.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Số thứ tự trang cần lấy dữ liệu",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Số lượng bản ghi trên một trang",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Từ khóa tìm kiếm theo tên chức vụ",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
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
     *                         @OA\Property(property="name", type="string", example="Giám Đốc"),
     *                         @OA\Property(property="description", type="string", example="Quản lý điều hành toàn diện")
     *                     )
     *                 ),
     *                 @OA\Property(property="total", type="integer", example=2)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Chưa xác thực tài khoản"),
     *     @OA\Response(response=403, description="Không có quyền truy cập")
     * )
     */
    public function index(Request $request)
    {
        $query = Position::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $limit = $request->get('limit', 15);
        $positions = $query->paginate($limit);

        return response()->json([
            'status' => 'success',
            'data' => $positions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:225|unique:positions,name',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu đầu vào không hợp lệ',
                'errors' => $validator->errors()
            ], 400);
        }

        $position = Position::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo chức vụ thành công',
            'data' => $position
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $position = Position::with('users')->find($id);

        if (!$position) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy chức vụ'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $position
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $position = Position::find($id);

        if (!$position) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy chức vụ'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:225|unique:positions,name,' . $id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu chỉnh sửa không hợp lệ',
                'errors' => $validator->errors()
            ], 400);
        }

        $position->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật chức vụ thành công',
            'data' => $position
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $position = Position::find($id);

        if (!$position) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy chức vụ'
            ], 404);
        }

        $position->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Xóa chức vụ thành công'
        ]);
    }
}
