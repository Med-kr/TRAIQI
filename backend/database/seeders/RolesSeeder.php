<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['administration', 'teacher', 'student', 'parent'] as $role) {
            DB::table('roles')->updateOrInsert(['name' => $role], ['name' => $role]);
        }
    }
}
