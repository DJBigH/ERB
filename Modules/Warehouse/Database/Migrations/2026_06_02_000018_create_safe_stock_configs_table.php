<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Cấu hình tồn kho an toàn Min/Max (non-MVP - để sẵn). */
    public function up(): void
    {
        Schema::create('safe_stock_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sku_id');
            $table->unsignedBigInteger('warehouse_id')->nullable()->comment('NULL = áp dụng toàn hệ thống');
            $table->decimal('min_qty', 18, 2)->nullable();
            $table->decimal('max_qty', 18, 2)->nullable();
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->string('note', 500)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('sku_id')->references('id')->on('skus')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');

            $table->unique(['sku_id', 'warehouse_id', 'effective_from'], 'uq_safe_stock_scope');
            $table->index('sku_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('safe_stock_configs');
    }
};
