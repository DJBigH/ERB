<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Company\Entities\Branch;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    /**
     * @OA\Get(
     *     path="/branches",
     *     tags={"Branches"},
     *     summary="Lấy danh sách chi nhánh",
     *     description="Lấy danh sách chi nhánh hỗ trợ tìm kiếm, lọc theo công ty, trạng thái và phân trang.",
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
     *         description="Từ khóa tìm kiếm theo tên, mã hoặc mã số thuế chi nhánh",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="company_id",
     *         in="query",
     *         description="Lọc chi nhánh trực thuộc một công ty cụ thể",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Trạng thái hoạt động (1: Kích hoạt, 0: Tạm ngưng)",
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
     *                         @OA\Property(property="company_id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Chi Nhánh Hà Nội"),
     *                         @OA\Property(property="code", type="string", example="CN_HN"),
     *                         @OA\Property(property="tax_code", type="string", example="0101234567-001"),
     *                         @OA\Property(property="phone", type="string", example="0243999111"),
     *                         @OA\Property(property="email", type="string", example="hn@erb.vn"),
     *                         @OA\Property(property="address", type="string", example="Cầu Giấy, Hà Nội"),
     *                         @OA\Property(property="status", type="integer", example=1)
     *                     )
     *                 ),
     *                 @OA\Property(property="total", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Chưa xác thực tài khoản"),
     *     @OA\Response(response=403, description="Không có quyền truy cập")
     * )
     */
    public function index(Request $request)
    {
        $query = Branch::query()->with('company');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('tax_code', 'like', "%{$search}%");
            });
        }

        if ($request->has('company_id') && $request->company_id != '') {
            $query->where('company_id', $request->company_id);
        }

        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        $limit = $request->get('limit', 15);
        $branches = $query->paginate($limit);

        return response()->json([
            'status' => 'success',
            'data' => $branches
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:225',
            'code' => 'required|string|max:225|unique:branches,code',
            'tax_code' => 'nullable|string|max:225|unique:branches,tax_code',
            'phone' => 'required|string|max:225',
            'email' => 'required|email|max:225|unique:branches,email',
            'address' => 'nullable|string|max:225',
            'status' => 'nullable|integer|in:0,1',
        ], [
            'company_id.required' => 'Company ID là bắt buộc.',
            'company_id.exists' => 'Company ID không tồn tại.',
            'name.required' => 'Tên chi nhánh là bắt buộc.',
            'name.string' => 'Tên chi nhánh phải là chuỗi.',
            'name.max' => 'Tên chi nhánh không được vượt quá 255 ký tự.',
            'code.required' => 'Mã chi nhánh là bắt buộc.',
            'code.string' => 'Mã chi nhánh phải là chuỗi.',
            'code.max' => 'Mã chi nhánh không được vượt quá 255 ký tự.',
            'code.unique' => 'Mã chi nhánh đã tồn tại.',
            'tax_code.string' => 'Mã thuế chi nhánh phải là chuỗi.',
            'tax_code.max' => 'Mã thuế chi nhánh không được vượt quá 255 ký tự.',
            'tax_code.unique' => 'Mã thuế chi nhánh đã tồn tại.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.string' => 'Số điện thoại phải là chuỗi.',
            'phone.max' => 'Số điện thoại không được vượt quá 225 ký tự.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.max' => 'Email không được vượt quá 225 ký tự.',
            'email.unique' => 'Email đã tồn tại.',
            'address.string' => 'Địa chỉ phải là chuỗi.',
            'address.max' => 'Địa chỉ không được vượt quá 225 ký tự.',
            'status.integer' => 'Trạng thái phải là số nguyên.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 400);
        }

        $data = $request->all();
        $data['status'] = $request->get('status', 1);

        $branch = Branch::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo chi nhánh thành công',
            'data' => $branch
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $branch = Branch::with(['company', 'departments'])->find($id);

        if (!$branch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy chi nhánh'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $branch
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $branch = Branch::find($id);

        if (!$branch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy chi nhánh'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'company_id' => 'sometimes|required|exists:companies,id',
            'name' => 'sometimes|required|string|max:225',
            'code' => 'sometimes|required|string|max:225|unique:branches,code,' . $id,
            'tax_code' => 'nullable|string|max:225|unique:branches,tax_code,' . $id,
            'phone' => 'sometimes|required|string|max:225',
            'email' => 'sometimes|required|email|max:225|unique:branches,email,' . $id,
            'address' => 'nullable|string|max:225',
            'status' => 'sometimes|required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu chỉnh sửa không hợp lệ',
                'errors' => $validator->errors()
            ], 400);
        }

        $branch->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật chi nhánh thành công',
            'data' => $branch
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $branch = Branch::find($id);

        if (!$branch) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy chi nhánh'
            ], 404);
        }

        $branch->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Xóa chi nhánh thành công'
        ]);
    }
}
