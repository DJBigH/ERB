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
        Schema::create('inventory_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('sku_id');
            $table->decimal('available_qty', 18, 2)->default(0)->comment('Ton kha dung (>=0)');
            $table->decimal('in_transit_qty', 18, 2)->default(0)->comment('Dang van chuyen (>=0)');
            $table->decimal('reserved_qty', 18, 2)->default(0)->comment('Dat giu (>=0)');
            $table->timestamps();

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('sku_id')->references('id')->on('skus')->onDelete('cascade');

            // Moi cap (kho, SKU) chi co mot dong ton kho
            $table->unique(['warehouse_id', 'sku_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_balances');
    }
};
