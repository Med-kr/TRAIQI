<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_current')->default(false);
            $table->timestamps();

            $table->unique(['school_id', 'name']);
        });

        Schema::table('subjects', function (Blueprint $table) {
            if (! Schema::hasColumn('subjects', 'school_id')) {
                $table->foreignId('school_id')
                    ->nullable()
                    ->after('id')
                    ->constrained()
                    ->nullOnDelete();
            }
        });

        Schema::table('classrooms', function (Blueprint $table) {
            if (! Schema::hasColumn('classrooms', 'academic_year_id')) {
                $table->foreignId('academic_year_id')
                    ->nullable()
                    ->after('school_id')
                    ->constrained()
                    ->nullOnDelete();
            }
        });

        Schema::table('teacher_assignments', function (Blueprint $table) {
            if (! Schema::hasColumn('teacher_assignments', 'school_id')) {
                $table->foreignId('school_id')
                    ->nullable()
                    ->after('id')
                    ->constrained()
                    ->nullOnDelete();
            }
        });

        Schema::table('evaluations', function (Blueprint $table) {
            if (! Schema::hasColumn('evaluations', 'school_id')) {
                $table->foreignId('school_id')
                    ->nullable()
                    ->after('id')
                    ->constrained()
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('evaluations', 'academic_year_id')) {
                $table->foreignId('academic_year_id')
                    ->nullable()
                    ->after('subject_id')
                    ->constrained()
                    ->nullOnDelete();
            }
        });

        Schema::table('grades', function (Blueprint $table) {
            if (! Schema::hasColumn('grades', 'school_id')) {
                $table->foreignId('school_id')
                    ->nullable()
                    ->after('id')
                    ->constrained()
                    ->nullOnDelete();
            }
        });

        DB::table('subjects')
            ->leftJoin('classroom_subject', 'classroom_subject.subject_id', '=', 'subjects.id')
            ->leftJoin('classrooms', 'classrooms.id', '=', 'classroom_subject.classroom_id')
            ->whereNull('subjects.school_id')
            ->whereNotNull('classrooms.school_id')
            ->update(['subjects.school_id' => DB::raw('classrooms.school_id')]);

        DB::table('teacher_assignments')
            ->join('classrooms', 'classrooms.id', '=', 'teacher_assignments.classroom_id')
            ->whereNull('teacher_assignments.school_id')
            ->update(['teacher_assignments.school_id' => DB::raw('classrooms.school_id')]);

        DB::table('evaluations')
            ->join('classrooms', 'classrooms.id', '=', 'evaluations.classroom_id')
            ->whereNull('evaluations.school_id')
            ->update([
                'evaluations.school_id' => DB::raw('classrooms.school_id'),
                'evaluations.academic_year_id' => DB::raw('classrooms.academic_year_id'),
            ]);

        DB::table('grades')
            ->join('evaluations', 'evaluations.id', '=', 'grades.evaluation_id')
            ->whereNull('grades.school_id')
            ->update(['grades.school_id' => DB::raw('evaluations.school_id')]);
    }

    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            if (Schema::hasColumn('grades', 'school_id')) {
                $table->dropConstrainedForeignId('school_id');
            }
        });

        Schema::table('evaluations', function (Blueprint $table) {
            if (Schema::hasColumn('evaluations', 'academic_year_id')) {
                $table->dropConstrainedForeignId('academic_year_id');
            }

            if (Schema::hasColumn('evaluations', 'school_id')) {
                $table->dropConstrainedForeignId('school_id');
            }
        });

        Schema::table('teacher_assignments', function (Blueprint $table) {
            if (Schema::hasColumn('teacher_assignments', 'school_id')) {
                $table->dropConstrainedForeignId('school_id');
            }
        });

        Schema::table('classrooms', function (Blueprint $table) {
            if (Schema::hasColumn('classrooms', 'academic_year_id')) {
                $table->dropConstrainedForeignId('academic_year_id');
            }
        });

        Schema::table('subjects', function (Blueprint $table) {
            if (Schema::hasColumn('subjects', 'school_id')) {
                $table->dropConstrainedForeignId('school_id');
            }
        });

        Schema::dropIfExists('academic_years');
    }
};
