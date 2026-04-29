<?php

namespace Database\Seeders;

use App\Models\AdministrationProfile;
use App\Models\Classroom;
use App\Models\Level;
use App\Models\ParentProfile;
use App\Models\Role;
use App\Models\School;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Models\AcademicYear;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolesSeeder::class);

        $superAdminRole = Role::findOrCreate('super_admin', 'web');
        $studentRole = Role::findOrCreate('student', 'web');
        $teacherRole = Role::findOrCreate('teacher', 'web');
        $parentRole = Role::findOrCreate('parent', 'web');
        $schoolAdminRole = Role::findOrCreate('school_admin', 'web');

        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'uuid' => (string) Str::uuid(),
                'global_code' => 'SUP001',
                'name' => 'Super Admin Test',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'school_id' => null,
                'level_id' => null,
            ]
        );
        $superAdmin->syncRoles([$superAdminRole]);

        $school = School::firstOrCreate(['name' => 'TRAIQI School']);
        $academicYear = AcademicYear::firstOrCreate(
            ['school_id' => $school->id, 'name' => '2026-2027'],
            ['start_date' => '2026-09-01', 'end_date' => '2027-06-30', 'is_current' => true]
        );
        $level = Level::firstOrCreate(
            ['school_id' => $school->id, 'code' => 'L1'],
            ['name' => 'Level 1', 'order' => 1]
        );
        $subject = Subject::firstOrCreate(
            ['school_id' => $school->id, 'name' => 'Mathematics']
        );

        $teacher = User::updateOrCreate(
            ['email' => 'teacher@test.com'],
            [
                'uuid' => (string) Str::uuid(),
                'global_code' => 'TEA001',
                'name' => 'Teacher Test',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'school_id' => $school->id,
                'level_id' => $level->id,
            ]
        );
        $teacher->syncRoles([$teacherRole]);
        TeacherProfile::firstOrCreate(['user_id' => $teacher->id], ['specialty' => 'General']);

        $admin = User::updateOrCreate(
            ['email' => 'administration@test.com'],
            [
                'uuid' => (string) Str::uuid(),
                'global_code' => 'SCH001',
                'name' => 'Administration Test',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'school_id' => $school->id,
                'level_id' => $level->id,
            ]
        );
        $admin->syncRoles([$schoolAdminRole]);
        AdministrationProfile::firstOrCreate(['user_id' => $admin->id], ['position' => 'Manager']);

        $class = Classroom::firstOrCreate([
            'name' => '1A',
            'school_id' => $school->id,
            'academic_year_id' => $academicYear->id,
            'level_id' => $level->id,
        ]);

        TeacherAssignment::firstOrCreate([
            'school_id' => $school->id,
            'teacher_id' => $teacher->id,
            'classroom_id' => $class->id,
            'subject_id' => $subject->id,
        ]);

        $students = collect();

        for ($i = 1; $i <= 5; $i++) {
            $user = User::updateOrCreate(
                ['email' => 'student' . $i . '@test.com'],
                [
                    'uuid' => (string) Str::uuid(),
                    'global_code' => sprintf('STU%03d', $i),
                    'name' => 'Student ' . $i,
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'school_id' => $school->id,
                    'level_id' => $level->id,
                ]
            );

            $user->syncRoles([$studentRole]);

            StudentProfile::updateOrCreate(
                ['user_id' => $user->id],
                ['classroom_id' => $class->id]
            );

            $students->push($user);
        }

        $parent = User::updateOrCreate(
            ['email' => 'parent@test.com'],
            [
                'uuid' => (string) Str::uuid(),
                'global_code' => 'PAR001',
                'name' => 'Parent Test',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'school_id' => $school->id,
                'level_id' => $level->id,
            ]
        );
        $parent->syncRoles([$parentRole]);
        ParentProfile::updateOrCreate(
            ['user_id' => $parent->id],
            ['phone' => '+212600000000']
        );
        $parent->children()->sync($students->take(2)->pluck('id')->all());
    }
}
