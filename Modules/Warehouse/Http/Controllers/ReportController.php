<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    /**
     * @OA\Get(path="/reports/inventory-xnt", tags={"Report (Báo cáo XNT)"},
     *   summary="Báo cáo Xuất - Nhập - Tồn theo kỳ",
     *   description="Tồn đầu kỳ + Nhập - Xuất (± điều chuyển) = Tồn cuối kỳ. scope=warehouse | system.",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="from", in="query", required=true, @OA\Schema(type="string", format="date")),
     *   @OA\Parameter(name="to", in="query", required=true, @OA\Schema(type="string", format="date")),
     *   @OA\Parameter(name="warehouse_id", in="query", @OA\Schema(type="integer")),
     *   @OA\Parameter(name="scope", in="query", @OA\Schema(type="string", enum={"warehouse","system"})),
     *   @OA\Response(response=200, description="OK"), @OA\Response(response=400, description="Thiếu/khoảng ngày sai"))
     */
    public function inventoryXnt(Request $request)
    {
        $v = Validator::make($request->all(), [
            'from' => 'required|date',
            'to'   => 'required|date|after_or_equal:from',
            'warehouse_id' => 'nullable|integer|exists:warehouses,id',
            'scope' => 'nullable|in:warehouse,system',
        ]);
        if ($v->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Tham số không hợp lệ (cần from ≤ to)', 'errors' => $v->errors()], 400);
        }

        $fromStart = $request->from . ' 00:00:00';
        $toEnd     = $request->to . ' 23:59:59';
        $system    = $request->get('scope') === 'system';

        // Biểu thức tổng hợp theo công thức Phụ lục D-III (quantity có dấu, bám available)
        $selects = [
            "SUM(CASE WHEN m.created_at < ? THEN m.quantity ELSE 0 END) AS opening",
            "SUM(CASE WHEN m.created_at BETWEEN ? AND ? AND m.movement_type = 1 THEN m.quantity ELSE 0 END) AS nhap",
            "-SUM(CASE WHEN m.created_at BETWEEN ? AND ? AND m.movement_type = 2 THEN m.quantity ELSE 0 END) AS xuat",
            "-SUM(CASE WHEN m.created_at BETWEEN ? AND ? AND m.movement_type = 3 THEN m.quantity ELSE 0 END) AS dc_out",
            "SUM(CASE WHEN m.created_at BETWEEN ? AND ? AND m.movement_type = 4 THEN m.quantity ELSE 0 END) AS dc_in",
            "SUM(CASE WHEN m.created_at BETWEEN ? AND ? AND m.movement_type = 5 THEN m.quantity ELSE 0 END) AS adjust",
            "SUM(CASE WHEN m.created_at <= ? THEN m.quantity ELSE 0 END) AS closing",
        ];
        $bindings = [
            $fromStart,
            $fromStart, $toEnd,
            $fromStart, $toEnd,
            $fromStart, $toEnd,
            $fromStart, $toEnd,
            $fromStart, $toEnd,
            $toEnd,
        ];

        $q = DB::table('stock_movements as m')->join('skus as s', 's.id', '=', 'm.sku_id');

        if ($system) {
            $q->selectRaw('m.sku_id, s.code as sku_code, s.name as sku_name, s.unit, ' . implode(', ', $selects), $bindings)
              ->groupBy('m.sku_id', 's.code', 's.name', 's.unit')->orderBy('s.code');
        } else {
            $q->join('warehouses as w', 'w.id', '=', 'm.warehouse_id')
              ->selectRaw('m.warehouse_id, w.code as warehouse_code, w.name as warehouse_name, m.sku_id, s.code as sku_code, s.name as sku_name, s.unit, ' . implode(', ', $selects), $bindings)
              ->when($request->filled('warehouse_id'), fn ($x) => $x->where('m.warehouse_id', $request->warehouse_id))
              ->groupBy('m.warehouse_id', 'w.code', 'w.name', 'm.sku_id', 's.code', 's.name', 's.unit')
              ->orderBy('w.code')->orderBy('s.code');
        }

        return response()->json([
            'status' => 'success',
            'period' => ['from' => $request->from, 'to' => $request->to],
            'scope'  => $system ? 'system' : 'warehouse',
            'note'   => $system ? 'Điều chuyển nội bộ không tính vào tổng tồn toàn hệ thống.' : null,
            'data'   => $q->get(),
        ]);
    }
}
