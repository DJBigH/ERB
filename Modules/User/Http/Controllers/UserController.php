<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Entities\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query()->with(['company', 'branch', 'department', 'position', 'role']);

        // Search by name, email, or code
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->has('company_id') && $request->company_id != '') {
            $query->where('company_id', $request->company_id);
        }

        if ($request->has('branch_id') && $request->branch_id != '') {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->has('department_id') && $request->department_id != '') {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        $limit = $request->get('limit', 15);
        $users = $query->paginate($limit);

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
            'branch_id' => 'required|exists:branches,id',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'role_id' => 'required|exists:roles,id',
            'code' => 'required|string|max:225|unique:users,code',
            'name' => 'required|string|max:20',
            'email' => 'required|email|max:225|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'gender' => 'required|integer|in:0,1',
            'birthday' => 'required|date',
            'address' => 'nullable|string',
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
        $data['password'] = Hash::make($request->password);
        $data['status'] = $request->get('status', 1);

        $user = User::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo nhân viên thành công',
            'data' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::with(['company', 'branch', 'department', 'position', 'role.permissions'])->find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy nhân viên'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy nhân viên'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'company_id' => 'sometimes|required|exists:companies,id',
            'branch_id' => 'sometimes|required|exists:branches,id',
            'department_id' => 'sometimes|required|exists:departments,id',
            'position_id' => 'sometimes|required|exists:positions,id',
            'role_id' => 'sometimes|required|exists:roles,id',
            'code' => 'sometimes|required|string|max:225|unique:users,code,' . $id,
            'name' => 'sometimes|required|string|max:20',
            'email' => 'sometimes|required|email|max:225|unique:users,email,' . $id,
            'phone' => 'sometimes|required|string|max:20',
            'password' => 'sometimes|nullable|string|min:6',
            'gender' => 'sometimes|required|integer|in:0,1',
            'birthday' => 'sometimes|required|date',
            'address' => 'nullable|string',
            'status' => 'sometimes|required|integer|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu chỉnh sửa không hợp lệ',
                'errors' => $validator->errors()
            ], 400);
        }

        $data = $request->all();
        if ($request->has('password') && $request->password != '') {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật nhân viên thành công',
            'data' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy nhân viên'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Xóa nhân viên thành công'
        ]);
    }
}
