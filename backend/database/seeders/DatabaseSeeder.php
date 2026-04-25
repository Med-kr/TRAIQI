<?php

namespace Database\Seeders;

use App\Models\AdministrationProfile;
use App\Models\Classroom;
use App\Models\Level;
use App\Models\Role;
use App\Models\School;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);
        $administrationRole = Role::firstOrCreate(['name' => 'administration']);

        $school = School::firstOrCreate(['name' => 'TRAIQI School']);
        $level = Level::firstOrCreate(
            ['code' => 'L1'],
            ['name' => 'Level 1', 'order' => 1]
        );
        $subject = Subject::firstOrCreate(['name' => 'Mathematics']);

        $teacher = User::factory()->create([
            'name' => 'Teacher Test',
            'email' => 'teacher@test.com',
            'password' => bcrypt('password'),
            'school_id' => $school->id,
            'level_id' => $level->id,
        ]);
        $teacher->roles()->attach($teacherRole->id);
        TeacherProfile::firstOrCreate(['user_id' => $teacher->id], ['specialty' => 'General']);

        $admin = User::factory()->create([
            'name' => 'Administration Test',
            'email' => 'administration@test.com',
            'password' => bcrypt('password'),
            'school_id' => $school->id,
            'level_id' => $level->id,
        ]);
        $admin->roles()->attach($administrationRole->id);
        AdministrationProfile::firstOrCreate(['user_id' => $admin->id], ['position' => 'Manager']);

        $class = Classroom::create([
            'name' => '1A',
            'school_id' => $school->id,
            'level_id' => $level->id,
        ]);

        TeacherAssignment::firstOrCreate([
            'teacher_id' => $teacher->id,
            'classroom_id' => $class->id,
            'subject_id' => $subject->id,
        ]);

        for ($i = 1; $i <= 5; $i++) {
            $user = User::factory()->create([
                'name' => 'Student ' . $i,
                'email' => 'student' . $i . '@test.com',
                'password' => bcrypt('password'),
                'school_id' => $school->id,
                'level_id' => $level->id,
            ]);

            $user->roles()->attach($studentRole->id);

            StudentProfile::create([
                'user_id' => $user->id,
                'classroom_id' => $class->id,
            ]);
        }
    }
}
