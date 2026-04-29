<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('roles') && ! Schema::hasColumn('roles', 'guard_name')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->string('guard_name')->default('web')->after('name');
            });
        }

        DB::table('roles')->whereNull('guard_name')->update(['guard_name' => 'web']);

        foreach (['super_admin', 'school_admin', 'teacher', 'parent', 'student'] as $roleName) {
            DB::table('roles')->updateOrInsert(
                ['name' => $roleName, 'guard_name' => 'web'],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        if (Schema::hasTable('role_user') && Schema::hasTable('model_has_roles')) {
            $targetRoleIds = DB::table('roles')
                ->whereIn('name', ['super_admin', 'school_admin', 'teacher', 'parent', 'student'])
                ->pluck('id', 'name');

            $legacyAssignments = DB::table('role_user')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->join('users', 'users.id', '=', 'role_user.user_id')
                ->select('role_user.id', 'role_user.user_id', 'users.school_id', 'roles.name')
                ->get();

            foreach ($legacyAssignments as $assignment) {
                $targetRoleName = match ($assignment->name) {
                    'administration', 'admin' => $assignment->school_id ? 'school_admin' : 'super_admin',
                    'teacher' => 'teacher',
                    'parent' => 'parent',
                    'student' => 'student',
                    default => null,
                };

                if (! $targetRoleName) {
                    continue;
                }

                $targetRoleId = $targetRoleIds[$targetRoleName] ?? null;

                if (! $targetRoleId) {
                    continue;
                }

                DB::table('role_user')
                    ->where('id', $assignment->id)
                    ->update([
                        'role_id' => $targetRoleId,
                        'updated_at' => now(),
                    ]);

                DB::table('model_has_roles')->insertOrIgnore([
                    'role_id' => $targetRoleId,
                    'model_type' => User::class,
                    'model_id' => $assignment->user_id,
                ]);
            }

            DB::table('roles')
                ->whereIn('name', ['administration', 'admin'])
                ->whereNotIn('id', DB::table('role_user')->select('role_id'))
                ->delete();
        }
    }

    public function down(): void
    {
        //
    }
};
