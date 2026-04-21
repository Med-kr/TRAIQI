<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GradeComment;

class GradeCommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'grade_id' => 'required',
            'comment' => 'required'
        ]);

        return GradeComment::create([
            'grade_id' => $request->grade_id,
            'teacher_id' => auth()->id(),
            'comment' => $request->comment
        ]);
    }
}
