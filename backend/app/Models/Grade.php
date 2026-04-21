<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_id',
        'student_id',
        'score',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function student()
    {
        return $this->belongsTo(StudentProfile::class, 'student_id');
    }

    public function comments()
    {
        return $this->hasMany(GradeComment::class);
    }

    public function reviewRequests()
    {
        return $this->hasMany(ReviewRequest::class);
    }
}
