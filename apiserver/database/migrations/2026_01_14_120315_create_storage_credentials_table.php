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
        Schema::create('storage_credentials', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index()->comment('Display name for admin');
            $table->unsignedBigInteger('storage_provider_id');
            $table->text('encrypted_token')->comment('Encrypted API token/key');
            $table->string('account_identifier')->comment('Account name or ID');
            $table->json('metadata')->nullable()->comment('Additional provider-specific data');
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('last_verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_credentials');
    }
};
