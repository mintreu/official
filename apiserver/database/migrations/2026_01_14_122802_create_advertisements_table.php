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
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Display name for admin');
            $table->string('placement')->index()->comment('ads_top, ads_sidebar, ads_bottom, ads_insights');
            $table->text('html_code')->comment('Google AdSense or custom ad HTML/script');
            $table->json('allowed_pages')->nullable()->comment('Specific pages to show ad (null = all)');
            $table->integer('priority')->default(0)->comment('Display priority (higher = show first)');
            $table->integer('impressions')->default(0)->comment('Total times shown');
            $table->integer('clicks')->default(0)->comment('Total clicks (if trackable)');
            $table->integer('unique_ips')->default(0)->comment('Unique IP addresses that saw ad');
            $table->json('viewed_ips')->nullable()->comment('Store recent IPs to prevent duplicate impressions');
            $table->integer('max_impressions_per_ip')->default(3)->comment('Max impressions per unique IP per day');
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();

            $table->index(['placement', 'is_active', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
