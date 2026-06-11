<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Warehouse\Entities\SafeStockConfig;

class SafeStockConfigController extends Controller
{
    /**
     * @OA\Get(
     *     path="/safe-stock-configs",
     *     tags={"Safe Stock Configs"},
     *     summary="Lấy danh sách cấu hình tồn kho an toàn (Min/Max)",
     *     description="Cấu hình tồn an toàn theo SKU và (tùy chọn) theo kho. warehouse_id null = áp dụng toàn hệ thống. Tính năng non-MVP, đã sẵn sàng.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="sku_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="warehouse_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="limit", in="query", required=false, @OA\Schema(type="integer", default=15)),
     *     @OA\Response(response=200, description="Lấy danh sách thành công"),
     *     @OA\Response(response=401, description="Chưa xác thực tài khoản")
     * )
     */
    public function index(Request $request)
    {
        $query = SafeStockConfig::with(['sku', 'warehouse']);

        if ($request->filled('sku_id')) {
            $query->where('sku_id', $request->sku_id);
        }
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        $configs = $query->orderByDesc('effective_from')->paginate($request->get('limit', 15));

        return response()->json(['status' => 'success', 'data' => $configs]);
    }

    /**
     * @OA\Post(
     *     path="/safe-stock-configs",
     *     tags={"Safe Stock Configs"},
     *     summary="Tạo cấu hình tồn kho an toàn",
     *     description="Ràng buộc duy nhất theo (sku_id, warehouse_id, effective_from). Cần ít nhất một trong min_qty/max_qty; nếu cả hai thì max_qty >= min_qty.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"sku_id","effective_from"},
     *             @OA\Property(property="sku_id", type="integer", example=1),
     *             @OA\Property(property="warehouse_id", type="integer", nullable=true, example=1, description="null = áp dụng toàn hệ thống"),
     *             @OA\Property(property="min_qty", type="number", format="float", nullable=true, example=10),
     *             @OA\Property(property="max_qty", type="number", format="float", nullable=true, example=100),
     *             @OA\Property(property="effective_from", type="string", format="date", example="2026-06-08"),
     *             @OA\Property(property="effective_to", type="string", format="date", nullable=true, example=null),
     *             @OA\Property(property="note", type="string", nullable=true, example="Cấu hình mùa cao điểm")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Tạo thành công"),
     *     @OA\Response(response=400, description="Dữ liệu không hợp lệ"),
     *     @OA\Response(response=401, description="Chưa xác thực tài khoản"),
     *     @OA\Response(response=409, description="Đã tồn tại cấu hình cho cùng SKU/kho/ngày hiệu lực")
     * )
     */
    public function store(Request $request)
    {
        $validator = $this->makeValidator($request);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Dữ liệu đầu vào không hợp lệ',
                'errors'  => $validator->errors(),
            ], 400);
        }

        $data = $validator->validated();

        $exists = SafeStockConfig::where('sku_id', $data['sku_id'])
            ->where('warehouse_id', $data['warehouse_id'] ?? null)
            ->where('effective_from', $data['effective_from'])
            ->exists();

        if ($exists) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Đã tồn tại cấu hình cho cùng SKU/kho/ngày hiệu lực',
            ], 409);
        }

        $data['created_by'] = Auth::id();
        $config = SafeStockConfig::create($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Tạo cấu hình tồn kho an toàn thành công',
            'data'    => $config->load(['sku', 'warehouse']),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/safe-stock-configs/{id}",
     *     tags={"Safe Stock Configs"},
     *     summary="Xem chi tiết cấu hình tồn kho an toàn",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Thành công"),
     *     @OA\Response(response=404, description="Không tìm thấy cấu hình")
     * )
     */
    public function show($id)
    {
        $config = SafeStockConfig::with(['sku', 'warehouse'])->find($id);

        if (!$config) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy cấu hình'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $config]);
    }

    /**
     * @OA\Put(
     *     path="/safe-stock-configs/{id}",
     *     tags={"Safe Stock Configs"},
     *     summary="Cập nhật cấu hình tồn kho an toàn",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="min_qty", type="number", format="float", nullable=true, example=20),
     *             @OA\Property(property="max_qty", type="number", format="float", nullable=true, example=200),
     *             @OA\Property(property="effective_to", type="string", format="date", nullable=true, example="2026-12-31"),
     *             @OA\Property(property="note", type="string", nullable=true, example="Điều chỉnh ngưỡng")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Cập nhật thành công"),
     *     @OA\Response(response=400, description="Dữ liệu không hợp lệ"),
     *     @OA\Response(response=404, description="Không tìm thấy cấu hình")
     * )
     */
    public function update(Request $request, $id)
    {
        $config = SafeStockConfig::find($id);

        if (!$config) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy cấu hình'], 404);
        }

        $validator = Validator::make($request->all(), [
            'min_qty'      => 'nullable|numeric|min:0',
            'max_qty'      => 'nullable|numeric|min:0',
            'effective_to' => 'nullable|date',
            'note'         => 'nullable|string|max:500',
        ]);

        $validator->after(function ($v) use ($request, $config) {
            $min = $request->has('min_qty') ? $request->min_qty : $config->min_qty;
            $max = $request->has('max_qty') ? $request->max_qty : $config->max_qty;
            if ($min !== null && $max !== null && (float) $max < (float) $min) {
                $v->errors()->add('max_qty', 'max_qty phải lớn hơn hoặc bằng min_qty');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Dữ liệu chỉnh sửa không hợp lệ',
                'errors'  => $validator->errors(),
            ], 400);
        }

        $data = $validator->validated();
        $data['updated_by'] = Auth::id();
        $config->update($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Cập nhật cấu hình tồn kho an toàn thành công',
            'data'    => $config->load(['sku', 'warehouse']),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/safe-stock-configs/{id}",
     *     tags={"Safe Stock Configs"},
     *     summary="Xóa cấu hình tồn kho an toàn",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Xóa thành công"),
     *     @OA\Response(response=404, description="Không tìm thấy cấu hình")
     * )
     */
    public function destroy($id)
    {
        $config = SafeStockConfig::find($id);

        if (!$config) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy cấu hình'], 404);
        }

        $config->delete();

        return response()->json(['status' => 'success', 'message' => 'Xóa cấu hình tồn kho an toàn thành công']);
    }

    /** Validator cho create — kiểm tra tồn tại SKU/kho và quan hệ min/max. */
    private function makeValidator(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sku_id'         => 'required|integer|exists:skus,id',
            'warehouse_id'   => 'nullable|integer|exists:warehouses,id',
            'min_qty'        => 'nullable|numeric|min:0',
            'max_qty'        => 'nullable|numeric|min:0',
            'effective_from' => 'required|date',
            'effective_to'   => 'nullable|date|after_or_equal:effective_from',
            'note'           => 'nullable|string|max:500',
        ]);

        $validator->after(function ($v) use ($request) {
            if ($request->min_qty === null && $request->max_qty === null) {
                $v->errors()->add('min_qty', 'Cần nhập ít nhất một trong min_qty hoặc max_qty');
            }
            if ($request->min_qty !== null && $request->max_qty !== null
                && (float) $request->max_qty < (float) $request->min_qty) {
                $v->errors()->add('max_qty', 'max_qty phải lớn hơn hoặc bằng min_qty');
            }
        });

        return $validator;
    }
}
