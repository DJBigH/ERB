<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Entities\Role;
use Modules\User\Entities\Permission;
use Illuminate\Support\Facades\Validator;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the roles.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();

        return response()->json([
            'status' => 'success',
            'data' => $roles
        ]);
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:20|unique:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 400);
        }

        $role = Role::create([
            'name' => $request->name
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tạo vai trò thành công',
            'data' => $role
        ], 201);
    }

    /**
     * Display the specified role with its permissions.
     */
    public function show($id)
    {
        $role = Role::with('permissions')->find($id);

        if (!$role) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy vai trò'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $role
        ]);
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy vai trò'
            ], 404);
        }

        // Dissociate permissions first (handled by onDelete('cascade') in DB, but good to run sync([]) too)
        $role->permissions()->sync([]);
        $role->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Xóa vai trò thành công'
        ]);
    }

    /**
     * Display a listing of all system permissions.
     */
    public function permissions()
    {
        $permissions = Permission::all();

        return response()->json([
            'status' => 'success',
            'data' => $permissions
        ]);
    }

    /**
     * Assign permissions to a specified role.
     */
    public function assignPermissions(Request $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy vai trò'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'permissions_id' => 'required|array',
            'permissions_id.*' => 'integer|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Danh sách mã quyền gửi lên không hợp lệ',
                'errors' => $validator->errors()
            ], 400);
        }

        // Sync uses the custom pivot column settings mapped in the BelongsToMany relation
        $role->permissions()->sync($request->permissions_id);

        return response()->json([
            'status' => 'success',
            'message' => 'Gán quyền cho vai trò thành công',
            'data' => $role->load('permissions')
        ]);
    }
}
