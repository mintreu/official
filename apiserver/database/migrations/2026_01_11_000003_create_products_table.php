<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('price')->default(0);
            $table->string('category')->nullable();
            $table->string('type')->nullable();
            $table->string('download_url')->nullable();
            $table->string('demo_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('documentation_url')->nullable();
            $table->string('version')->nullable();
            $table->unsignedInteger('downloads')->default(0);
            $table->decimal('rating', 3, 1)->default(0);
            $table->string('status')->default('draft');
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
