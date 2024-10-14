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
        Schema::create('studios', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url')->unique();

            // Client/User Domain Url
            $table->string('domain_schema')->default('http://');
            $table->string('domain');

            // Subscription
            $table->dateTime('expire_on')->nullable();
            $table->string('serial_key')->nullable();

            // Foreign Key
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();           // Customer
            $table->foreignId('product_id')->constrained('products')->cascadeOnUpdate()->cascadeOnDelete();     // Product
            $table->foreignId('plan_id')->constrained('plans')->cascadeOnUpdate()->cascadeOnDelete();           // Selected Plan
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->cascadeOnUpdate()->nullOnDelete();       // Selected Studio

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studios');
    }
};
