<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomSubject extends Model
{
    use HasFactory;

    protected $table = 'classroom_subject';

    protected $fillable = [
        'classroom_id',
        'subject_id',
    ];

    public $incrementing = false;

    protected $primaryKey = null;

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
