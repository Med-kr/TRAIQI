<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['school_id', 'name'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class)->withTimestamps();
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
