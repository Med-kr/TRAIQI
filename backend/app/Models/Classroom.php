<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'school_id', 'level_id'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function students()
    {
        return $this->hasMany(StudentProfile::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

}
