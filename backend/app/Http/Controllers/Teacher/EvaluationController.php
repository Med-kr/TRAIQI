<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Evaluation;
use App\Models\Classroom;
use App\Models\Subject;

class EvaluationController extends Controller
{
    public function index()
    {
        $evaluations = Evaluation::where('teacher_id', auth()->id())
            ->with(['classroom', 'subject'])
            ->get();

        return response()->json($evaluations);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'classroom_id' => 'required',
            'subject_id' => 'required',
        ]);

        $evaluation = Evaluation::create([
            'title' => $request->title,
            'classroom_id' => $request->classroom_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => auth()->id(),
        ]);

        return response()->json($evaluation);
    }
}
