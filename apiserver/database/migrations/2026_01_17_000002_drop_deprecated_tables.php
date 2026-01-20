<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Drop deprecated tables that were replaced by simpler structure
 *
 * IMPORTANT: Run this AFTER data migration if you have existing data
 * This migration drops:
 * - product_meta (replaced by products.meta JSON)
 * - product_configs (replaced by product_sources)
 * - product_assets (replaced by product_sources)
 * - storage_providers (not needed)
 * - storage_credentials (merged into product_sources)
 */
return new class extends Migration
{
    public function up(): void
    {
        // Drop in correct order (foreign keys)

        // product_assets depends on product_configs
        Schema::dropIfExists('product_assets');

        // product_configs depends on storage_credentials
        Schema::dropIfExists('product_configs');

        // storage_credentials depends on storage_providers
        Schema::dropIfExists('storage_credentials');

        // Now drop independent tables
        Schema::dropIfExists('storage_providers');
        Schema::dropIfExists('product_meta');
    }

    public function down(): void
    {
        // Recreate tables if needed to rollback
        // Note: Data will be lost, this is for structure only

        Schema::create('storage_providers', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('display_name');
            $table->string('description')->nullable();
            $table->string('icon')->nullable();
            $table->json('config_schema')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('rate_limit')->nullable();
            $table->string('webhook_secret')->nullable();
            $table->timestamps();
        });

        Schema::create('storage_credentials', function ($table) {
            $table->id();
            $table->string('name');
            $table->foreignId('storage_provider_id')->constrained()->cascadeOnDelete();
            $table->text('encrypted_token')->nullable();
            $table->string('account_identifier')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('product_configs', function ($table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('storage_credential_id')->nullable()->constrained()->nullOnDelete();
            $table->string('source_type')->nullable();
            $table->string('source_identifier')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_private')->default(false);
            $table->timestamp('last_validated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('product_assets', function ($table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_config_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('resource_type')->nullable();
            $table->integer('download_limit')->nullable();
            $table->boolean('requires_auth')->default(false);
            $table->boolean('is_commercial_only')->default(false);
            $table->timestamps();
        });

        Schema::create('product_meta', function ($table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->json('value')->nullable();
            $table->timestamps();
        });
    }
};
