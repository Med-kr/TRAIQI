<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        $admin = User::updateOrCreate(
            [
                'uuid' => Str::uuid(),
                'global_code' => 'ADM001',
                'name' => 'Admin',
                'email' => 'admin@test.com',
                'password' => Hash::make('123456'),
                'role' => 'admin',
                'school_id' => null,
                'level_id' => null
            ]
        );
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        $user = User::updateOrCreate(
            [
                'uuid' => Str::uuid(),
                'global_code' => 'USR001',
                'name' => 'User',
                'email' => 'user@test.com',
                'password' => Hash::make('123456'),
                'role' => 'user',
                'school_id' => null,
                'level_id' => null
            ]
        );
        $user->roles()->syncWithoutDetaching([$userRole->id]);
    }
}
