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
        return response()->json([
            'total_users' => User::count(),
            'total_evaluations' => Evaluation::count(),
            'total_grades' => Grade::count(),
            'total_classes' => Classroom::count(),
        ]);
    }

    // moyenne générale
    public function averageGrades()
    {
        $average = Grade::avg('value');

        return response()->json([
            'average_grade' => round($average, 2)
        ]);
    }

    // classes performance
    public function classStats()
    {
        $stats = Classroom::withCount('students')->get();

        return response()->json($stats);
    }
}
