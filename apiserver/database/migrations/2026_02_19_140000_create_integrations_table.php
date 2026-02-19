<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type');
            $table->text('credentials')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('is_sandbox')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamp('last_tested_at')->nullable();
            $table->string('last_test_result')->nullable();
            $table->text('last_test_message')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('type');
            $table->index(['type', 'is_active', 'is_default']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
};

