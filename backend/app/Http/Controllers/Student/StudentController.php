<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Evaluation;
use App\Models\Notification;

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

        return view('student.dashboard', [
            'student' => $student,
            'grades' => $grades,
            'evaluations' => $evaluations,
            'averageGrade' => $grades->avg('value'),
            'passRate' => $grades->count() > 0 ? round(($grades->where('value', '>=', 10)->count() / $grades->count()) * 100, 2) : 0,
            'unreadNotificationsCount' => Notification::where('user_id', $user->id)->where('read', false)->count(),
            'timetableSubjects' => $student?->classroom?->subjects()->orderBy('name')->get() ?? collect(),
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
