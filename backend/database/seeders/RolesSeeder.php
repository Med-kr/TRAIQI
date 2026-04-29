<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $roles = [];

        foreach (['super_admin', 'school_admin', 'teacher', 'student', 'parent'] as $role) {
            $roles[$role] = Role::findOrCreate($role, 'web');
        }

        $permissions = [
            'manage schools',
            'manage academic years',
            'manage levels',
            'manage classrooms',
            'manage subjects',
            'manage teacher assignments',
            'manage users',
            'manage imports',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $roles['super_admin']->syncPermissions($permissions);
        $roles['school_admin']->syncPermissions([
            'manage academic years',
            'manage levels',
            'manage classrooms',
            'manage subjects',
            'manage teacher assignments',
            'manage users',
            'manage imports',
        ]);
    }
}
