<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Đặt giữ tồn kho (inventory reservations).
     * Để sẵn (non-MVP): tạo bảng + FK + index, CHƯA wire vào luồng xuất kho.
     * Khi bật: duyệt phiếu xuất -> tạo reservation (status active, reserved_qty +);
     * confirm xuất -> chuyển consumed & trừ available; hủy -> released.
     */
    public function up(): void
    {
        Schema::create('inventory_reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('sku_id');
            $table->decimal('quantity', 18, 2)->comment('Số lượng giữ chỗ (> 0)');

            // Nguồn giữ chỗ: phiếu kho nội bộ hoặc tham chiếu ngoài (đơn bán hàng...)
            $table->unsignedBigInteger('source_document_id')->nullable();
            $table->unsignedBigInteger('source_document_line_id')->nullable();
            $table->string('reference_type', 50)->nullable()->comment('VD: sale_order (tham chiếu ngoài)');
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->tinyInteger('status')->default(1)->comment('1: Đang giữ, 2: Đã nhả, 3: Đã dùng');
            $table->timestamp('expires_at')->nullable()->comment('Tự hết hạn giữ chỗ (nếu có)');
            $table->string('note', 500)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('sku_id')->references('id')->on('skus')->onDelete('cascade');
            $table->foreign('source_document_id')->references('id')->on('stock_documents')->nullOnDelete();
            $table->foreign('source_document_line_id')->references('id')->on('stock_document_lines')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();

            $table->index(['warehouse_id', 'sku_id', 'status']);
            $table->index('status');
            $table->index('expires_at');
            $table->index(['source_document_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_reservations');
    }
};
