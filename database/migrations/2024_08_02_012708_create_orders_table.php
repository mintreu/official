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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->integer('amount');
            $table->integer('subtotal'); // subtotal  base_price
            $table->integer('discount'); // subtotal discount
            $table->integer('tax'); // subtotal tax
            $table->integer('total'); // subtotal final price
            $table->integer('quantity'); // total quantity
            $table->string('voucher')->nullable();
            $table->string('tracking_id')->nullable(); // can be deleted

            $table->string('status')->default('pending');
            $table->boolean('payment_success')->default(false);
            $table->dateTime('expire_at');

            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained('plans')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnUpdate()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
