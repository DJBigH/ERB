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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name', 225);
            $table->string('code', 225)->unique();
            $table->string('tax_code', 225)->nullable()->unique();
            $table->string('phone', 225);
            $table->string('email', 225)->unique();
            $table->string('address', 225)->nullable();
            $table->tinyInteger('status')->default(1)->comment('0: Inactive, 1:Active');
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
