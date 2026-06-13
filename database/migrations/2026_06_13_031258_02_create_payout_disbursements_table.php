<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payout_disbursements', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('payout_request_id')->constrained()->cascadeOnDelete();
            $table->string('provider_reference')->nullable()->unique();
            $table->string('status')->default('pending')->index();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payout_disbursements');
    }
};
