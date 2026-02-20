<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saas_site_stat_events', function (Blueprint $table) {
            $table->id();
            $table->string('source_project', 80)->default('unknown');
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('site_id')->nullable();
            $table->uuid('site_uuid')->nullable();
            $table->string('site_slug')->nullable();
            $table->timestamp('window_start')->nullable();
            $table->timestamp('window_end')->nullable();
            $table->json('metrics');
            $table->json('payload')->nullable();
            $table->timestamp('received_at');
            $table->timestamps();

            $table->index(['source_project', 'received_at'], 'saas_stats_source_received_idx');
            $table->index(['vendor_id', 'received_at'], 'saas_stats_vendor_received_idx');
            $table->index(['site_uuid', 'received_at'], 'saas_stats_site_uuid_received_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saas_site_stat_events');
    }
};
