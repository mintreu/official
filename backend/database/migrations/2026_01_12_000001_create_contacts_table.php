<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('project_type')->nullable();
            $table->string('budget')->nullable();
            $table->text('message');
            $table->enum('status', ['new', 'in_progress', 'replied', 'archived'])->default('new');
            $table->timestamp('replied_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
