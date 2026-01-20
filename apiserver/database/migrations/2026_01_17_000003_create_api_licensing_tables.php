<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create API & Licensing System Tables
 *
 * Tables:
 * - plans: Pricing tiers for products
 * - api_endpoints: API routes for rate limiting
 * - api_keys: Customer API credentials
 * - api_usage_logs: Request tracking
 *
 * Updates:
 * - licenses: Add plan_id, server_info columns
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Plans table - pricing tiers for products
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('slug'); // unique within product
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('price_cents')->default(0);
            $table->string('billing_cycle')->default('monthly'); // monthly, yearly, lifetime, one_time
            $table->unsignedInteger('requests_per_month')->nullable(); // API rate limit
            $table->unsignedInteger('requests_per_day')->nullable();
            $table->unsignedInteger('requests_per_minute')->nullable(); // burst limit
            $table->json('features')->nullable(); // list of included features
            $table->json('limits')->nullable(); // additional limits (storage, seats, etc.)
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['product_id', 'slug']);
            $table->index(['product_id', 'is_active']);
        });

        // 2. API Endpoints - defines API routes for a product
        Schema::create('api_endpoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('method', 10); // GET, POST, PUT, DELETE
            $table->string('path'); // /users/{id}
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('base_url')->nullable(); // actual API host
            $table->unsignedTinyInteger('weight')->default(1); // request weight for rate limiting
            $table->boolean('is_public')->default(false); // accessible without API key
            $table->boolean('is_active')->default(true);
            $table->json('params')->nullable(); // expected parameters
            $table->json('response')->nullable(); // example response
            $table->timestamps();

            $table->index(['product_id', 'is_active']);
            $table->index(['product_id', 'method', 'path']);
        });

        // 3. API Keys - customer credentials for API access
        // Product has many ApiKeys (different customers)
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('license_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('key_hash', 64)->unique(); // SHA-256 hash
            $table->string('key_prefix', 20); // sk_live_xxxx... for display
            $table->string('name')->nullable(); // user-defined name
            $table->string('domain_restriction')->nullable(); // restrict to domain
            $table->json('ip_whitelist')->nullable(); // allowed IPs
            $table->unsignedInteger('requests_this_month')->default(0);
            $table->unsignedInteger('requests_today')->default(0);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['product_id', 'is_active']);
            $table->index(['user_id', 'is_active']);
            $table->index('key_hash');
        });

        // 4. API Usage Logs - request tracking
        Schema::create('api_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_key_id')->constrained()->cascadeOnDelete();
            $table->foreignId('api_endpoint_id')->nullable()->constrained()->nullOnDelete();
            $table->string('method', 10);
            $table->string('path');
            $table->unsignedSmallInteger('status_code');
            $table->unsignedInteger('response_time_ms')->default(0);
            $table->unsignedInteger('request_size')->default(0);
            $table->unsignedInteger('response_size')->default(0);
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->string('country', 2)->nullable();
            $table->json('request_headers')->nullable();
            $table->json('error_details')->nullable();
            $table->timestamp('created_at');

            $table->index(['api_key_id', 'created_at']);
            $table->index(['created_at']); // for cleanup
        });

        // 5. Update licenses table
        Schema::table('licenses', function (Blueprint $table) {
            if (! Schema::hasColumn('licenses', 'plan_id')) {
                $table->foreignId('plan_id')
                    ->nullable()
                    ->after('product_id')
                    ->constrained()
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('licenses', 'server_info')) {
                $table->json('server_info')->nullable()->after('meta');
            }
        });
    }

    public function down(): void
    {
        // Remove license columns first
        Schema::table('licenses', function (Blueprint $table) {
            if (Schema::hasColumn('licenses', 'plan_id')) {
                $table->dropForeign(['plan_id']);
                $table->dropColumn('plan_id');
            }
            if (Schema::hasColumn('licenses', 'server_info')) {
                $table->dropColumn('server_info');
            }
        });

        Schema::dropIfExists('api_usage_logs');
        Schema::dropIfExists('api_keys');
        Schema::dropIfExists('api_endpoints');
        Schema::dropIfExists('plans');
    }
};
