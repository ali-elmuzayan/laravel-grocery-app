<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_installments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sequence');
            $table->decimal('amount', 12, 2);
            $table->timestamp('due_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('status')->default('pending')->index();
            $table->timestamps();

            $table->unique(['order_id', 'sequence']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_installments');
    }
};
