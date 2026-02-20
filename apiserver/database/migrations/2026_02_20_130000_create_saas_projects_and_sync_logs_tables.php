<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saas_projects', function (Blueprint $table): void {
            $table->id();
            $table->string('slug', 120)->unique();
            $table->string('name', 160);
            $table->string('environment', 40)->default('production');
            $table->string('base_url')->nullable();
            $table->string('internal_key')->nullable();
            $table->text('internal_secret')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_heartbeat_at')->nullable();
            $table->json('last_machine_info')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'environment'], 'saas_projects_active_env_idx');
        });

        Schema::create('saas_sync_logs', function (Blueprint $table): void {
            $table->id();
            $table->string('project_slug', 120);
            $table->string('direction', 20); // inbound|outbound
            $table->string('action', 120);
            $table->string('status', 40); // success|failed|skipped
            $table->unsignedSmallInteger('http_status')->nullable();
            $table->string('target_url')->nullable();
            $table->text('message')->nullable();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->timestamp('executed_at');
            $table->timestamps();

            $table->index(['project_slug', 'executed_at'], 'saas_sync_logs_project_time_idx');
            $table->index(['status', 'executed_at'], 'saas_sync_logs_status_time_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saas_sync_logs');
        Schema::dropIfExists('saas_projects');
    }
};
