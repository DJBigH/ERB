<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('code', 50)->unique();
            $table->string('name', 255);
            $table->tinyInteger('depth')->default(0)->comment('Cấp trong cây danh mục');
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1)->comment('0: Inactive, 1: Active');
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('product_categories')->nullOnDelete();
            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
