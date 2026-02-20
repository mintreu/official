<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('license_id')->nullable()->constrained('licenses')->nullOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('review')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->unique(['user_id', 'product_id']);
            $table->index(['product_id', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
