<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('escrow_holds', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('vendor_id')->constrained('users')->restrictOnDelete();
            $table->decimal('amount', 12, 2);
            $table->timestamp('release_at')->index();
            $table->timestamp('released_at')->nullable();
            $table->string('status')->default('held')->index();
            $table->timestamps();

            $table->index(['vendor_id', 'status', 'release_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('escrow_holds');
    }
};
