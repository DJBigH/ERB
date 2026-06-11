<?php

namespace Modules\Warehouse\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Warehouse\Entities\DocumentStatusHistory;

/**
 * Lịch sử chuyển trạng thái chứng từ kho (append-only). Read-only.
 */
class DocumentStatusHistoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/document-status-history",
     *     tags={"Document Status History"},
     *     summary="Lấy lịch sử chuyển trạng thái chứng từ",
     *     description="Truy vết các lần đổi trạng thái của phiếu (draft → pending → approved ...). Lọc theo phiếu (stock_document_id).",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="stock_document_id", in="query", required=false, description="ID phiếu kho cần xem lịch sử", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="limit", in="query", required=false, @OA\Schema(type="integer", default=50)),
     *     @OA\Response(response=200, description="Lấy danh sách thành công"),
     *     @OA\Response(response=401, description="Chưa xác thực tài khoản")
     * )
     */
    public function index(Request $request)
    {
        $query = DocumentStatusHistory::with(['changedBy']);

        if ($request->filled('stock_document_id')) {
            $query->where('stock_document_id', $request->stock_document_id);
        }

        $history = $query->orderByDesc('id')->paginate($request->get('limit', 50));

        return response()->json(['status' => 'success', 'data' => $history]);
    }
}
