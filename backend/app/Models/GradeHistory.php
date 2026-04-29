<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_id',
        'changed_by',
        'old_value',
        'new_value',
        'reason',
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
