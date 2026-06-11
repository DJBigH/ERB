<?php

namespace Modules\Warehouse\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Warehouse\Entities\StockDocument;

class InboundController extends DocumentController
{
    protected int $type = StockDocument::TYPE_INBOUND;

    /**
     * @OA\Get(path="/inbound-documents", tags={"Inbound (Nhập kho)"}, summary="Danh sách phiếu nhập",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="status", in="query", @OA\Schema(type="integer")),
     *   @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
     *   @OA\Parameter(name="warehouse_id", in="query", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK"))
     */
    public function index(Request $request) { return parent::index($request); }

    /**
     * @OA\Get(path="/inbound-documents/{id}", tags={"Inbound (Nhập kho)"}, summary="Chi tiết phiếu nhập",
     *   security={{"bearerAuth":{}}}, @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK"))
     */
    public function show($id) { return parent::show($id); }

    /**
     * @OA\Post(path="/inbound-documents", tags={"Inbound (Nhập kho)"}, summary="Tạo phiếu nhập (nháp)",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(required=true, @OA\JsonContent(
     *     required={"dest_warehouse_id","lines"},
     *     @OA\Property(property="dest_warehouse_id", type="integer", example=1),
     *     @OA\Property(property="note", type="string"),
     *     @OA\Property(property="lines", type="array", @OA\Items(
     *       @OA\Property(property="sku_id", type="integer", example=3),
     *       @OA\Property(property="quantity", type="number", example=100),
     *       @OA\Property(property="unit_price", type="number", example=50000)
     *     ))
     *   )),
     *   @OA\Response(response=201, description="Tạo thành công"), @OA\Response(response=400, description="Lỗi dữ liệu"))
     */
    public function store(Request $request)
    {
        $v = $this->validateLines($request);
        $v->addRules(['dest_warehouse_id' => 'required|integer|exists:warehouses,id']);
        if ($v->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Dữ liệu đầu vào không hợp lệ', 'errors' => $v->errors()], 400);
        }
        return $this->run(fn () => $this->ok(
            $this->service->create($this->type, $v->validated(), $this->uid(), $request->ip()),
            'Tạo phiếu nhập thành công', 201
        ));
    }

    /**
     * @OA\Post(path="/inbound-documents/{id}/submit", tags={"Inbound (Nhập kho)"}, summary="Gửi duyệt",
     *   security={{"bearerAuth":{}}}, @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK"))
     */
    public function submit($id) { return parent::submit($id); }

    /**
     * @OA\Post(path="/inbound-documents/{id}/approve", tags={"Inbound (Nhập kho)"}, summary="Duyệt phiếu",
     *   security={{"bearerAuth":{}}}, @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK"))
     */
    public function approve($id) { return parent::approve($id); }

    /**
     * @OA\Post(path="/inbound-documents/{id}/confirm", tags={"Inbound (Nhập kho)"}, summary="Thực hiện nhập kho (tăng tồn)",
     *   security={{"bearerAuth":{}}}, @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Đã nhập kho, tồn tăng"))
     */
    public function confirm($id)
    {
        return $this->run(fn () => $this->ok($this->service->confirm($id, $this->uid(), request()->ip()), 'Đã nhập kho, tồn đã tăng'));
    }

    /**
     * @OA\Post(path="/inbound-documents/{id}/cancel", tags={"Inbound (Nhập kho)"}, summary="Hủy phiếu",
     *   security={{"bearerAuth":{}}}, @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK"))
     */
    public function cancel(Request $request, $id) { return parent::cancel($request, $id); }
}
