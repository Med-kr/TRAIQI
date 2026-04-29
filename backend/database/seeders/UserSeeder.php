<?php

namespace Database\Seeders;

use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::findOrCreate('super_admin', 'web');
        $studentRole = Role::findOrCreate('student', 'web');

        $admin = User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'uuid' => (string) Str::uuid(),
                'global_code' => 'SUP001',
                'name' => 'Admin',
                'password' => Hash::make('123456'),
                'school_id' => null,
                'level_id' => null,
            ]
        );
        $admin->syncRoles([$adminRole]);

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
        $user->syncRoles([$studentRole]);
        StudentProfile::firstOrCreate(['user_id' => $user->id]);
    }
}
