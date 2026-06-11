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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('action', 20)->comment('CREATE / UPDATE / DELETE / APPROVE / POST');
            $table->string('object_type', 50)->comment('Phieu / Kho / SKU');
            // Tham chieu mem den doi tuong bi tac dong (khong dat FK)
            $table->unsignedBigInteger('object_id')->nullable();
            $table->json('detail')->nullable()->comment('Payload thay doi');
            $table->string('ip_address', 45)->nullable();
            // Append-only: chi luu created_at, khong co updated_at
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->index('user_id');
            $table->index(['object_type', 'object_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
