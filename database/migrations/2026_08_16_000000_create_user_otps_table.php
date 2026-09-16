<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_otps', function (Blueprint $table): void {
            $table->id();
            $table->string('code');
            $table->enum('type', ['forgot_password', 'verify_email']);
            $table->timestamp('expires_at');
            $table->timestamp('verified_at')->nullable();
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamps();

            // Relationships: 
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Indexes: 
            $table->index(['user_id', 'type', 'verified_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_otps');
    }
};
