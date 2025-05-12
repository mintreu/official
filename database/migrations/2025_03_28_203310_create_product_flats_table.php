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
        Schema::create('product_flats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnUpdate()->cascadeOnDelete();

            $table->text('short_desc')->nullable();
            $table->text('desc')->nullable();

            $table->string('host_url')->nullable();
            $table->string('host_api_url')->nullable();
            $table->string('client_login_url')->nullable();

            $table->json('demo_accounts')->nullable();
            $table->json('product_info')->nullable();
            $table->json('metadata')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_flats');
    }
};
