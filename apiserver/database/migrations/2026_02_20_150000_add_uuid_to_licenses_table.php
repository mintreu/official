<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('licenses', function (Blueprint $table): void {
            if (! Schema::hasColumn('licenses', 'uuid')) {
                $table->uuid('uuid')->nullable()->after('id');
            }
        });

        DB::table('licenses')
            ->select(['id', 'uuid'])
            ->orderBy('id')
            ->chunkById(200, function ($licenses): void {
                foreach ($licenses as $license) {
                    if (! empty($license->uuid)) {
                        continue;
                    }

                    DB::table('licenses')
                        ->where('id', $license->id)
                        ->update(['uuid' => (string) Str::uuid()]);
                }
            });

        Schema::table('licenses', function (Blueprint $table): void {
            $table->unique('uuid');
        });
    }

    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table): void {
            if (Schema::hasColumn('licenses', 'uuid')) {
                $table->dropUnique('licenses_uuid_unique');
                $table->dropColumn('uuid');
            }
        });
    }
};
