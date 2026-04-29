<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            if (! Schema::hasColumn('evaluations', 'type')) {
                $table->string('type')->default('devoir')->after('title');
            }

            if (! Schema::hasColumn('evaluations', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('date');
            }

            if (! Schema::hasColumn('evaluations', 'is_locked')) {
                $table->boolean('is_locked')->default(false)->after('is_published');
            }
        });
    }

    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            if (Schema::hasColumn('evaluations', 'is_locked')) {
                $table->dropColumn('is_locked');
            }

            if (Schema::hasColumn('evaluations', 'is_published')) {
                $table->dropColumn('is_published');
            }

            if (Schema::hasColumn('evaluations', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
