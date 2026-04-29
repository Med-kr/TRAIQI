<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Evaluation;
use App\Models\Notification;
use Illuminate\Support\Collection;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $student = $user->studentProfile;

        $grades = $this->studentGrades();
        $evaluations = $this->classroomEvaluations();
        $notifications = $this->studentNotifications();

        return view('student.dashboard', [
            'student' => $student,
            'grades' => $grades,
            'evaluations' => $evaluations,
            'averageGrade' => $grades->avg('value'),
            'passRate' => $grades->count() > 0 ? round(($grades->where('value', '>=', 10)->count() / $grades->count()) * 100, 2) : 0,
            'unreadNotificationsCount' => $notifications->where('read', false)->count(),
            'timetableSubjects' => $student?->classroom?->subjects()->orderBy('name')->get() ?? collect(),
        ]);
    }

    public function grades()
    {
        $grades = $this->studentGrades();

        return view('student.grades.index', [
            'grades' => $grades,
            'averageGrade' => $grades->avg('value'),
            'subjects' => $this->subjectAverages($grades),
        ]);
    }

    public function progress()
    {
        $grades = $this->studentGrades();

        return view('student.progress.index', [
            'grades' => $grades,
            'averageGrade' => $grades->avg('value'),
            'passRate' => $grades->count() > 0 ? round(($grades->where('value', '>=', 10)->count() / $grades->count()) * 100, 2) : 0,
            'subjects' => $this->subjectAverages($grades),
        ]);
    }

    public function schedule()
    {
        $student = auth()->user()->studentProfile;

        return view('student.schedule.index', [
            'student' => $student,
            'timetableSubjects' => $student?->classroom?->subjects()->orderBy('name')->get() ?? collect(),
        ]);
    }

    public function notifications()
    {
        $notifications = $this->studentNotifications();

        return view('student.notifications.index', [
            'notifications' => $notifications,
            'unreadNotificationsCount' => $notifications->where('read', false)->count(),
        ]);
    }

    public function evaluations()
    {
        $student = auth()->user()->studentProfile;

        if (! $student?->classroom_id) {
            return collect();
        }

        return Evaluation::forSchoolContext(auth()->user())
            ->where('classroom_id', $student->classroom_id)
            ->with('subject')
            ->get();
    }

    private function studentGrades(): Collection
    {
        return Grade::forSchoolContext(auth()->user())
            ->where('student_id', auth()->id())
            ->with(['evaluation.subject', 'evaluation.classroom', 'comments.teacher'])
            ->latest()
            ->get();
    }

    private function classroomEvaluations(): Collection
    {
        $student = auth()->user()->studentProfile;

        if (! $student?->classroom_id) {
            return collect();
        }

        return Evaluation::forSchoolContext(auth()->user())
            ->where('classroom_id', $student->classroom_id)
            ->with('subject')
            ->latest('date')
            ->get();
    }

    private function studentNotifications(): Collection
    {
        return Notification::where('user_id', auth()->id())
            ->latest()
            ->get();
    }

    private function subjectAverages(Collection $grades): Collection
    {
        return $grades
            ->groupBy(fn ($grade) => $grade->evaluation?->subject?->name ?? 'Matière')
            ->map(fn ($subjectGrades, $subject) => [
                'name' => $subject,
                'avg' => $subjectGrades->avg('value'),
                'count' => $subjectGrades->count(),
            ])
            ->sortByDesc('avg')
            ->values();
    }
}
