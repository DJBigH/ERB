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
     * @OA\Get(
     *     path="/roles",
     *     tags={"Roles & Permissions"},
     *     summary="Lấy danh sách vai trò hệ thống",
     *     description="Lấy danh sách toàn bộ các vai trò hệ thống kèm theo các quyền tương ứng của từng vai trò.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lấy danh sách thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Super Admin"),
     *                     @OA\Property(property="permissions", type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="quan_ly_he_thong")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Chưa xác thực tài khoản")
     * )
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
     * @OA\Get(
     *     path="/permissions",
     *     tags={"Roles & Permissions"},
     *     summary="Lấy danh sách tất cả quyền hạn có sẵn",
     *     description="Lấy danh sách tất cả các quyền hệ thống hỗ trợ quản lý (Yêu cầu quyền: quan_ly_he_thong).",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lấy danh sách thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="quan_ly_he_thong")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Chưa xác thực tài khoản"),
     *     @OA\Response(response=403, description="Không có quyền truy cập")
     * )
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
     * @OA\Post(
     *     path="/roles/{id}/permissions",
     *     tags={"Roles & Permissions"},
     *     summary="Gán/Đồng bộ danh sách quyền cho vai trò",
     *     description="Gán hoặc đồng bộ mảng chứa các ID quyền cho một vai trò xác định bằng ID trên URL (Yêu cầu quyền: quan_ly_he_thong).",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của Vai trò cần gán quyền",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"permissions_id"},
     *             @OA\Property(property="permissions_id", type="array",
     *                 @OA\Items(type="integer", example=2)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Gán quyền thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Gán quyền cho vai trò thành công")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Dữ liệu gửi lên không hợp lệ"),
     *     @OA\Response(response=401, description="Chưa xác thực tài khoản"),
     *     @OA\Response(response=403, description="Không có quyền truy cập"),
     *     @OA\Response(response=404, description="Không tìm thấy vai trò")
     * )
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
