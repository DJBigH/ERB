<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Warehouse Module API Routes  (prefix: /api/v1, namespace set by provider)
|--------------------------------------------------------------------------
| Bảo vệ bằng auth:api (JWT). Bổ sung middleware permission:quan_ly_kho khi
| Module 10 khai báo quyền nghiệp vụ kho.
*/

Route::middleware('auth:api')->group(function () {

    // ===== Master data: Kho =====
    Route::patch('warehouses/{id}/disable', 'WarehouseController@disable');
    Route::patch('warehouses/{id}/enable', 'WarehouseController@enable');
    Route::apiResource('warehouses', WarehouseController::class);

    // ===== Master data: SKU =====
    Route::patch('skus/{id}/disable', 'SkuController@disable');
    Route::patch('skus/{id}/enable', 'SkuController@enable');
    Route::apiResource('skus', SkuController::class);

    // ===== Nhập kho =====
    Route::get('inbound-documents', 'InboundController@index');
    Route::post('inbound-documents', 'InboundController@store');
    Route::get('inbound-documents/{id}', 'InboundController@show');
    Route::post('inbound-documents/{id}/submit', 'InboundController@submit');
    Route::post('inbound-documents/{id}/approve', 'InboundController@approve');
    Route::post('inbound-documents/{id}/confirm', 'InboundController@confirm');
    Route::post('inbound-documents/{id}/cancel', 'InboundController@cancel');

    // ===== Xuất kho =====
    Route::get('outbound-documents', 'OutboundController@index');
    Route::post('outbound-documents', 'OutboundController@store');
    Route::get('outbound-documents/{id}', 'OutboundController@show');
    Route::post('outbound-documents/{id}/submit', 'OutboundController@submit');
    Route::post('outbound-documents/{id}/approve', 'OutboundController@approve');
    Route::post('outbound-documents/{id}/confirm', 'OutboundController@confirm');
    Route::post('outbound-documents/{id}/cancel', 'OutboundController@cancel');

    // ===== Điều chuyển =====
    Route::get('transfer-orders', 'TransferController@index');
    Route::post('transfer-orders', 'TransferController@store');
    Route::get('transfer-orders/{id}', 'TransferController@show');
    Route::post('transfer-orders/{id}/submit', 'TransferController@submit');
    Route::post('transfer-orders/{id}/approve', 'TransferController@approve');
    Route::post('transfer-orders/{id}/dispatch', 'TransferController@dispatch');
    Route::post('transfer-orders/{id}/receive', 'TransferController@receive');
    Route::post('transfer-orders/{id}/return', 'TransferController@returnTransfer');
    Route::post('transfer-orders/{id}/cancel', 'TransferController@cancel');

    // ===== Tồn kho / Lịch sử / Báo cáo =====
    Route::get('inventory/balances', 'InventoryController@index');
    Route::get('stock-movements', 'StockMovementController@index');
    Route::get('reports/inventory-xnt', 'ReportController@inventoryXnt');

    // ===== Master data: Nhà cung cấp =====
    Route::patch('suppliers/{id}/disable', 'SupplierController@disable');
    Route::patch('suppliers/{id}/enable', 'SupplierController@enable');
    Route::apiResource('suppliers', SupplierController::class);

    // ===== Master data: Nhóm sản phẩm (cây danh mục) =====
    Route::apiResource('product-categories', ProductCategoryController::class);

    // ===== Cấu hình tồn kho an toàn (non-MVP) =====
    Route::apiResource('safe-stock-configs', SafeStockConfigController::class)->names('safe-stock');

    // ===== Phân quyền user theo kho (data scope) =====
    Route::get('warehouse-scopes', 'WarehouseScopeController@index');
    Route::post('warehouse-scopes', 'WarehouseScopeController@store');
    Route::delete('warehouse-scopes/{id}', 'WarehouseScopeController@destroy');

    // ===== Đặt giữ tồn kho (read-only, non-MVP) =====
    Route::get('inventory-reservations', 'ReservationController@index');
    Route::get('inventory-reservations/{id}', 'ReservationController@show');

    // ===== Sổ In-Transit (read-only) =====
    Route::get('in-transit-ledger', 'InTransitLedgerController@index');

    // ===== Lịch sử trạng thái chứng từ (read-only) =====
    Route::get('document-status-history', 'DocumentStatusHistoryController@index');

    // ===== Snapshot tồn kho =====
    Route::get('inventory-snapshots', 'InventorySnapshotController@index');
    Route::post('inventory-snapshots/generate', 'InventorySnapshotController@generate');
});
