<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Evaluation;
use App\Models\Grade;
use App\Models\GradeHistory;
use App\Models\Notification;
use App\Models\TeacherAssignment;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function store(Request $request, $evaluationId)
    {
        $request->validate([
            'grades' => ['required', 'array'],
            'grades.*' => ['nullable', 'numeric', 'min:0', 'max:20'],
        ]);

        $evaluation = Evaluation::forSchoolContext(auth()->user())
            ->where('teacher_id', auth()->id())
            ->findOrFail($evaluationId);

        abort_if($evaluation->is_locked, 422, 'This evaluation is locked and can no longer be edited.');

        $allowedStudentIds = $evaluation->classroom
            ?->students()
            ->pluck('user_id')
            ->all() ?? [];

        foreach ($request->grades as $studentId => $value) {
            if (! in_array((int) $studentId, $allowedStudentIds, true) || $value === null || $value === '') {
                continue;
            }

            $existingGrade = Grade::query()
                ->where('evaluation_id', $evaluation->id)
                ->where('student_id', $studentId)
                ->first();

            $grade = Grade::updateOrCreate(
                [
                    'evaluation_id' => $evaluation->id,
                    'student_id' => $studentId,
                ],
                [
                    'school_id' => $evaluation->school_id,
                    'value' => $value,
                ]
            );

            $oldValue = $existingGrade?->value;
            $newValue = (float) $value;

            if ($oldValue === null || (float) $oldValue !== $newValue) {
                GradeHistory::create([
                    'grade_id' => $grade->id,
                    'changed_by' => auth()->id(),
                    'old_value' => $oldValue,
                    'new_value' => $newValue,
                    'reason' => $oldValue === null ? 'initial_entry' : 'teacher_update',
                ]);

                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'grade_saved',
                    'description' => sprintf(
                        'Grade #%d saved for evaluation #%d and student #%d: %s -> %s.',
                        $grade->id,
                        $evaluation->id,
                        $studentId,
                        $oldValue ?? 'null',
                        $newValue
                    ),
                ]);

                if ($evaluation->is_published) {
                    $student = $grade->student()->with('parents')->first();

                    if ($student) {
                        $recipients = collect([$student])->merge($student->parents)->unique('id');

                        foreach ($recipients as $recipient) {
                            Notification::create([
                                'user_id' => $recipient->id,
                                'title' => 'Grade updated',
                                'body' => sprintf(
                                    'A grade for %s in %s is now available: %.2f/20.',
                                    $student->name,
                                    $evaluation->subject?->name ?? 'an evaluation',
                                    $newValue
                                ),
                            ]);
                        }
                    }
                }
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Grades saved successfully',
            ]);
        }

        return redirect()
            ->route('teacher.grades.show', $evaluation->id)
            ->with('status', 'Grades saved successfully.');
    }

    public function show($evaluationId)
    {
        $evaluation = Evaluation::forSchoolContext(auth()->user())
            ->where('teacher_id', auth()->id())
            ->with(['classroom.students.user', 'subject', 'grades.comments.teacher', 'grades.reviewRequests.student', 'grades.histories.actor'])
            ->findOrFail($evaluationId);

        $assignment = TeacherAssignment::forSchoolContext(auth()->user())
            ->where('teacher_id', auth()->id())
            ->where('classroom_id', $evaluation->classroom_id)
            ->where('subject_id', $evaluation->subject_id)
            ->firstOrFail();

        $grades = Grade::forSchoolContext(auth()->user())
            ->where('evaluation_id', $evaluation->id)
            ->with(['student', 'comments.teacher', 'reviewRequests.student', 'histories.actor'])
            ->get()
            ->keyBy('student_id');

        $classAverage = round($grades->avg('value') ?? 0, 2);
        $remainingCopies = max(($evaluation->classroom?->students?->count() ?? 0) - $grades->count(), 0);

        return view('teacher.grades.show', [
            'assignment' => $assignment,
            'evaluation' => $evaluation,
            'students' => $evaluation->classroom?->students ?? collect(),
            'grades' => $grades,
            'classAverage' => $classAverage,
            'remainingCopies' => $remainingCopies,
        ]);
    }
}
