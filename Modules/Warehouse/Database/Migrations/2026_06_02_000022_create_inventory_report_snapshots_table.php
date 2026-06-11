<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Snapshot tồn kho cuối kỳ - tối ưu tính tồn đầu kỳ cho báo cáo (job dựng sau). */
    public function up(): void
    {
        Schema::create('inventory_report_snapshots', function (Blueprint $table) {
            $table->id();
            $table->date('snapshot_date');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('sku_id');
            $table->decimal('available_qty', 18, 2)->default(0);
            $table->decimal('in_transit_qty', 18, 2)->default(0);
            $table->decimal('reserved_qty', 18, 2)->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('sku_id')->references('id')->on('skus')->onDelete('cascade');

            $table->unique(['snapshot_date', 'warehouse_id', 'sku_id'], 'uq_snapshot');
            $table->index(['warehouse_id', 'snapshot_date']);
            $table->index(['sku_id', 'snapshot_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_report_snapshots');
    }
};
