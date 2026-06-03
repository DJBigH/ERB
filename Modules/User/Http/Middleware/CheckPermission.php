<?php

namespace Modules\User\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Chưa xác thực tài khoản'
            ], 401);
        }

        // Super Admin has absolute master access and bypasses all restrictions
        if ($user->role && $user->role->name === 'Super Admin') {
            return $next($request);
        }

        // Verify if user's role contains this permission
        $hasPermission = $user->role && $user->role->permissions()
            ->where('name', $permission)
            ->exists();

        if (!$hasPermission) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn không có quyền thực hiện hành động này (Yêu cầu quyền: ' . $permission . ')'
            ], 403);
        }

        return $next($request);
    }
}
