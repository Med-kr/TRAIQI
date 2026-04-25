<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\TeacherAssignment;
use Illuminate\Http\Request;
use App\Models\Evaluation;

class EvaluationController extends Controller
{
    public function index()
    {
        $evaluations = Evaluation::where('teacher_id', auth()->id())
            ->with(['classroom', 'subject', 'grades'])
            ->latest('date')
            ->get();

        $assignments = TeacherAssignment::where('teacher_id', auth()->id())
            ->with(['classroom', 'subject'])
            ->get();

        return view('dashboards.teacher', [
            'evaluations' => $evaluations,
            'assignments' => $assignments,
            'evaluationsCount' => $evaluations->count(),
            'assignmentsCount' => $assignments->count(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'date' => ['required', 'date'],
        ]);

        $evaluation = Evaluation::create([
            'title' => $request->title,
            'classroom_id' => $request->classroom_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => auth()->id(),
            'date' => $request->date,
        ]);

        return response()->json($evaluation);
    }
}
