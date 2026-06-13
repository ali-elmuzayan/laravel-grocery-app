<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payout_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('vendor_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('pending')->index();
            $table->text('note')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['vendor_id', 'status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payout_requests');
    }
};
