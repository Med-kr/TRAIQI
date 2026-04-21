<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Grade;

class ParentController extends Controller
{
    public function dashboard()
    {
        $parent = auth()->user();

        $children = $parent->children; // relation later

        return response()->json($children);
    }

    public function childGrades($studentId)
    {
        return Grade::where('student_id', $studentId)
            ->with('evaluation')
            ->get();
    }
}
