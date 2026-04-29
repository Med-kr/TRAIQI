<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'evaluation_id',
        'student_id',
        'value',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function comments()
    {
        return $this->hasMany(GradeComment::class);
    }

    public function reviewRequests()
    {
        return $this->hasMany(ReviewRequest::class);
    }

    public function histories()
    {
        return $this->hasMany(GradeHistory::class)->latest();
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
