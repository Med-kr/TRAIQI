<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->dropUnique('levels_code_unique');
        });

        Schema::table('levels', function (Blueprint $table) {
            if (! Schema::hasColumn('levels', 'school_id')) {
                $table->foreignId('school_id')
                    ->nullable()
                    ->after('id')
                    ->constrained()
                    ->nullOnDelete();
            }
        });

        DB::table('levels')
            ->whereNull('school_id')
            ->update([
                'school_id' => DB::raw('(select school_id from users where users.level_id = levels.id and users.school_id is not null limit 1)'),
            ]);

        Schema::table('levels', function (Blueprint $table) {
            $table->unique(['school_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->dropUnique(['school_id', 'code']);

            if (Schema::hasColumn('levels', 'school_id')) {
                $table->dropConstrainedForeignId('school_id');
            }

            $table->unique('code');
        });
    }
};
