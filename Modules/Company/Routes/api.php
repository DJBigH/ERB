<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'permission:quan_ly_nhan_su'])->group(function () {
    // Companies
    Route::apiResource('companies', CompanyController::class);

    // Branches
    Route::apiResource('branches', BranchController::class);

    // Departments
    Route::apiResource('departments', DepartmentController::class);

    // Positions
    Route::apiResource('positions', PositionController::class);
});
