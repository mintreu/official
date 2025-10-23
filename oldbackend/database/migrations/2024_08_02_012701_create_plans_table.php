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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('url')->unique();
            $table->text('desc')->nullable();

            // Subscription Charge For Using API
            // Pricing***********************************************
            $table->float('base_price', 10, 2, true)->default(0.00);
            // Tax info
            $table->string('hsn_code')->nullable();
            $table->float('tax_percent', 4, 2, true)->default(0.00);
            $table->float('tax_amount', 10, 2)->default(0.00);
            // Final Price
            $table->float('price', 10, 2, true)->default(0.00);

            // Plan details
            $table->integer('per_month_limit')->nullable();
            $table->string('auth_type')->nullable();
            $table->string('support_type')->nullable();
            $table->string('documentation_type')->nullable();

            // New features for future-proofing
            $table->json('features')->nullable();
            $table->boolean('is_recommended')->default(false);
            $table->boolean('is_enterprise')->default(false);
            $table->boolean('visible_on_front')->default(false);

            // Foreign Id
            $table->foreignId('product_id')->constrained('products')->cascadeOnUpdate()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};

