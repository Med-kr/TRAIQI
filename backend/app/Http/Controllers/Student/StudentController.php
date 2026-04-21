<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\Evaluation;

class StudentController extends Controller
{
    public function dashboard()
    {
        $student = auth()->user()->studentProfile;

        $grades = Grade::where('student_id', $student->id)
            ->with('evaluation')
            ->get();

        return response()->json($grades);
    }

    public function evaluations()
    {
        $student = auth()->user()->studentProfile;

        return Evaluation::where('classroom_id', $student->classroom_id)
            ->with('subject')
            ->get();
    }
}
