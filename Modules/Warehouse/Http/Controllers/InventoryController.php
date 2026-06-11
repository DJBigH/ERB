<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Warehouse\Entities\InventoryBalance;

class InventoryController extends Controller
{
    /**
     * @OA\Get(path="/inventory/balances", tags={"Inventory (Tồn kho)"},
     *   summary="Xem tồn kho theo kho hoặc toàn hệ thống",
     *   description="scope=warehouse (mặc định) hoặc system (tổng hợp theo SKU toàn hệ thống).",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="warehouse_id", in="query", @OA\Schema(type="integer")),
     *   @OA\Parameter(name="sku_id", in="query", @OA\Schema(type="integer")),
     *   @OA\Parameter(name="search", in="query", description="Tìm theo mã/tên SKU", @OA\Schema(type="string")),
     *   @OA\Parameter(name="scope", in="query", @OA\Schema(type="string", enum={"warehouse","system"})),
     *   @OA\Response(response=200, description="OK"))
     */
    public function index(Request $request)
    {
        // Toàn hệ thống: tổng hợp theo SKU
        if ($request->get('scope') === 'system') {
            $rows = DB::table('inventory_balances as ib')
                ->join('skus as s', 's.id', '=', 'ib.sku_id')
                ->selectRaw('ib.sku_id, s.code as sku_code, s.name as sku_name, s.unit,
                    SUM(ib.available_qty) as available_qty,
                    SUM(ib.in_transit_qty) as in_transit_qty,
                    SUM(ib.reserved_qty) as reserved_qty')
                ->when($request->filled('sku_id'), fn ($q) => $q->where('ib.sku_id', $request->sku_id))
                ->when($request->filled('search'), fn ($q) => $q->where(function ($w) use ($request) {
                    $w->where('s.code', 'like', "%{$request->search}%")->orWhere('s.name', 'like', "%{$request->search}%");
                }))
                ->groupBy('ib.sku_id', 's.code', 's.name', 's.unit')
                ->orderBy('s.code')
                ->paginate($request->get('limit', 20));

            return response()->json(['status' => 'success', 'scope' => 'system', 'data' => $rows]);
        }

        // Theo từng kho
        $query = InventoryBalance::query()->with(['warehouse', 'sku']);
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        if ($request->filled('sku_id')) {
            $query->where('sku_id', $request->sku_id);
        }
        if ($request->filled('search')) {
            $query->whereHas('sku', function ($w) use ($request) {
                $w->where('code', 'like', "%{$request->search}%")->orWhere('name', 'like', "%{$request->search}%");
            });
        }

        return response()->json([
            'status' => 'success',
            'scope'  => 'warehouse',
            'data'   => $query->orderBy('warehouse_id')->orderBy('sku_id')->paginate($request->get('limit', 20)),
        ]);
    }
}
