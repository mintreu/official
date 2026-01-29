<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Fix download_logs.license_id to be nullable
 *
 * Freebies and free products don't require a license,
 * so we need to allow null values for license_id
 */
return new class extends Migration
{
    public function up(): void
    {
        // Drop the foreign key first
        Schema::table('download_logs', function (Blueprint $table) {
            $table->dropForeign(['license_id']);
        });

        // Modify column to be nullable
        Schema::table('download_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('license_id')->nullable()->change();
        });

        // Re-add foreign key with nullOnDelete
        Schema::table('download_logs', function (Blueprint $table) {
            $table->foreign('license_id')
                ->references('id')
                ->on('licenses')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        // Remove existing null values before making non-nullable
        DB::table('download_logs')->whereNull('license_id')->delete();

        Schema::table('download_logs', function (Blueprint $table) {
            $table->dropForeign(['license_id']);
        });

        Schema::table('download_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('license_id')->nullable(false)->change();
        });

        Schema::table('download_logs', function (Blueprint $table) {
            $table->foreign('license_id')
                ->references('id')
                ->on('licenses')
                ->cascadeOnDelete();
        });
    }
};
