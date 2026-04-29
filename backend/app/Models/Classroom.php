<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'school_id', 'academic_year_id', 'level_id'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function students()
    {
        return $this->hasMany(StudentProfile::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class)->withTimestamps();
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
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

        return $query->where('school_id', $user->school_id);
    }
}
