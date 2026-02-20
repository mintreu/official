<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('licensed_sites', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('license_id')->constrained('licenses')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();
            $table->string('source_project', 120);
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->uuid('vendor_uuid')->nullable();
            $table->string('vendor_slug')->nullable();
            $table->string('vendor_name')->nullable();
            $table->unsignedBigInteger('site_id')->nullable();
            $table->uuid('site_uuid')->nullable();
            $table->string('site_slug');
            $table->string('site_name');
            $table->string('site_domain')->nullable();
            $table->string('site_license_key');
            $table->text('site_license_secret')->nullable();
            $table->string('status', 30)->default('active');
            $table->timestamp('provisioned_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['license_id', 'site_slug'], 'licensed_sites_license_site_slug_unique');
            $table->index(['user_id', 'source_project'], 'licensed_sites_user_project_idx');
            $table->index(['site_uuid', 'source_project'], 'licensed_sites_site_uuid_project_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('licensed_sites');
    }
};
