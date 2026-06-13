<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('vendor_id')->constrained('users')->restrictOnDelete();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('delivery_fee', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->string('payment_status')->default('pending')->index();
            $table->string('status')->default('pending')->index();
            $table->timestamp('placed_at')->nullable()->index();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['vendor_id', 'status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
