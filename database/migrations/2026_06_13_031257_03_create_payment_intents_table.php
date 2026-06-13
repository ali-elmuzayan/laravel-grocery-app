<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_intents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('idempotency_key')->unique();
            $table->string('provider_reference')->nullable()->unique();
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('pending')->index();
            $table->timestamp('captured_at')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_intents');
    }
};
