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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('name');
            $table->string('url')->unique();
            $table->string('version')->nullable();
            $table->text('short_desc')->nullable();


            $table->string('status')->nullable();
            $table->text('status_feedback')->nullable();

            $table->boolean('chargeable')->default(false);



            $table->integer('popularity')->default(0);
            $table->unsignedBigInteger('views')->default(0);
            $table->boolean('featured')->default(false);
            $table->boolean('visibility')->default(false);



            $table->foreignId('parent_id')->nullable()->constrained('products')->cascadeOnUpdate()->nullOnDelete();

            $table->foreignId('project_id')->nullable()->constrained('projects')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->cascadeOnUpdate()->nullOnDelete();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
