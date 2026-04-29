<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }

    public function levels()
    {
        return $this->hasMany(Level::class);
    }

    public function academicYears()
    {
        return $this->hasMany(AcademicYear::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function scopeForSchoolContext($query, ?User $user = null)
    {
        $user ??= auth()->user();

        if (! $user || $user->hasRole('super_admin')) {
            return $query;
        }

        if (! $user->school_id) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('id', $user->school_id);
    }
}
