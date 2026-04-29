<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_id',
        'student_id',
        'reason',
        'status',
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
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

        return $query->whereHas('grade', fn ($gradeQuery) => $gradeQuery->where('school_id', $user->school_id));
    }
}
