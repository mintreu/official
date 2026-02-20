<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_spaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('api_key_id')->constrained('api_keys')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('name');
            $table->string('website');
            $table->string('environment')->default('prod');
            $table->string('status')->default('active');
            $table->unsignedInteger('requests_this_month')->default(0);
            $table->unsignedInteger('requests_today')->default(0);
            $table->timestamp('last_request_at')->nullable();
            $table->json('config')->nullable();
            $table->json('insights')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'api_key_id']);
            $table->index(['user_id', 'updated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_spaces');
    }
};

