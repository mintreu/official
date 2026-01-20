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
        Schema::table('storage_credentials', function (Blueprint $table) {
            $table->foreign('storage_provider_id')
                ->references('id')
                ->on('storage_providers')
                ->cascadeOnDelete();
        });

        Schema::table('product_configs', function (Blueprint $table) {
            $table->foreign('storage_credential_id')
                ->references('id')
                ->on('storage_credentials')
                ->nullifyOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('product_configs', function (Blueprint $table) {
            $table->dropForeign(['storage_credential_id']);
        });

        Schema::table('storage_credentials', function (Blueprint $table) {
            $table->dropForeign(['storage_provider_id']);
        });
    }
};
