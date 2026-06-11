<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Dedup toàn cục cho API nghiệp vụ (để sẵn) - cleanup sau 24h. */
    public function up(): void
    {
        Schema::create('idempotency_keys', function (Blueprint $table) {
            $table->id();
            $table->string('idem_key', 100);
            $table->string('operation', 80)->comment('VD: approve_document | post_receipt ...');
            $table->tinyInteger('status')->default(1)->comment('1: processing, 2: done, 3: failed');
            $table->smallInteger('response_code')->nullable();
            $table->json('response_body')->nullable();
            $table->timestamp('locked_until')->nullable()->comment('Hết hạn lock chống duplicate inflight');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['idem_key', 'operation']);
            $table->index('locked_until');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('idempotency_keys');
    }
};
