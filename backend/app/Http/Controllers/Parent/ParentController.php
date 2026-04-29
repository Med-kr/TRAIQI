<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Grade;
use App\Models\Notification;
use App\Models\ReviewRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ParentController extends Controller
{
    public function dashboard()
    {
        $parent = auth()->user();
        $childrenWithGrades = $this->childrenWithGrades($parent);
        $allGrades = $childrenWithGrades->flatMap(fn ($item) => $item['grades']);
        $notifications = $this->parentNotifications($parent)->take(5);
        $reviewRequests = $this->parentReviewRequests($parent)->take(5);

        return view('parent.dashboard', [
            'childrenWithGrades' => $childrenWithGrades,
            'childrenCount' => $childrenWithGrades->count(),
            'average' => $allGrades->avg('value'),
            'gradesCount' => $allGrades->count(),
            'notifications' => $notifications,
            'reviewRequests' => $reviewRequests,
            'unreadNotificationsCount' => $this->unreadNotificationsCount($parent),
        ]);
    }

    public function children()
    {
        $parent = auth()->user();
        $childrenWithGrades = $this->childrenWithGrades($parent);
        $allGrades = $childrenWithGrades->flatMap(fn ($item) => $item['grades']);

        return view('parent.children.index', [
            'childrenWithGrades' => $childrenWithGrades,
            'childrenCount' => $childrenWithGrades->count(),
            'average' => $allGrades->avg('value'),
        ]);
    }

    public function grades()
    {
        $parent = auth()->user();
        $childrenWithGrades = $this->childrenWithGrades($parent);
        $grades = $childrenWithGrades
            ->flatMap(fn ($item) => $item['grades']->map(fn ($grade) => [
                'child' => $item['child'],
                'grade' => $grade,
            ]))
            ->sortByDesc(fn ($item) => $item['grade']->created_at)
            ->values();

        return view('parent.grades.index', [
            'childrenWithGrades' => $childrenWithGrades,
            'grades' => $grades,
            'average' => $grades->avg(fn ($item) => $item['grade']->value),
        ]);
    }

    public function messages()
    {
        $parent = auth()->user();
        $notifications = $this->parentNotifications($parent);

        return view('parent.messages.index', [
            'notifications' => $notifications,
            'unreadNotificationsCount' => $this->unreadNotificationsCount($parent),
        ]);
    }

    public function appointments()
    {
        $parent = auth()->user();

        return view('parent.appointments.index', [
            'childrenWithGrades' => $this->childrenWithGrades($parent),
            'reviewRequests' => $this->parentReviewRequests($parent),
        ]);
    }

    public function storeReviewRequest(Request $request)
    {
        $parent = $request->user();

        $validated = $request->validate([
            'student_id' => ['required', 'integer'],
            'grade_id' => ['required', 'integer'],
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $child = $parent->children()
            ->forSchoolContext($parent)
            ->findOrFail($validated['student_id']);

        $grade = Grade::forSchoolContext($parent)
            ->where('student_id', $child->id)
            ->with(['evaluation.teacher'])
            ->findOrFail($validated['grade_id']);

        $existingPendingRequest = ReviewRequest::forSchoolContext($parent)
            ->where('grade_id', $grade->id)
            ->where('student_id', $child->id)
            ->where('status', 'pending')
            ->first();

        if ($existingPendingRequest) {
            return redirect()
                ->route('parent.dashboard')
                ->with('status', 'A pending review request already exists for this grade.');
        }

        $reviewRequest = ReviewRequest::create([
            'grade_id' => $grade->id,
            'student_id' => $child->id,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        $recipients = collect([$grade->evaluation?->teacher])
            ->filter()
            ->merge(
                User::forSchoolContext($parent)
                    ->role('school_admin')
                    ->get()
            )
            ->unique('id');

        foreach ($recipients as $recipient) {
            Notification::create([
                'user_id' => $recipient->id,
                'title' => 'New parent review request',
                'body' => sprintf(
                    '%s requested a meeting about %s\'s grade in %s.',
                    $parent->name,
                    $child->name,
                    $grade->evaluation?->subject?->name ?? 'an evaluation'
                ),
            ]);
        }

        AuditLog::create([
            'user_id' => $parent->id,
            'action' => 'review_request_created',
            'description' => sprintf(
                'Parent %s submitted review request #%d for student %s and grade #%d.',
                $parent->name,
                $reviewRequest->id,
                $child->name,
                $grade->id
            ),
        ]);

        return redirect()
            ->route('parent.dashboard')
            ->with('status', 'Review request submitted successfully.');
    }

    public function childGrades($studentId)
    {
        $child = auth()->user()
            ->children()
            ->forSchoolContext(auth()->user())
            ->findOrFail($studentId);

        return Grade::forSchoolContext(auth()->user())
            ->where('student_id', $child->id)
            ->with(['evaluation.subject'])
            ->get();
    }

    private function childrenWithGrades(User $parent): Collection
    {
        $children = $parent->children()->forSchoolContext($parent)
            ->with(['studentProfile.classroom', 'roles'])
            ->get();

        return $children->map(function ($child) use ($parent) {
            $grades = Grade::forSchoolContext($parent)
                ->where('student_id', $child->id)
                ->with(['evaluation.subject', 'evaluation.classroom', 'comments.teacher', 'reviewRequests'])
                ->latest()
                ->get();

            $reviewRequests = ReviewRequest::forSchoolContext($parent)
                ->where('student_id', $child->id)
                ->with(['grade.evaluation.subject'])
                ->latest()
                ->get();

            return [
                'child' => $child,
                'grades' => $grades,
                'average' => $grades->avg('value'),
                'lastGrade' => $grades->first(),
                'progress' => $grades->count() > 1
                    ? round(($grades->take(3)->avg('value') ?? 0) - ($grades->slice(3, 3)->avg('value') ?? 0), 2)
                    : null,
                'reviewRequests' => $reviewRequests,
            ];
        });
    }

    private function parentNotifications(User $parent): Collection
    {
        return Notification::where('user_id', $parent->id)
            ->latest()
            ->get();
    }

    private function parentReviewRequests(User $parent): Collection
    {
        return ReviewRequest::forSchoolContext($parent)
            ->whereIn('student_id', $parent->children()->pluck('users.id'))
            ->with(['grade.evaluation.subject', 'grade.evaluation.teacher', 'student'])
            ->latest()
            ->get();
    }

    private function unreadNotificationsCount(User $parent): int
    {
        return Notification::where('user_id', $parent->id)
            ->where('read', false)
            ->count();
    }
}
