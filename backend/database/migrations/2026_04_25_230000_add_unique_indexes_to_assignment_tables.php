<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classroom_subject', function (Blueprint $table) {
            $table->unique(['classroom_id', 'subject_id']);
        });

        Schema::table('teacher_assignments', function (Blueprint $table) {
            $table->unique(['school_id', 'teacher_id', 'classroom_id', 'subject_id'], 'teacher_assignments_unique_link');
        });
    }

    public function down(): void
    {
        Schema::table('teacher_assignments', function (Blueprint $table) {
            $table->dropUnique('teacher_assignments_unique_link');
        });

        Schema::table('classroom_subject', function (Blueprint $table) {
            $table->dropUnique(['classroom_id', 'subject_id']);
        });
    }
};
