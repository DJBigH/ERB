<?php

namespace Modules\Warehouse\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Warehouse\Entities\StockDocument;

class TransferController extends DocumentController
{
    protected int $type = StockDocument::TYPE_TRANSFER;

    /**
     * @OA\Get(path="/transfer-orders", tags={"Transfer (Điều chuyển)"}, summary="Danh sách phiếu điều chuyển",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="status", in="query", @OA\Schema(type="integer")),
     *   @OA\Parameter(name="warehouse_id", in="query", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK"))
     */
    public function index(Request $request) { return parent::index($request); }

    /**
     * @OA\Get(path="/transfer-orders/{id}", tags={"Transfer (Điều chuyển)"}, summary="Chi tiết phiếu điều chuyển",
     *   security={{"bearerAuth":{}}}, @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK"))
     */
    public function show($id) { return parent::show($id); }

    /**
     * @OA\Post(path="/transfer-orders", tags={"Transfer (Điều chuyển)"}, summary="Tạo phiếu điều chuyển (nháp)",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(required=true, @OA\JsonContent(
     *     required={"source_warehouse_id","dest_warehouse_id","lines"},
     *     @OA\Property(property="source_warehouse_id", type="integer", example=1),
     *     @OA\Property(property="dest_warehouse_id", type="integer", example=2),
     *     @OA\Property(property="note", type="string"),
     *     @OA\Property(property="lines", type="array", @OA\Items(
     *       @OA\Property(property="sku_id", type="integer", example=3),
     *       @OA\Property(property="quantity", type="number", example=20)
     *     ))
     *   )),
     *   @OA\Response(response=201, description="Tạo thành công"), @OA\Response(response=422, description="Kho nguồn trùng đích"))
     */
    public function store(Request $request)
    {
        $v = $this->validateLines($request);
        $v->addRules([
            'source_warehouse_id' => 'required|integer|exists:warehouses,id',
            'dest_warehouse_id'   => 'required|integer|exists:warehouses,id|different:source_warehouse_id',
        ]);
        if ($v->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Dữ liệu đầu vào không hợp lệ', 'errors' => $v->errors()], 400);
        }
        return $this->run(fn () => $this->ok(
            $this->service->create($this->type, $v->validated(), $this->uid(), $request->ip()),
            'Tạo phiếu điều chuyển thành công', 201
        ));
    }

    /**
     * @OA\Post(path="/transfer-orders/{id}/submit", tags={"Transfer (Điều chuyển)"}, summary="Gửi duyệt",
     *   security={{"bearerAuth":{}}}, @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK"))
     */
    public function submit($id) { return parent::submit($id); }

    /**
     * @OA\Post(path="/transfer-orders/{id}/approve", tags={"Transfer (Điều chuyển)"}, summary="Phê duyệt (kiểm tra tồn nguồn)",
     *   security={{"bearerAuth":{}}}, @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK"), @OA\Response(response=422, description="Tồn nguồn không đủ"))
     */
    public function approve($id) { return parent::approve($id); }

    /**
     * @OA\Post(path="/transfer-orders/{id}/dispatch", tags={"Transfer (Điều chuyển)"}, summary="Xuất điều chuyển (nguồn -, In-Transit +)",
     *   security={{"bearerAuth":{}}}, @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Đang vận chuyển"), @OA\Response(response=422, description="Tồn không đủ"))
     */
    public function dispatch($id)
    {
        return $this->run(fn () => $this->ok($this->service->dispatch($id, $this->uid(), request()->ip()), 'Đã xuất điều chuyển (Đang vận chuyển)'));
    }

    /**
     * @OA\Post(path="/transfer-orders/{id}/receive", tags={"Transfer (Điều chuyển)"}, summary="Nhận hàng (In-Transit -, đích +)",
     *   security={{"bearerAuth":{}}}, @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(required=false, @OA\JsonContent(
     *     @OA\Property(property="lines", type="array", @OA\Items(
     *       @OA\Property(property="line_id", type="integer", example=1),
     *       @OA\Property(property="received_qty", type="number", example=20)
     *     ))
     *   )),
     *   @OA\Response(response=200, description="Hoàn tất"), @OA\Response(response=422, description="Nhận vượt số điều chuyển"))
     */
    public function receive(Request $request, $id)
    {
        $map = [];
        foreach ((array) $request->input('lines', []) as $l) {
            if (isset($l['line_id'], $l['received_qty'])) {
                $map[(int) $l['line_id']] = $l['received_qty'];
            }
        }
        return $this->run(fn () => $this->ok($this->service->receive($id, $map, $this->uid(), request()->ip()), 'Đã nhận hàng điều chuyển'));
    }

    /**
     * @OA\Post(path="/transfer-orders/{id}/return", tags={"Transfer (Điều chuyển)"}, summary="Trả lại điều chuyển (hoàn nguồn)",
     *   security={{"bearerAuth":{}}}, @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(required=false, @OA\JsonContent(@OA\Property(property="reason", type="string"))),
     *   @OA\Response(response=200, description="Bị trả lại"))
     */
    public function returnTransfer(Request $request, $id)
    {
        return $this->run(fn () => $this->ok($this->service->returnTransfer($id, $request->input('reason'), $this->uid(), request()->ip()), 'Đã trả lại điều chuyển'));
    }

    /**
     * @OA\Post(path="/transfer-orders/{id}/cancel", tags={"Transfer (Điều chuyển)"}, summary="Hủy phiếu (trước khi xuất)",
     *   security={{"bearerAuth":{}}}, @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK"))
     */
    public function cancel(Request $request, $id) { return parent::cancel($request, $id); }
}
