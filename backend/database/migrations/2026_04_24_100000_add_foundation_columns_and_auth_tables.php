<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken()->nullable()->after('password');
            }
        });

        Schema::table('student_profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('student_profiles', 'classroom_id')) {
                $table->foreignId('classroom_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained()
                    ->nullOnDelete();
            }
        });

        Schema::table('evaluations', function (Blueprint $table) {
            if (! Schema::hasColumn('evaluations', 'teacher_id')) {
                $table->foreignId('teacher_id')
                    ->nullable()
                    ->after('subject_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });

        if (! Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if (! Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('sessions')) {
            Schema::dropIfExists('sessions');
        }

        if (Schema::hasTable('password_reset_tokens')) {
            Schema::dropIfExists('password_reset_tokens');
        }

        Schema::table('evaluations', function (Blueprint $table) {
            if (Schema::hasColumn('evaluations', 'teacher_id')) {
                $table->dropConstrainedForeignId('teacher_id');
            }
        });

        Schema::table('student_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('student_profiles', 'classroom_id')) {
                $table->dropConstrainedForeignId('classroom_id');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'remember_token')) {
                $table->dropColumn('remember_token');
            }
        });
    }
};
