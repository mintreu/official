<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Refactor Product Schema - Simplify over-engineered structure
 *
 * REMOVES:
 * - product_meta (replaced by JSON column)
 * - product_configs (replaced by product_sources)
 * - product_assets (merged into product_sources)
 * - storage_providers (not needed)
 * - storage_credentials (merged into product_sources)
 *
 * ADDS:
 * - product_sources (single table for all download sources)
 * - external_api_credentials (for API products)
 *
 * UPDATES:
 * - products (adds JSON columns, removes old columns)
 * - licenses (removes product_asset_id, adds api_credential_id)
 * - download_logs (uses product_source_id instead of product_asset_id)
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Create product_sources table (replaces 4 tables)
        Schema::create('product_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('provider'); // SourceProvider enum value
            $table->string('name'); // Display name (e.g., "Windows x64", "Linux ARM")
            $table->string('description')->nullable();
            $table->text('source_url'); // Real download URL (private)
            $table->text('encrypted_token')->nullable(); // Auth token for private repos
            $table->string('version')->nullable(); // Version tag
            $table->string('file_name')->nullable(); // Expected filename
            $table->unsignedBigInteger('file_size')->nullable(); // File size in bytes
            $table->json('metadata')->nullable(); // Provider-specific data
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_verified_at')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'is_active']);
            $table->index(['product_id', 'is_primary']);
        });

        // 2. Create external_api_credentials table (for API products)
        Schema::create('external_api_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('encrypted_api_key')->nullable();
            $table->text('encrypted_api_secret')->nullable();
            $table->string('external_user_id')->nullable(); // User ID on external platform
            $table->string('external_account_url')->nullable(); // Link to external account
            $table->string('plan_id')->nullable(); // External plan ID
            $table->json('rate_limits')->nullable(); // Rate limit info
            $table->json('usage_stats')->nullable(); // Usage statistics
            $table->json('meta')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'user_id']);
            $table->index(['user_id', 'is_active']);
        });

        // 3. Update products table
        Schema::table('products', function (Blueprint $table) {
            // Add new columns
            if (! Schema::hasColumn('products', 'short_description')) {
                $table->string('short_description', 160)->nullable()->after('title');
            }

            if (! Schema::hasColumn('products', 'meta')) {
                $table->json('meta')->nullable()->after('default_license_type');
            }

            // plans column NOT needed - we use plans table instead

            if (! Schema::hasColumn('products', 'api_config')) {
                $table->json('api_config')->nullable()->after('meta');
            }

            // Rename columns to match new naming
            if (Schema::hasColumn('products', 'requires_account')) {
                $table->renameColumn('requires_account', 'requires_auth');
            }

            if (Schema::hasColumn('products', 'default_license_type')) {
                $table->renameColumn('default_license_type', 'default_license');
            }

            if (Schema::hasColumn('products', 'is_payable')) {
                $table->dropColumn('is_payable'); // Determined by type/price now
            }

            // download_url moved to product_sources
            if (Schema::hasColumn('products', 'download_url')) {
                $table->dropColumn('download_url');
            }
        });

        // 4. Update licenses table
        Schema::table('licenses', function (Blueprint $table) {
            // Remove product_asset_id (no longer exists)
            if (Schema::hasColumn('licenses', 'product_asset_id')) {
                $table->dropForeign(['product_asset_id']);
                $table->dropColumn('product_asset_id');
            }

            // Rename license_type to type
            if (Schema::hasColumn('licenses', 'license_type')) {
                $table->renameColumn('license_type', 'type');
            }

            // Add api_credential_id for API products
            if (! Schema::hasColumn('licenses', 'api_credential_id')) {
                $table->foreignId('api_credential_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('external_api_credentials')
                    ->nullOnDelete();
            }

            // Rename api_config/usage_terms/attribution_text to meta
            if (Schema::hasColumn('licenses', 'api_config')) {
                $table->dropColumn('api_config');
            }
            if (Schema::hasColumn('licenses', 'usage_terms')) {
                $table->dropColumn('usage_terms');
            }
            if (Schema::hasColumn('licenses', 'attribution_text')) {
                $table->dropColumn('attribution_text');
            }

            if (! Schema::hasColumn('licenses', 'meta')) {
                $table->json('meta')->nullable()->after('max_usage');
            }
        });

        // 5. Update download_logs table
        Schema::table('download_logs', function (Blueprint $table) {
            // Replace product_asset_id with product_source_id
            if (Schema::hasColumn('download_logs', 'product_asset_id')) {
                $table->dropForeign(['product_asset_id']);
                $table->dropColumn('product_asset_id');
            }

            if (! Schema::hasColumn('download_logs', 'product_source_id')) {
                $table->foreignId('product_source_id')
                    ->nullable()
                    ->after('product_id')
                    ->constrained()
                    ->nullOnDelete();
            }

            // Remove checksum (not used)
            if (Schema::hasColumn('download_logs', 'checksum')) {
                $table->dropColumn('checksum');
            }
        });
    }

    public function down(): void
    {
        // Reverse download_logs changes
        Schema::table('download_logs', function (Blueprint $table) {
            if (Schema::hasColumn('download_logs', 'product_source_id')) {
                $table->dropForeign(['product_source_id']);
                $table->dropColumn('product_source_id');
            }

            if (! Schema::hasColumn('download_logs', 'product_asset_id')) {
                $table->foreignId('product_asset_id')->nullable()->after('product_id');
            }

            if (! Schema::hasColumn('download_logs', 'checksum')) {
                $table->string('checksum')->nullable();
            }
        });

        // Reverse licenses changes
        Schema::table('licenses', function (Blueprint $table) {
            if (Schema::hasColumn('licenses', 'api_credential_id')) {
                $table->dropForeign(['api_credential_id']);
                $table->dropColumn('api_credential_id');
            }

            if (Schema::hasColumn('licenses', 'type')) {
                $table->renameColumn('type', 'license_type');
            }

            if (! Schema::hasColumn('licenses', 'product_asset_id')) {
                $table->foreignId('product_asset_id')->nullable();
            }

            if (Schema::hasColumn('licenses', 'meta')) {
                $table->dropColumn('meta');
            }

            if (! Schema::hasColumn('licenses', 'api_config')) {
                $table->json('api_config')->nullable();
            }
            if (! Schema::hasColumn('licenses', 'usage_terms')) {
                $table->json('usage_terms')->nullable();
            }
            if (! Schema::hasColumn('licenses', 'attribution_text')) {
                $table->json('attribution_text')->nullable();
            }
        });

        // Reverse products changes
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'short_description')) {
                $table->dropColumn('short_description');
            }
            if (Schema::hasColumn('products', 'meta')) {
                $table->dropColumn('meta');
            }
            if (Schema::hasColumn('products', 'plans')) {
                $table->dropColumn('plans');
            }
            if (Schema::hasColumn('products', 'api_config')) {
                $table->dropColumn('api_config');
            }

            if (Schema::hasColumn('products', 'requires_auth')) {
                $table->renameColumn('requires_auth', 'requires_account');
            }
            if (Schema::hasColumn('products', 'default_license')) {
                $table->renameColumn('default_license', 'default_license_type');
            }

            if (! Schema::hasColumn('products', 'is_payable')) {
                $table->boolean('is_payable')->default(false);
            }
            if (! Schema::hasColumn('products', 'download_url')) {
                $table->string('download_url')->nullable();
            }
        });

        Schema::dropIfExists('external_api_credentials');
        Schema::dropIfExists('product_sources');
    }
};
