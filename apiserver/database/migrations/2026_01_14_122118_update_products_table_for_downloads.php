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
        Schema::table('products', function (Blueprint $table) {
            // Remove old is_free column if it exists
            if (Schema::hasColumn('products', 'is_free')) {
                $table->dropColumn('is_free');
            }

            // Remove old requires_account column if it exists
            if (Schema::hasColumn('products', 'requires_account')) {
                $table->dropColumn('requires_account');
            }

            // Add new columns for download system
            if (! Schema::hasColumn('products', 'is_payable')) {
                $table->boolean('is_payable')->default(false)->after('featured')->comment('TRUE = paid, FALSE = free');
            }

            if (! Schema::hasColumn('products', 'requires_account')) {
                $table->boolean('requires_account')->default(false)->after('is_payable')->comment('Require login to download');
            }

            if (! Schema::hasColumn('products', 'default_license_type')) {
                $table->string('default_license_type')->default('FREE_ATTRIBUTION')->after('requires_account')->comment('Default license for free downloads');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'is_payable')) {
                $table->dropColumn('is_payable');
            }

            if (Schema::hasColumn('products', 'requires_account')) {
                $table->dropColumn('requires_account');
            }

            if (Schema::hasColumn('products', 'default_license_type')) {
                $table->dropColumn('default_license_type');
            }
        });
    }
};
