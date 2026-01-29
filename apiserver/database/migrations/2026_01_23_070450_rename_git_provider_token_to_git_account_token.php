<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Rename git_provider_tokens to git_account_tokens for clarity
 * Rename foreign key column in product_sources
 */
return new class extends Migration
{
    public function up(): void
    {
        // Rename the table
        Schema::rename('git_provider_tokens', 'git_account_tokens');

        // Rename foreign key column in product_sources
        Schema::table('product_sources', function (Blueprint $table) {
            $table->dropForeign(['git_provider_token_id']);
        });

        Schema::table('product_sources', function (Blueprint $table) {
            $table->renameColumn('git_provider_token_id', 'git_account_token_id');
        });

        Schema::table('product_sources', function (Blueprint $table) {
            $table->foreign('git_account_token_id')
                ->references('id')
                ->on('git_account_tokens')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        // Rename back
        Schema::table('product_sources', function (Blueprint $table) {
            $table->dropForeign(['git_account_token_id']);
        });

        Schema::table('product_sources', function (Blueprint $table) {
            $table->renameColumn('git_account_token_id', 'git_provider_token_id');
        });

        Schema::rename('git_account_tokens', 'git_provider_tokens');

        Schema::table('product_sources', function (Blueprint $table) {
            $table->foreign('git_provider_token_id')
                ->references('id')
                ->on('git_provider_tokens')
                ->nullOnDelete();
        });
    }
};
