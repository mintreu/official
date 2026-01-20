<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('download_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_asset_id')->constrained('product_assets')->cascadeOnDelete();
            $table->foreignId('license_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullifyOnDelete();
            $table->string('ip_address')->index();
            $table->string('user_agent')->nullable();
            $table->string('status')->default('completed')->comment('pending, completed, failed');
            $table->string('download_token')->unique()->nullable()->comment('For resuming downloads');
            $table->bigInteger('file_size')->nullable()->comment('Size in bytes');
            $table->string('checksum')->nullable()->comment('SHA256 hash');
            $table->timestamp('downloaded_at')->index();
            $table->timestamps();

            $table->index(['product_id', 'downloaded_at']);
            $table->index(['license_id', 'downloaded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('download_logs');
    }
};
