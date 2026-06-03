<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Company\Entities\Company;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
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
