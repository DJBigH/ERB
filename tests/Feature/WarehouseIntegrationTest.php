<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\User\Entities\User;
use Modules\Warehouse\Entities\Warehouse;
use Modules\Warehouse\Entities\Sku;
use Modules\Warehouse\Entities\StockDocument;

class WarehouseIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    protected string $token;
    protected array $headers;

    protected function setUp(): void
    {
        parent::setUp();

        // Perform login to get JWT Token
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@erb.vn',
            'password' => '12345678',
        ]);

        $response->assertStatus(200);
        $this->token = $response->json('data.access_token');
        $this->assertNotEmpty($this->token);

        $this->headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ];
    }

    public function test_warehouse_full_workflow()
    {
        // ==========================================
        // 1. Tạo kho chính (Main Warehouse)
        // ==========================================
        $response = $this->postJson('/api/v1/warehouses', [
            'company_id' => 1,
            'branch_id' => 1,
            'code' => 'WH_TEST_MAIN_' . uniqid(),
            'name' => 'Kho Tổng Kiểm Tra',
            'warehouse_type' => 1,
            'address' => '123 Đường Chính, Hà Nội',
            'status' => 1
        ], $this->headers);

        $response->assertStatus(201);
        $mainWhId = $response->json('data.id');
        $this->assertNotNull($mainWhId);

        // ==========================================
        // 2. Tạo kho chi nhánh (Branch Warehouse)
        // ==========================================
        $response = $this->postJson('/api/v1/warehouses', [
            'company_id' => 1,
            'branch_id' => 1,
            'parent_id' => $mainWhId,
            'code' => 'WH_TEST_BRANCH_' . uniqid(),
            'name' => 'Kho Con Kiểm Tra',
            'warehouse_type' => 2,
            'address' => '456 Đường Phụ, Hà Nội',
            'status' => 1
        ], $this->headers);

        $response->assertStatus(201);
        $branchWhId = $response->json('data.id');
        $this->assertNotNull($branchWhId);

        // ==========================================
        // 3. Tạo sản phẩm (SKU)
        // ==========================================
        $response = $this->postJson('/api/v1/skus', [
            'code' => 'SKU_TEST_' . uniqid(),
            'name' => 'Sản Phẩm Kiểm Tra',
            'unit' => 'cái',
            'category' => 'Điện tử',
            'has_serial' => false,
            'status' => 1
        ], $this->headers);

        $response->assertStatus(201);
        $skuId = $response->json('data.id');
        $this->assertNotNull($skuId);

        // ==========================================
        // 4. Tạo bản nháp phiếu nhập kho (Draft Inbound)
        // ==========================================
        $response = $this->postJson('/api/v1/inbound-documents', [
            'dest_warehouse_id' => $mainWhId,
            'note' => 'Nhập kho thử nghiệm',
            'lines' => [
                [
                    'sku_id' => $skuId,
                    'quantity' => 100,
                    'unit_price' => 50000
                ]
            ]
        ], $this->headers);

        $response->assertStatus(201);
        $inboundId = $response->json('data.id');
        $this->assertNotNull($inboundId);

        // ==========================================
        // 5. Xác nhận nhận hàng (Submit -> Approve -> Confirm Inbound)
        // ==========================================
        // Gửi duyệt
        $response = $this->postJson("/api/v1/inbound-documents/{$inboundId}/submit", [], $this->headers);
        $response->assertStatus(200);

        // Duyệt
        $response = $this->postJson("/api/v1/inbound-documents/{$inboundId}/approve", [], $this->headers);
        $response->assertStatus(200);

        // Xác nhận thực nhận
        $response = $this->postJson("/api/v1/inbound-documents/{$inboundId}/confirm", [], $this->headers);
        $response->assertStatus(200);

        // Kiểm tra tồn kho tại kho chính phải là 100
        $response = $this->getJson('/api/v1/inventory/balances?warehouse_id=' . $mainWhId . '&sku_id=' . $skuId, $this->headers);
        $response->assertStatus(200);
        $data = $response->json('data.data');
        $this->assertCount(1, $data);
        $this->assertEquals(100, $data[0]['available_qty']);

        // ==========================================
        // 6. Tạo bản nháp phát hành hàng tồn kho / xuất kho (Draft Outbound)
        // ==========================================
        $response = $this->postJson('/api/v1/outbound-documents', [
            'source_warehouse_id' => $mainWhId,
            'note' => 'Xuất kho thử nghiệm',
            'lines' => [
                [
                    'sku_id' => $skuId,
                    'quantity' => 30
                ]
            ]
        ], $this->headers);

        $response->assertStatus(201);
        $outboundId = $response->json('data.id');
        $this->assertNotNull($outboundId);

        // ==========================================
        // 7. Xác nhận vấn đề về hàng / xác nhận xuất kho (Submit -> Approve -> Confirm Outbound)
        // ==========================================
        // Gửi duyệt
        $response = $this->postJson("/api/v1/outbound-documents/{$outboundId}/submit", [], $this->headers);
        $response->assertStatus(200);

        // Duyệt
        $response = $this->postJson("/api/v1/outbound-documents/{$outboundId}/approve", [], $this->headers);
        $response->assertStatus(200);

        // Xác nhận xuất
        $response = $this->postJson("/api/v1/outbound-documents/{$outboundId}/confirm", [], $this->headers);
        $response->assertStatus(200);

        // Kiểm tra tồn kho tại kho chính phải giảm xuống còn 70
        $response = $this->getJson('/api/v1/inventory/balances?warehouse_id=' . $mainWhId . '&sku_id=' . $skuId, $this->headers);
        $response->assertStatus(200);
        $data = $response->json('data.data');
        $this->assertEquals(70, $data[0]['available_qty']);

        // ==========================================
        // 8. Chuyển kho hàng (Transfer Order)
        // ==========================================
        $response = $this->postJson('/api/v1/transfer-orders', [
            'source_warehouse_id' => $mainWhId,
            'dest_warehouse_id' => $branchWhId,
            'note' => 'Điều chuyển thử nghiệm',
            'lines' => [
                [
                    'sku_id' => $skuId,
                    'quantity' => 20
                ]
            ]
        ], $this->headers);

        $response->assertStatus(201);
        $transferId = $response->json('data.id');
        $this->assertNotNull($transferId);
        $transferLineId = $response->json('data.lines.0.id');
        $this->assertNotNull($transferLineId);

        // Gửi duyệt
        $response = $this->postJson("/api/v1/transfer-orders/{$transferId}/submit", [], $this->headers);
        $response->assertStatus(200);

        // Duyệt
        $response = $this->postJson("/api/v1/transfer-orders/{$transferId}/approve", [], $this->headers);
        $response->assertStatus(200);

        // Xuất điều chuyển (Vận chuyển)
        $response = $this->postJson("/api/v1/transfer-orders/{$transferId}/dispatch", [], $this->headers);
        $response->assertStatus(200);

        // Kiểm tra tồn kho tại kho chính phải giảm xuống còn 50
        $response = $this->getJson('/api/v1/inventory/balances?warehouse_id=' . $mainWhId . '&sku_id=' . $skuId, $this->headers);
        $response->assertStatus(200);
        $data = $response->json('data.data');
        $this->assertEquals(50, $data[0]['available_qty']);

        // Nhận điều chuyển
        $response = $this->postJson("/api/v1/transfer-orders/{$transferId}/receive", [
            'lines' => [
                [
                    'line_id' => $transferLineId,
                    'received_qty' => 20
                ]
            ]
        ], $this->headers);
        $response->assertStatus(200);

        // Kiểm tra tồn kho tại kho con phải là 20
        $response = $this->getJson('/api/v1/inventory/balances?warehouse_id=' . $branchWhId . '&sku_id=' . $skuId, $this->headers);
        $response->assertStatus(200);
        $data = $response->json('data.data');
        $this->assertCount(1, $data);
        $this->assertEquals(20, $data[0]['available_qty']);

        // ==========================================
        // 9. Nhận số dư hàng tồn kho (Get inventory balance)
        // ==========================================
        $response = $this->getJson('/api/v1/inventory/balances', $this->headers);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'warehouse_id',
                        'sku_id',
                        'available_qty',
                        'reserved_qty'
                    ]
                ]
            ]
        ]);

        // ==========================================
        // 10. Nhận báo cáo hàng tồn kho XNT (Get inventory XNT report)
        // ==========================================
        $today = date('Y-m-d');
        $response = $this->getJson("/api/v1/reports/inventory-xnt?from={$today}&to={$today}&scope=system", $this->headers);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                '*' => [
                    'sku_code',
                    'sku_name',
                    'opening',
                    'nhap',
                    'xuat',
                    'dc_out',
                    'dc_in',
                    'adjust',
                    'closing'
                ]
            ]
        ]);
    }
}
