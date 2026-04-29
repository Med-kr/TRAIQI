<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Evaluation;
use App\Models\Grade;
use App\Models\Classroom;

class ReportController extends Controller
{
    // dashboard stats
    public function index()
    {
        $user = auth()->user();

        $payload = [
            'total_users' => User::forSchoolContext($user)->count(),
            'total_evaluations' => Evaluation::forSchoolContext($user)->count(),
            'total_grades' => Grade::forSchoolContext($user)->count(),
            'total_classes' => Classroom::forSchoolContext($user)->count(),
        ];

        if (request()->expectsJson()) {
            return response()->json($payload);
        }

        return view('admin.reports.index', [
            'reportStats' => $payload,
            'averageGrade' => round(Grade::forSchoolContext($user)->avg('value') ?? 0, 2),
            'classrooms' => Classroom::forSchoolContext($user)->withCount('students')->get(),
        ]);
    }

    // moyenne générale
    public function averageGrades()
    {
        $average = Grade::forSchoolContext(auth()->user())->avg('value');

        return response()->json([
            'average_grade' => round($average, 2)
        ]);
    }

    // classes performance
    public function classStats()
    {
        $stats = Classroom::forSchoolContext(auth()->user())
            ->withCount('students')
            ->get();

        return response()->json($stats);
    }
}
