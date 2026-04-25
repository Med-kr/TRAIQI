<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Evaluation;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $student = $user->studentProfile;

        $grades = Grade::where('student_id', $user->id)
            ->with(['evaluation.subject', 'comments'])
            ->latest()
            ->get();

        $evaluations = Evaluation::where('classroom_id', $student?->classroom_id)
            ->with('subject')
            ->latest('date')
            ->get();

        return view('dashboards.student', [
            'student' => $student,
            'grades' => $grades,
            'evaluations' => $evaluations,
            'averageGrade' => $grades->avg('value'),
        ]);
    }

    public function evaluations()
    {
        $student = auth()->user()->studentProfile;

        return Evaluation::where('classroom_id', $student->classroom_id)
            ->with('subject')
            ->get();
    }
}
