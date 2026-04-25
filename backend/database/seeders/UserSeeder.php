<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'administration']);
        $studentRole = Role::firstOrCreate(['name' => 'student']);

        $admin = User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'uuid' => (string) Str::uuid(),
                'global_code' => 'ADM001',
                'name' => 'Admin',
                'password' => Hash::make('123456'),
                'school_id' => null,
                'level_id' => null,
            ]
        );
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        $user = User::updateOrCreate(
            ['email' => 'user@test.com'],
            [
                'uuid' => (string) Str::uuid(),
                'global_code' => 'USR001',
                'name' => 'User',
                'password' => Hash::make('123456'),
                'school_id' => null,
                'level_id' => null,
            ]
        );
        $user->roles()->syncWithoutDetaching([$studentRole->id]);
        StudentProfile::firstOrCreate(['user_id' => $user->id]);
    }
}
