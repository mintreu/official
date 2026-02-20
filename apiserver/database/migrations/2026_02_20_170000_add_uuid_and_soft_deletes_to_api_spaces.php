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
        Schema::table('api_spaces', function (Blueprint $table): void {
            if (! Schema::hasColumn('api_spaces', 'uuid')) {
                $table->uuid('uuid')->nullable()->after('id');
            }
            if (! Schema::hasColumn('api_spaces', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        DB::table('api_spaces')
            ->select(['id', 'uuid'])
            ->orderBy('id')
            ->chunkById(200, function ($rows): void {
                foreach ($rows as $row) {
                    if (! empty($row->uuid)) {
                        continue;
                    }

                    DB::table('api_spaces')
                        ->where('id', $row->id)
                        ->update(['uuid' => (string) Str::uuid()]);
                }
            });

        Schema::table('api_spaces', function (Blueprint $table): void {
            $table->unique('uuid');
        });
    }

    public function down(): void
    {
        Schema::table('api_spaces', function (Blueprint $table): void {
            if (Schema::hasColumn('api_spaces', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
            if (Schema::hasColumn('api_spaces', 'uuid')) {
                $table->dropUnique('api_spaces_uuid_unique');
                $table->dropColumn('uuid');
            }
        });
    }
};
