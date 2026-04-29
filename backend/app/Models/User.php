<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    protected string $guard_name = 'web';

    protected $fillable = [
        'uuid',
        'global_code',
        'name',
        'email',
        'password',
        'is_active',
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
            'is_active' => 'boolean',
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

    public function scopeForSchoolContext($query, ?self $user = null)
    {
        $user ??= auth()->user();

        if (! $user || $user->hasRole('super_admin')) {
            return $query;
        }

        if (! $user->school_id) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('school_id', $user->school_id);
    }

    public function primaryRole(): ?string
    {
        $ownedRoles = $this->getRoleNames();

        foreach (['super_admin', 'school_admin', 'teacher', 'parent', 'student'] as $role) {
            if ($ownedRoles->contains($role)) {
                return $role;
            }
        }

        return $ownedRoles->first();
    }

    public function isAdmin(): bool
    {
        return $this->hasAnyRole(['super_admin', 'school_admin']);
    }

    public function dashboardRoute(): string
    {
        return match ($this->primaryRole()) {
            'super_admin', 'school_admin' => 'admin.dashboard',
            'teacher' => 'teacher.dashboard',
            'parent' => 'parent.dashboard',
            'student' => 'student.dashboard',
            default => 'dashboard',
        };
    }

}
