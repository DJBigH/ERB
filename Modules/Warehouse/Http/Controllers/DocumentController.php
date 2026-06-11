<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Warehouse\Entities\StockDocument;
use Modules\Warehouse\Exceptions\WarehouseException;
use Modules\Warehouse\Services\StockDocumentService;

/**
 * Lớp cơ sở cho 3 loại phiếu (Nhập / Xuất / Điều chuyển).
 * Giữ logic chung: list / show / submit / approve / cancel + xử lý lỗi nghiệp vụ.
 */
abstract class DocumentController extends Controller
{
    protected int $type;

    public function __construct(protected StockDocumentService $service) {}

    /** Bọc gọi service, biến WarehouseException thành JSON đúng HTTP status. */
    protected function run(Closure $fn)
    {
        try {
            return $fn();
        } catch (WarehouseException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], $e->statusCode);
        }
    }

    protected function ok($data, ?string $message = null, int $code = 200)
    {
        $payload = ['status' => 'success', 'data' => $data];
        if ($message) {
            $payload['message'] = $message;
        }
        return response()->json($payload, $code);
    }

    protected function uid(): int
    {
        return (int) auth()->id();
    }

    public function index(Request $request)
    {
        $query = StockDocument::query()->where('type', $this->type)
            ->with(['sourceWarehouse', 'destWarehouse', 'creator']);

        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('warehouse_id')) {
            $wid = $request->warehouse_id;
            $query->where(function ($q) use ($wid) {
                $q->where('source_warehouse_id', $wid)->orWhere('dest_warehouse_id', $wid);
            });
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        return $this->ok($query->orderByDesc('id')->paginate($request->get('limit', 15)));
    }

    public function show($id)
    {
        $doc = StockDocument::where('type', $this->type)->where('id', $id)
            ->with(['lines.sku', 'sourceWarehouse', 'destWarehouse', 'creator', 'approver', 'performer'])
            ->first();
        if (!$doc) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy phiếu'], 404);
        }
        return $this->ok($doc);
    }

    public function submit($id)
    {
        return $this->run(fn () => $this->ok($this->service->submit($id, $this->uid(), request()->ip()), 'Đã gửi duyệt'));
    }

    public function approve($id)
    {
        return $this->run(fn () => $this->ok($this->service->approve($id, $this->uid(), request()->ip()), 'Đã duyệt phiếu'));
    }

    public function cancel(Request $request, $id)
    {
        return $this->run(fn () => $this->ok(
            $this->service->cancel($id, $request->input('reason'), $this->uid(), request()->ip()),
            'Đã hủy phiếu'
        ));
    }

    /** Validate phần dòng sản phẩm dùng chung. */
    protected function validateLines(Request $request)
    {
        return Validator::make($request->all(), [
            'note'             => 'nullable|string|max:1000',
            'lines'            => 'required|array|min:1',
            'lines.*.sku_id'   => 'required|integer|exists:skus,id',
            'lines.*.quantity' => 'required|numeric|gt:0',
            'lines.*.unit_price' => 'nullable|numeric|gte:0',
            'lines.*.note'     => 'nullable|string|max:500',
        ]);
    }
}
