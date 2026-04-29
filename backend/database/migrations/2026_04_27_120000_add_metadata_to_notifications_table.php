<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (! Schema::hasColumn('notifications', 'type')) {
                $table->string('type')->default('system')->after('user_id');
            }

            if (! Schema::hasColumn('notifications', 'action_url')) {
                $table->string('action_url')->nullable()->after('body');
            }

            if (! Schema::hasColumn('notifications', 'data')) {
                $table->json('data')->nullable()->after('action_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'data')) {
                $table->dropColumn('data');
            }

            if (Schema::hasColumn('notifications', 'action_url')) {
                $table->dropColumn('action_url');
            }

            if (Schema::hasColumn('notifications', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
