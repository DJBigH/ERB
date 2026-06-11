<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_document_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_document_id');
            $table->unsignedBigInteger('sku_id');
            $table->decimal('quantity', 18, 2)->comment('Số lượng (> 0)');
            $table->decimal('unit_price', 18, 2)->nullable()->comment('Đơn giá nhập - lưu phục vụ mở rộng (không tính COGS trong MVP)');
            $table->decimal('received_qty', 18, 2)->nullable()->comment('Số lượng thực nhận (điều chuyển)');
            $table->string('note', 500)->nullable();
            $table->timestamps();

            $table->foreign('stock_document_id')->references('id')->on('stock_documents')->onDelete('cascade');
            $table->foreign('sku_id')->references('id')->on('skus')->onDelete('cascade');

            $table->index('stock_document_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_document_lines');
    }
};
