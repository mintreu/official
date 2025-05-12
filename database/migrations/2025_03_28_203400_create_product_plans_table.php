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
        Schema::create('product_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnUpdate()->cascadeOnDelete();

            $table->string('name');
            $table->text('desc')->nullable();

            // Final Price
            $table->float('price', 10, 2, true)->default(0.00);
            // Plan details
            $table->integer('per_month_limit')->nullable();
            $table->integer('records_limit')->nullable();
            $table->integer('storage_limit')->nullable();

            $table->boolean('is_recommended')->default(false);
            $table->boolean('is_enterprise')->default(false);
            $table->boolean('visible_on_front')->default(false);

            $table->boolean('has_support')->default(false);
            $table->string('support_level')->nullable()->default(\App\Models\Enums\Product\ProductSupportLevelCast::BASIC->value);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_plans');
    }
};
