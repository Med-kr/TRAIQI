<?php

namespace App\Services;

use App\Models\Level;
use App\Models\ParentProfile;
use App\Models\School;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\AdministrationProfile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserProvisioningService
{
    public function upsertStudent(School $school, array $payload): array
    {
        $generatedPassword = null;
        $level = $this->resolveLevel($school, $payload);

        $attributes = [
            'name' => $payload['name'],
            'school_id' => $school->id,
            'level_id' => $level?->id,
        ];

        if (empty($payload['email'])) {
            $payload['email'] = $this->generatedEmail($payload['name'], 'student');
        }

        $user = User::query()->where('email', $payload['email'])->first();
        $isNew = ! $user;

        if ($isNew) {
            $generatedPassword = $payload['password'] ?? $this->generatedPassword();
            $user = User::create(array_merge($attributes, [
                'email' => $payload['email'],
                'email_verified_at' => now(),
                'password' => Hash::make($generatedPassword),
            ]));
        } else {
            $user->update($attributes);
        }

        $user->syncRoles([Role::findOrCreate('student', 'web')]);

        StudentProfile::updateOrCreate(
            ['user_id' => $user->id],
            ['phone' => $payload['phone'] ?? null]
        );

        return [
            'user' => $user,
            'created' => $isNew,
            'generated_password' => $generatedPassword,
            'level' => $level,
        ];
    }

    public function upsertParent(School $school, array $payload): array
    {
        $generatedPassword = null;

        if (empty($payload['email'])) {
            $payload['email'] = $this->generatedEmail($payload['name'], 'parent');
        }

        $user = User::query()->where('email', $payload['email'])->first();
        $isNew = ! $user;

        if ($isNew) {
            $generatedPassword = $payload['password'] ?? $this->generatedPassword();
            $user = User::create([
                'name' => $payload['name'],
                'email' => $payload['email'],
                'email_verified_at' => now(),
                'password' => Hash::make($generatedPassword),
                'school_id' => $school->id,
            ]);
        } else {
            $user->update([
                'name' => $payload['name'],
                'school_id' => $school->id,
            ]);
        }

        $user->syncRoles([Role::findOrCreate('parent', 'web')]);

        ParentProfile::updateOrCreate(
            ['user_id' => $user->id],
            ['phone' => $payload['phone'] ?? null]
        );

        return [
            'user' => $user,
            'created' => $isNew,
            'generated_password' => $generatedPassword,
        ];
    }

    public function upsertTeacher(School $school, array $payload): array
    {
        $generatedPassword = null;

        if (empty($payload['email'])) {
            $payload['email'] = $this->generatedEmail($payload['name'], 'teacher');
        }

        $level = $this->resolveLevel($school, $payload);
        $user = User::query()->where('email', $payload['email'])->first();
        $isNew = ! $user;

        if ($isNew) {
            $generatedPassword = $payload['password'] ?? $this->generatedPassword();
            $user = User::create([
                'name' => $payload['name'],
                'email' => $payload['email'],
                'email_verified_at' => now(),
                'password' => Hash::make($generatedPassword),
                'school_id' => $school->id,
                'level_id' => $level?->id,
            ]);
        } else {
            $user->update([
                'name' => $payload['name'],
                'school_id' => $school->id,
                'level_id' => $level?->id,
            ]);
        }

        $user->syncRoles([Role::findOrCreate('teacher', 'web')]);

        TeacherProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $payload['phone'] ?? null,
                'specialty' => $payload['specialty'] ?? ($payload['subject_name'] ?? null),
            ]
        );

        return [
            'user' => $user,
            'created' => $isNew,
            'generated_password' => $generatedPassword,
            'level' => $level,
        ];
    }

    public function upsertAdministration(School $school, array $payload, string $role = 'school_admin'): array
    {
        $generatedPassword = null;

        if (empty($payload['email'])) {
            $payload['email'] = $this->generatedEmail($payload['name'], $role);
        }

        $user = User::query()->where('email', $payload['email'])->first();
        $isNew = ! $user;

        if ($isNew) {
            $generatedPassword = $payload['password'] ?? $this->generatedPassword();
            $user = User::create([
                'name' => $payload['name'],
                'email' => $payload['email'],
                'email_verified_at' => now(),
                'password' => Hash::make($generatedPassword),
                'school_id' => $role === 'super_admin' ? null : $school->id,
                'is_active' => $payload['is_active'] ?? true,
            ]);
        } else {
            $user->update([
                'name' => $payload['name'],
                'school_id' => $role === 'super_admin' ? null : $school->id,
                'is_active' => $payload['is_active'] ?? $user->is_active,
            ]);
        }

        $user->syncRoles([Role::findOrCreate($role, 'web')]);

        AdministrationProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $payload['phone'] ?? null,
                'position' => $payload['position'] ?? null,
            ]
        );

        return [
            'user' => $user,
            'created' => $isNew,
            'generated_password' => $generatedPassword,
        ];
    }

    public function linkParentToStudent(User $parent, User $student): void
    {
        $parent->children()->syncWithoutDetaching([$student->id]);
    }

    protected function resolveLevel(School $school, array $payload): ?Level
    {
        $code = trim((string) ($payload['level_code'] ?? ''));
        $name = trim((string) ($payload['level_name'] ?? ''));

        if ($code === '' && $name === '') {
            return null;
        }

        return Level::firstOrCreate(
            [
                'school_id' => $school->id,
                'code' => $code !== '' ? $code : Str::upper(Str::slug($name, '')),
            ],
            [
                'name' => $name !== '' ? $name : $code,
                'order' => ((int) Level::query()->where('school_id', $school->id)->max('order')) + 1,
            ]
        );
    }

    protected function generatedPassword(): string
    {
        return 'Traiqi@' . random_int(100000, 999999);
    }

    protected function generatedEmail(string $name, string $prefix): string
    {
        return sprintf(
            '%s.%s.%s@traiqi.local',
            $prefix,
            Str::slug($name),
            random_int(1000, 9999)
        );
    }
}
