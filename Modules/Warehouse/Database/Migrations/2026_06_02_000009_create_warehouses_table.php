<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Reflects the existing `warehouses` table (created outside migrations).
     * Guarded with hasTable so it is a no-op on databases that already have it.
     */
    public function up(): void
    {
        if (Schema::hasTable('warehouses')) {
            return;
        }

        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable()
                ->comment('Kho cha (kho tổng) - hỗ trợ kho đa tầng');
            $table->string('code', 225)->unique();
            $table->string('name', 225);
            $table->tinyInteger('warehouse_type')->default(2)->comment('1: Kho tổng, 2: Kho con');
            $table->string('address', 225)->nullable();
            $table->unsignedBigInteger('manager_id')->nullable()->comment('Người phụ trách - users.id');
            $table->tinyInteger('status')->default(1)->comment('0: Inactive, 1: Active');
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            $table->foreign('parent_id')->references('id')->on('warehouses')->nullOnDelete();
            $table->foreign('manager_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
