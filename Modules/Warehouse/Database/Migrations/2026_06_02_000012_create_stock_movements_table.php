<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();

            // Tham chieu mem den Phieu kho (nam ngoai pham vi 5 bang nay -> khong dat FK)
            $table->string('source_document_type', 50)->nullable();
            $table->unsignedBigInteger('source_document_id')->nullable();

            $table->tinyInteger('movement_type')
                ->comment('1: Nhap, 2: Xuat, 3: Dieu chuyen xuat, 4: Dieu chuyen nhap, 5: Dieu chinh');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('sku_id');
            $table->decimal('quantity', 18, 2)->comment('Co dau +/- theo nghiep vu');
            $table->decimal('qty_before', 18, 2)->nullable();
            $table->decimal('qty_after', 18, 2)->nullable();
            $table->unsignedBigInteger('performed_by');
            $table->string('idempotency_key', 100)->nullable()->unique();

            // Hoan tac bien dong (reversal)
            $table->boolean('is_reversed')->default(false);
            $table->unsignedBigInteger('reversed_by_movement_id')->nullable();
            $table->string('reversal_reason', 255)->nullable();
            $table->string('reversal_type', 50)->nullable();

            $table->timestamps();

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('sku_id')->references('id')->on('skus')->onDelete('cascade');
            $table->foreign('performed_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reversed_by_movement_id')->references('id')->on('stock_movements')->nullOnDelete();

            $table->index(['warehouse_id', 'sku_id']);
            $table->index(['source_document_type', 'source_document_id']);
            $table->index('movement_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
