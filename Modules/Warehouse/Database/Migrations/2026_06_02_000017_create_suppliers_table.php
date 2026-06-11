<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng suppliers đã tồn tại sẵn trong hệ thống (tạo ngoài module).
     * Migration phản ánh đúng schema thật + guard hasTable để no-op trên DB đã có,
     * vẫn tạo được trên môi trường fresh.
     */
    public function up(): void
    {
        if (Schema::hasTable('suppliers')) {
            return;
        }

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 225)->unique();
            $table->string('name', 225);
            $table->string('phone', 20)->nullable();
            $table->string('address', 225)->nullable();
            $table->tinyInteger('status')->default(1)->comment('0: Inactive, 1: Active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
