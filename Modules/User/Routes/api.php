<?php

use Illuminate\Support\Facades\Route;

// Public routes
Route::post('auth/login', 'AuthController@login');

// Protected routes
Route::middleware('auth:api')->group(function () {
    Route::post('auth/logout', 'AuthController@logout');
    Route::get('auth/me', 'AuthController@me');

    // Roles Index is readable by any logged-in user to populate selectors
    Route::get('roles', 'RolePermissionController@index');

    // Users CRUD (Protected by quan_ly_nhan_su)
    Route::middleware('permission:quan_ly_nhan_su')->group(function () {
        Route::apiResource('users', UserController::class);
    });

    // Roles & Permissions management (Protected by quan_ly_he_thong)
    Route::middleware('permission:quan_ly_he_thong')->group(function () {
        Route::get('permissions', 'RolePermissionController@permissions');
        Route::post('roles/{id}/permissions', 'RolePermissionController@assignPermissions');
        Route::post('roles', 'RolePermissionController@store');
        Route::delete('roles/{id}', 'RolePermissionController@destroy');
        Route::get('roles/{id}', 'RolePermissionController@show');
    });
});
