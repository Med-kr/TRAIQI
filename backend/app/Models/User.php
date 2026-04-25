<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'uuid',
        'global_code',
        'name',
        'email',
        'password',
        'school_id',
        'level_id',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->uuid ??= (string) Str::uuid();
            $user->global_code ??= 'USR-' . random_int(100000, 999999);
        });
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function parentProfile()
    {
        return $this->hasOne(ParentProfile::class);
    }

    public function teacherProfile()
    {
        return $this->hasOne(TeacherProfile::class);
    }

    public function administrationProfile()
    {
        return $this->hasOne(AdministrationProfile::class);
    }

    public function teacherAssignments()
    {
        return $this->hasMany(TeacherAssignment::class, 'teacher_id');
    }

    public function children()
    {
        return $this->belongsToMany(
            User::class,
            'parent_student',
            'parent_user_id',
            'student_user_id'
        );
    }

    public function parents()
    {
        return $this->belongsToMany(
            User::class,
            'parent_student',
            'student_user_id',
            'parent_user_id'
        );
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function hasRole(string ...$roles): bool
    {
        $allowed = collect($roles)->map(fn ($role) => $this->normalizeRoleName($role));
        $owned = $this->roles()
            ->pluck('name')
            ->map(fn ($role) => $this->normalizeRoleName($role));

        return $owned->intersect($allowed)->isNotEmpty();
    }

    public function primaryRole(): ?string
    {
        $role = $this->roles()
            ->orderBy('roles.id')
            ->value('name');

        return $role ? $this->normalizeRoleName($role) : null;
    }

    public function dashboardRoute(): string
    {
        return match ($this->primaryRole()) {
            'administration' => 'administration.dashboard',
            'teacher' => 'teacher.dashboard',
            'parent' => 'parent.dashboard',
            'student' => 'student.dashboard',
            default => 'dashboard',
        };
    }

    protected function role(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->primaryRole()
        );
    }

    private function normalizeRoleName(string $role): string
    {
        return $role === 'admin' ? 'administration' : $role;
    }
}
