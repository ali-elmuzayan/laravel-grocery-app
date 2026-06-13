<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('wallet_account_id')->constrained()->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->string('type')->index();
            $table->decimal('amount', 14, 2);
            $table->string('status')->default('posted')->index();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['wallet_account_id', 'created_at', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
