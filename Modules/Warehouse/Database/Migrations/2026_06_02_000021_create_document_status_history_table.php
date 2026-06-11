<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Lịch sử chuyển trạng thái chứng từ (append-only). */
    public function up(): void
    {
        Schema::create('document_status_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_document_id');
            $table->tinyInteger('from_status')->nullable();
            $table->tinyInteger('to_status');
            $table->unsignedBigInteger('changed_by');
            $table->timestamp('changed_at')->useCurrent();
            $table->string('note', 1000)->nullable();

            $table->foreign('stock_document_id')->references('id')->on('stock_documents')->onDelete('cascade');
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('cascade');

            $table->index('stock_document_id');
            $table->index('changed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_status_history');
    }
};
