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
        Schema::create('storage_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->index()->comment('GITHUB, BITBUCKET, GOOGLE_DRIVE, AWS_S3, DROPBOX, etc');
            $table->string('display_name')->comment('Display name in admin');
            $table->text('description')->nullable();
            $table->string('icon')->nullable()->comment('Icon class or URL');
            $table->json('config_schema')->comment('JSON schema for required fields');
            $table->boolean('is_active')->default(true)->index();
            $table->integer('rate_limit')->nullable()->comment('API rate limit per hour');
            $table->string('webhook_secret')->nullable()->comment('Secret for validating webhooks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_providers');
    }
};
