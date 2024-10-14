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

            $table->text('short_desc')->nullable();
            $table->text('desc')->nullable();

            $table->boolean('status')->default(false);

            $table->boolean('chargeable')->default(false);

            // Purchase Charges No Api Subscription
            $table->float('price', 10, 2, true)->default(0.00);
            $table->string('tax_type')->default('none');


            $table->integer('popularity')->default(0);
            $table->unsignedBigInteger('views')->default(0);
            $table->boolean('featured')->default(false);
            $table->boolean('visibility')->default(false);



            $table->string('host_url')->nullable();
            $table->string('host_api_url')->nullable();
            $table->string('client_login_url')->nullable();

            $table->json('demo_accounts')->nullable();
            $table->json('product_info')->nullable();
            $table->json('metadata')->nullable();

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
