<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\StudentProfile;

class GradeController extends Controller
{
    public function store(Request $request, $evaluationId)
    {
        $request->validate([
            'grades' => 'required|array'
        ]);

        foreach ($request->grades as $studentId => $value) {
            Grade::updateOrCreate(
                [
                    'evaluation_id' => $evaluationId,
                    'student_id' => $studentId
                ],
                [
                    'grade' => $value
                ]
            );
        }

        return response()->json([
            'message' => 'Grades saved successfully'
        ]);
    }

    public function show($evaluationId)
    {
        return Grade::where('evaluation_id', $evaluationId)
            ->with('student')
            ->get();
    }
}
