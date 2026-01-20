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
        Schema::create('product_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('storage_credential_id')->nullable();
            $table->string('source_type')->default('LOCAL_STORAGE')->comment('GIT_REPO, LOCAL_STORAGE, GOOGLE_DRIVE, AWS_S3, DROPBOX, EXTERNAL_URL');
            $table->string('source_identifier')->comment('Repo path, folder ID, URL, etc');
            $table->json('metadata')->nullable()->comment('Provider-specific config');
            $table->boolean('is_primary')->default(true)->comment('Primary download source');
            $table->boolean('is_private')->default(false)->comment('Requires credentials to access');
            $table->timestamp('last_validated_at')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'is_primary']);
            $table->index('source_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_configs');
    }
};
