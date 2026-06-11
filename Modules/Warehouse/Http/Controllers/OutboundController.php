<?php

namespace Modules\Warehouse\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Warehouse\Entities\StockDocument;

class OutboundController extends DocumentController
{
    protected int $type = StockDocument::TYPE_OUTBOUND;

    /**
     * @OA\Get(path="/outbound-documents", tags={"Outbound (Xuất kho)"}, summary="Danh sách phiếu xuất",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="status", in="query", @OA\Schema(type="integer")),
     *   @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
     *   @OA\Parameter(name="warehouse_id", in="query", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK"))
     */
    public function index(Request $request) { return parent::index($request); }

    /**
     * @OA\Get(path="/outbound-documents/{id}", tags={"Outbound (Xuất kho)"}, summary="Chi tiết phiếu xuất",
     *   security={{"bearerAuth":{}}}, @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK"))
     */
    public function show($id) { return parent::show($id); }

    /**
     * @OA\Post(path="/outbound-documents", tags={"Outbound (Xuất kho)"}, summary="Tạo phiếu xuất (nháp)",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(required=true, @OA\JsonContent(
     *     required={"source_warehouse_id","lines"},
     *     @OA\Property(property="source_warehouse_id", type="integer", example=1),
     *     @OA\Property(property="note", type="string"),
     *     @OA\Property(property="lines", type="array", @OA\Items(
     *       @OA\Property(property="sku_id", type="integer", example=3),
     *       @OA\Property(property="quantity", type="number", example=10)
     *     ))
     *   )),
     *   @OA\Response(response=201, description="Tạo thành công"), @OA\Response(response=400, description="Lỗi dữ liệu"))
     */
    public function store(Request $request)
    {
        $v = $this->validateLines($request);
        $v->addRules(['source_warehouse_id' => 'required|integer|exists:warehouses,id']);
        if ($v->fails()) {
            return response()->json(['status' => 'error', 'message' => 'Dữ liệu đầu vào không hợp lệ', 'errors' => $v->errors()], 400);
        }
        return $this->run(fn () => $this->ok(
            $this->service->create($this->type, $v->validated(), $this->uid(), $request->ip()),
            'Tạo phiếu xuất thành công', 201
        ));
    }

    /**
     * @OA\Post(path="/outbound-documents/{id}/submit", tags={"Outbound (Xuất kho)"}, summary="Gửi duyệt",
     *   security={{"bearerAuth":{}}}, @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK"))
     */
    public function submit($id) { return parent::submit($id); }

    /**
     * @OA\Post(path="/outbound-documents/{id}/approve", tags={"Outbound (Xuất kho)"}, summary="Duyệt (kiểm tra tồn khả dụng)",
     *   security={{"bearerAuth":{}}}, @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK"), @OA\Response(response=422, description="Tồn không đủ"))
     */
    public function approve($id) { return parent::approve($id); }

    /**
     * @OA\Post(path="/outbound-documents/{id}/confirm", tags={"Outbound (Xuất kho)"}, summary="Thực hiện xuất kho (giảm tồn)",
     *   security={{"bearerAuth":{}}}, @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Đã xuất kho, tồn giảm"), @OA\Response(response=422, description="Tồn không đủ"))
     */
    public function confirm($id)
    {
        return $this->run(fn () => $this->ok($this->service->confirm($id, $this->uid(), request()->ip()), 'Đã xuất kho, tồn đã giảm'));
    }

    /**
     * @OA\Post(path="/outbound-documents/{id}/cancel", tags={"Outbound (Xuất kho)"}, summary="Hủy phiếu",
     *   security={{"bearerAuth":{}}}, @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK"))
     */
    public function cancel(Request $request, $id) { return parent::cancel($request, $id); }
}
