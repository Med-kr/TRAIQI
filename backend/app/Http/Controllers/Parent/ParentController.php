<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Grade;

class ParentController extends Controller
{
    public function dashboard()
    {
        $parent = auth()->user();

        $children = $parent->children()
            ->with([
                'studentProfile.classroom',
                'roles',
            ])
            ->get();

        $childrenWithGrades = $children->map(function ($child) {
            $grades = Grade::where('student_id', $child->id)
                ->with(['evaluation.subject'])
                ->latest()
                ->get();

            return [
                'child' => $child,
                'grades' => $grades,
                'average' => $grades->avg('value'),
            ];
        });

        return view('dashboards.parent', [
            'childrenWithGrades' => $childrenWithGrades,
            'childrenCount' => $children->count(),
        ]);
    }

    public function childGrades($studentId)
    {
        return Grade::where('student_id', $studentId)
            ->with(['evaluation.subject'])
            ->get();
    }
}
