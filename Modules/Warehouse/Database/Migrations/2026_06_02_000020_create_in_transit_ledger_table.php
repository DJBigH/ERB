<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sổ In-Transit theo cặp (kho nguồn → kho đích) - theo dõi điều chuyển chi tiết.
     * Để sẵn; engine hiện tại đang giữ in_transit trên bản ghi tồn kho nguồn.
     */
    public function up(): void
    {
        Schema::create('in_transit_ledger', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_document_id');
            $table->unsignedBigInteger('stock_document_line_id');
            $table->unsignedBigInteger('sku_id');
            $table->unsignedBigInteger('source_warehouse_id');
            $table->unsignedBigInteger('dest_warehouse_id');
            $table->decimal('qty_dispatched', 18, 2)->comment('Đã xuất khỏi kho nguồn');
            $table->decimal('qty_received', 18, 2)->default(0)->comment('Đã nhận tại kho đích');
            $table->decimal('qty_returned', 18, 2)->default(0)->comment('Trả lại kho nguồn');
            $table->timestamp('dispatched_at');
            $table->timestamp('received_at')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1: Đang vận chuyển, 2: Hoàn tất, 3: Đã trả lại');
            $table->timestamps();

            $table->foreign('stock_document_id')->references('id')->on('stock_documents')->onDelete('cascade');
            $table->foreign('stock_document_line_id')->references('id')->on('stock_document_lines')->onDelete('cascade');
            $table->foreign('sku_id')->references('id')->on('skus')->onDelete('cascade');
            $table->foreign('source_warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('dest_warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');

            $table->unique(['stock_document_id', 'stock_document_line_id'], 'uq_itl_line');
            $table->index(['source_warehouse_id', 'status']);
            $table->index(['dest_warehouse_id', 'status']);
            $table->index(['sku_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('in_transit_ledger');
    }
};
