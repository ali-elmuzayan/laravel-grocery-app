<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('sku')->unique();
            $table->string('name');
            $table->decimal('price', 12, 2);
            $table->integer('stock')->default(0);
            $table->timestamps();

            $table->index(['product_id', 'stock']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
