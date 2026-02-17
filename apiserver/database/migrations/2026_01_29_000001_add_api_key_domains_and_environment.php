<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('api_keys', function (Blueprint $table) {
            if (! Schema::hasColumn('api_keys', 'allowed_domains')) {
                $table->json('allowed_domains')->nullable()->after('domain_restriction');
            }

            if (! Schema::hasColumn('api_keys', 'environment')) {
                $table->string('environment')->default('prod')->after('allowed_domains');
            }
        });
    }

    public function down(): void
    {
        Schema::table('api_keys', function (Blueprint $table) {
            if (Schema::hasColumn('api_keys', 'environment')) {
                $table->dropColumn('environment');
            }

            if (Schema::hasColumn('api_keys', 'allowed_domains')) {
                $table->dropColumn('allowed_domains');
            }
        });
    }
};
