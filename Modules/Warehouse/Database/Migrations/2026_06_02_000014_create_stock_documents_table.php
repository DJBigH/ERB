<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phiếu kho hợp nhất (Stock Document) dùng chung cho Nhập / Xuất / Điều chuyển.
     */
    public function up(): void
    {
        Schema::create('stock_documents', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->tinyInteger('type')->comment('1: Nhập, 2: Xuất, 3: Điều chuyển');
            $table->tinyInteger('status')->default(1)
                ->comment('1: Nháp, 2: Chờ duyệt, 3: Đã duyệt, 4: Hoàn tất, 5: Đang vận chuyển, 8: Bị trả lại, 9: Đã hủy');
            $table->unsignedBigInteger('source_warehouse_id')->nullable()->comment('Kho nguồn (Xuất/Điều chuyển)');
            $table->unsignedBigInteger('dest_warehouse_id')->nullable()->comment('Kho đích (Nhập/Điều chuyển)');
            $table->string('note', 1000)->nullable();
            $table->string('reason', 500)->nullable()->comment('Lý do hủy / trả lại');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('performed_by')->nullable();
            $table->timestamp('performed_at')->nullable();
            $table->timestamps();

            $table->foreign('source_warehouse_id')->references('id')->on('warehouses')->nullOnDelete();
            $table->foreign('dest_warehouse_id')->references('id')->on('warehouses')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('performed_by')->references('id')->on('users')->nullOnDelete();

            $table->index(['type', 'status']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_documents');
    }
};
