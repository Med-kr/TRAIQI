<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\Notification;
use App\Models\TeacherAssignment;
use Illuminate\Http\Request;
use App\Models\Evaluation;
use Illuminate\Validation\Rule;

class EvaluationController extends Controller
{
    public function index()
    {
        $teacher = auth()->user();

        $evaluations = Evaluation::forSchoolContext($teacher)
            ->where('teacher_id', auth()->id())
            ->with(['classroom', 'subject', 'grades', 'classroom.students.user'])
            ->latest('date')
            ->get();

        $assignments = TeacherAssignment::forSchoolContext($teacher)
            ->where('teacher_id', auth()->id())
            ->with(['classroom.students.user', 'subject'])
            ->get();

        $gradesCount = Grade::forSchoolContext($teacher)
            ->whereHas('evaluation', fn ($query) => $query->where('teacher_id', $teacher->id))
            ->count();

        $totalStudentsAcrossAssignments = $assignments->sum(fn ($assignment) => $assignment->classroom?->students?->count() ?? 0);
        $remainingCopies = $evaluations->sum(function ($evaluation) {
            $studentCount = $evaluation->classroom?->students?->count() ?? 0;

            return max($studentCount - $evaluation->grades->count(), 0);
        });

        $studentsInDifficulty = Grade::forSchoolContext($teacher)
            ->whereHas('evaluation', fn ($query) => $query->where('teacher_id', $teacher->id))
            ->where('value', '<', 10)
            ->distinct('student_id')
            ->count('student_id');

        $classAverage = round(
            Grade::forSchoolContext($teacher)
                ->whereHas('evaluation', fn ($query) => $query->where('teacher_id', $teacher->id))
                ->avg('value') ?? 0,
            2
        );

        return view('teacher.dashboard', [
            'evaluations' => $evaluations,
            'assignments' => $assignments,
            'evaluationsCount' => $evaluations->count(),
            'assignmentsCount' => $assignments->count(),
            'gradesCount' => $gradesCount,
            'remainingCopiesCount' => $remainingCopies,
            'studentsInDifficultyCount' => $studentsInDifficulty,
            'trackedStudentsCount' => $totalStudentsAcrossAssignments,
            'classAverage' => $classAverage,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'date' => ['required', 'date'],
            'type' => ['required', Rule::in(['devoir', 'examen', 'controle_continu'])],
        ]);

        $assignment = TeacherAssignment::forSchoolContext(auth()->user())
            ->where('teacher_id', auth()->id())
            ->where('classroom_id', $request->classroom_id)
            ->where('subject_id', $request->subject_id)
            ->with('classroom')
            ->first();

        abort_unless($assignment, 403);

        $classroom = Classroom::forSchoolContext(auth()->user())
            ->with('students.user')
            ->findOrFail($assignment->classroom_id);

        $evaluation = Evaluation::create([
            'title' => $request->title,
            'type' => $request->type,
            'school_id' => $classroom->school_id,
            'classroom_id' => $classroom->id,
            'subject_id' => $request->subject_id,
            'academic_year_id' => $classroom->academic_year_id,
            'teacher_id' => auth()->id(),
            'date' => $request->date,
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'evaluation_created',
            'description' => sprintf(
                'Evaluation #%d (%s) created for %s / %s.',
                $evaluation->id,
                $evaluation->type,
                $classroom->name,
                $evaluation->subject?->name ?? 'subject'
            ),
        ]);

        if ($request->expectsJson()) {
            return response()->json($evaluation);
        }

        return redirect()
            ->route('teacher.dashboard')
            ->with('status', 'Evaluation created successfully.');
    }

    public function publish(int $id)
    {
        $evaluation = Evaluation::forSchoolContext(auth()->user())
            ->where('teacher_id', auth()->id())
            ->with(['classroom.students.user.parents', 'subject'])
            ->findOrFail($id);

        $evaluation->update(['is_published' => true]);

        foreach ($evaluation->classroom?->students ?? collect() as $studentProfile) {
            $student = $studentProfile->user;

            if (! $student) {
                continue;
            }

            $recipients = collect([$student])->merge($student->parents)->unique('id');

            foreach ($recipients as $recipient) {
                Notification::create([
                    'user_id' => $recipient->id,
                    'title' => 'New published evaluation',
                    'body' => sprintf(
                        '%s published %s for %s in %s.',
                        auth()->user()->name,
                        $evaluation->title,
                        $student->name,
                        $evaluation->subject?->name ?? 'a subject'
                    ),
                ]);
            }
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'evaluation_published',
            'description' => sprintf('Evaluation #%d published.', $evaluation->id),
        ]);

        return redirect()
            ->route('teacher.grades.show', $evaluation->id)
            ->with('status', 'Evaluation published successfully.');
    }

    public function lock(int $id)
    {
        $evaluation = Evaluation::forSchoolContext(auth()->user())
            ->where('teacher_id', auth()->id())
            ->findOrFail($id);

        $evaluation->update([
            'is_published' => true,
            'is_locked' => true,
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'evaluation_locked',
            'description' => sprintf('Evaluation #%d locked.', $evaluation->id),
        ]);

        return redirect()
            ->route('teacher.grades.show', $evaluation->id)
            ->with('status', 'Evaluation locked successfully.');
    }
}
