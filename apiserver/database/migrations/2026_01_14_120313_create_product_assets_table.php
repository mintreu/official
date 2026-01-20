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
        Schema::create('product_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_config_id')->constrained()->cascadeOnDelete();
            $table->string('name')->comment('Resource name: Source Code, Binary, Demo, etc');
            $table->text('description')->nullable();
            $table->string('resource_type')->default('MAIN')->comment('MAIN, DOCUMENTATION, DEMO, MEDIA, SOURCE');
            $table->integer('download_limit')->nullable()->comment('NULL = unlimited, 0 = no download, 5 = max 5 times');
            $table->boolean('requires_auth')->default(false)->comment('Must be logged in to download');
            $table->boolean('is_commercial_only')->default(false)->comment('Only available with commercial license');
            $table->timestamps();

            $table->index(['product_id', 'resource_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_assets');
    }
};
