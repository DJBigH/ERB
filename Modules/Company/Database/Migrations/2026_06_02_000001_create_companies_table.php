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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 225);
            $table->string('code', 225)->unique();
            $table->string('tax_code', 225)->unique();
            $table->string('phone', 20);
            $table->string('email', 100)->unique();
            $table->string('address', 225)->nullable();
            $table->tinyInteger('status')->default(1)->comment('0: Inactive, 1:Active');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('Nếu null = công ty mẹ');
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
