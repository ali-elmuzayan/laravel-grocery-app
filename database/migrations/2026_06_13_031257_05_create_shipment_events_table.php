<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipment_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();

            $table->index(['shipment_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_events');
    }
};
