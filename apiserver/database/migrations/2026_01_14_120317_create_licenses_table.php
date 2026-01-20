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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_asset_id')->nullable()->constrained('product_assets')->nullifyOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullifyOnDelete()->comment('NULL for guest licenses');
            $table->string('license_key', 50)->unique()->comment('Unique license identifier');
            $table->string('license_type')->index()->comment('FREE_SINGLE_USE, FREE_ATTRIBUTION, FREE_UNLIMITED, COMMERCIAL_SINGLE_USE, COMMERCIAL_3_USES, COMMERCIAL_10_USES, API_SUBSCRIPTION, DEMO');
            $table->string('email')->nullable()->index()->comment('Email associated with license');
            $table->json('usage_terms')->nullable()->comment('License restrictions and terms');
            $table->json('attribution_text')->nullable()->comment('Required attribution string');
            $table->integer('usage_count')->default(0)->comment('Times license has been used');
            $table->integer('max_usage')->nullable()->comment('Max allowed uses (NULL = unlimited)');
            $table->json('api_config')->nullable()->comment('For API subscriptions: rate limits, duration, etc');
            $table->timestamp('expires_at')->nullable()->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('first_used_at')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'is_active']);
            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
