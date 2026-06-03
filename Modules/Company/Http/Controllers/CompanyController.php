<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Company\Entities\Company;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * @OA\Get(
     *     path="/companies",
     *     tags={"Companies"},
     *     summary="Lấy danh sách công ty",
     *     description="Lấy danh sách công ty hỗ trợ tìm kiếm theo tên/mã/MST, lọc theo trạng thái và phân trang.",
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
     *         description="Từ khóa tìm kiếm theo tên, mã hoặc mã số thuế công ty",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Trạng thái hoạt động (1: Kích hoạt, 0: Tạm khóa)",
     *         required=false,
     *         @OA\Schema(type="integer", enum={0, 1})
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
     *                         @OA\Property(property="name", type="string", example="Tổng Công Ty ERB"),
     *                         @OA\Property(property="code", type="string", example="ERB_CORP"),
     *                         @OA\Property(property="tax_code", type="string", example="0101234567"),
     *                         @OA\Property(property="phone", type="string", example="0243999999"),
     *                         @OA\Property(property="email", type="string", example="info@erb.vn"),
     *                         @OA\Property(property="address", type="string", example="Cầu Giấy, Hà Nội"),
     *                         @OA\Property(property="status", type="integer", example=1),
     *                         @OA\Property(property="parent_id", type="integer", nullable=true, example=null)
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
        $query = Company::query()->with(['parent', 'children']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('tax_code', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        $limit = $request->get('limit', 15);
        $companies = $query->paginate($limit);

        return response()->json([
            'status' => 'success',
            'data' => $companies
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:225',
            'code' => 'required|string|max:225|unique:companies,code',
            'tax_code' => 'required|string|max:225|unique:companies,tax_code',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:100|unique:companies,email',
            'address' => 'nullable|string|max:225',
            'status' => 'nullable|integer|in:0,1',
            'parent_id' => 'nullable|integer|exists:companies,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu đầu vào không hợp lệ',
                'errors' => $validator->errors()
            ], 400);
        }

        $data = $request->all();
        $data['status'] = $request->get('status', 1);

        $company = Company::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo công ty thành công',
            'data' => $company
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $company = Company::with(['parent', 'children', 'branches'])->find($id);

        if (!$company) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy công ty'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $company
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $company = Company::find($id);

        if (!$company) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy công ty'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:225',
            'code' => 'sometimes|required|string|max:225|unique:companies,code,' . $id,
            'tax_code' => 'sometimes|required|string|max:225|unique:companies,tax_code,' . $id,
            'phone' => 'sometimes|required|string|max:20',
            'email' => 'sometimes|required|email|max:100|unique:companies,email,' . $id,
            'address' => 'nullable|string|max:225',
            'status' => 'sometimes|required|integer|in:0,1',
            'parent_id' => 'nullable|integer|exists:companies,id|not_in:' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu chỉnh sửa không hợp lệ',
                'errors' => $validator->errors()
            ], 400);
        }

        $company->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật công ty thành công',
            'data' => $company
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $company = Company::find($id);

        if (!$company) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy công ty'
            ], 404);
        }

        $company->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Xóa công ty thành công'
        ]);
    }
}
