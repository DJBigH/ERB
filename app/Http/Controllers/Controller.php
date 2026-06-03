<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="ERB System API Documentation",
 *     version="1.0.0",
 *     description="Tài liệu đặc tả và kiểm thử RESTful API - Hệ thống quản trị ERB",
 *     @OA\Contact(
 *         email="admin@erb.vn"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000/api/v1",
 *     description="Local Development Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Nhập JWT Access Token (dạng: Bearer <token>) để thực hiện các yêu cầu cần xác thực."
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
