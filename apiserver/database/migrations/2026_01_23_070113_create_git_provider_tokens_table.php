<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Git Provider Tokens - Multi-account token storage
 *
 * Allows storing multiple Git provider tokens (GitHub, GitLab, Bitbucket)
 * for different accounts. Tokens are encrypted at rest.
 *
 * Usage:
 * - Each ProductSource can reference a specific token via git_provider_token_id
 * - Falls back to config token if no specific token assigned
 * - Supports multiple GitHub accounts (different orgs/users)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('git_provider_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Display name (e.g., "Mintreu Org", "Personal Account")
            $table->string('provider'); // github, gitlab, bitbucket
            $table->string('account_identifier')->nullable(); // GitHub username/org for reference
            $table->text('encrypted_token'); // Encrypted PAT
            $table->json('scopes')->nullable(); // Token scopes/permissions for reference
            $table->text('notes')->nullable(); // Admin notes
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable(); // Token expiration (if known)
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('last_verified_at')->nullable(); // Last time token was verified working
            $table->timestamps();

            $table->index(['provider', 'is_active']);
            $table->index('account_identifier');
        });

        // Add foreign key to product_sources
        Schema::table('product_sources', function (Blueprint $table) {
            $table->foreignId('git_provider_token_id')
                ->nullable()
                ->after('encrypted_token')
                ->constrained('git_provider_tokens')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('product_sources', function (Blueprint $table) {
            $table->dropForeign(['git_provider_token_id']);
            $table->dropColumn('git_provider_token_id');
        });

        Schema::dropIfExists('git_provider_tokens');
    }
};
