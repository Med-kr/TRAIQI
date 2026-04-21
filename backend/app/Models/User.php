<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Models\Role;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */


    use HasFactory, Notifiable;



    protected $fillable = [
        'uuid',
        'global_code',
        'name',
        'email',
        'password',
        'school_id',
        'level_id',
    ];


    protected $hidden = [
        'password',
        'remember_token'
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
            $user->uuid = (string) Str::uuid();

            $user->global_code = 'USR-' . random_int(100000, 999999);
        });
    }
    public function roles()
    {
        return $this->hasOne(Role::class);
    }
    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }
    public function teacherAssignments()
    {
        return $this->hasOne(TeacherAssignment::class, 'teacher_id');
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}
