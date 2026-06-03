<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Company\Entities\Department;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Department::query()->with(['branch', 'parent', 'children']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->has('branch_id') && $request->branch_id != '') {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        $limit = $request->get('limit', 15);
        $departments = $query->paginate($limit);

        return response()->json([
            'status' => 'success',
            'data' => $departments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|exists:branches,id',
            'parent_id' => 'nullable|integer|exists:departments,id',
            'name' => 'required|string|max:225',
            'code' => 'required|string|max:225|unique:departments,code',
            'description' => 'nullable|string',
            'status' => 'nullable|integer|in:0,1',
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

        $department = Department::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo phòng ban thành công',
            'data' => $department
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $department = Department::with(['branch', 'parent', 'children', 'users'])->find($id);

        if (!$department) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy phòng ban'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $department
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy phòng ban'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'branch_id' => 'sometimes|required|exists:branches,id',
            'parent_id' => 'nullable|integer|exists:departments,id|not_in:' . $id,
            'name' => 'sometimes|required|string|max:225',
            'code' => 'sometimes|required|string|max:225|unique:departments,code,' . $id,
            'description' => 'nullable|string',
            'status' => 'sometimes|required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu chỉnh sửa không hợp lệ',
                'errors' => $validator->errors()
            ], 400);
        }

        $department->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật phòng ban thành công',
            'data' => $department
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy phòng ban'
            ], 404);
        }

        $department->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Xóa phòng ban thành công'
        ]);
    }
}
