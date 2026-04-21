<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Classroom;
use App\Models\StudentProfile;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $studentRole = Role::create(['name' => 'student']);
        $teacherRole = Role::create(['name' => 'teacher']);


        $teacher = User::factory()->create([
            'name' => 'Teacher Test',
            'email' => 'teacher@test.com',
            'password' => bcrypt('password'),
        ]);

        $teacher->roles()->attach($teacherRole->id);


        $class = Classroom::create([
            'name' => '1A'
        ]);


        for ($i = 1; $i <= 5; $i++) {
            $user = User::factory()->create([
                'name' => 'Student ' . $i,
                'email' => 'student' . $i . '@test.com',
                'password' => bcrypt('password'),
            ]);

            $user->roles()->attach($studentRole->id);

            StudentProfile::create([
                'user_id' => $user->id,
                'classroom_id' => $class->id,
            ]);
        }
    }
}
