<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Grade;
use App\Models\Notification;
use App\Models\ReviewRequest;
use App\Models\User;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function dashboard()
    {
        $parent = auth()->user();

        $children = $parent->children()->forSchoolContext($parent)
            ->with([
                'studentProfile.classroom',
                'roles',
            ])
            ->get();

        $childrenWithGrades = $children->map(function ($child) {
            $grades = Grade::where('student_id', $child->id)
                ->with(['evaluation.subject', 'comments.teacher', 'reviewRequests'])
                ->latest()
                ->get();

            $reviewRequests = ReviewRequest::forSchoolContext(auth()->user())
                ->where('student_id', $child->id)
                ->with(['grade.evaluation.subject'])
                ->latest()
                ->get();

            return [
                'child' => $child,
                'grades' => $grades,
                'average' => $grades->avg('value'),
                'progress' => $grades->count() > 1
                    ? round(($grades->take(3)->avg('value') ?? 0) - ($grades->slice(3, 3)->avg('value') ?? 0), 2)
                    : null,
                'reviewRequests' => $reviewRequests,
            ];
        });

        return view('parent.dashboard', [
            'childrenWithGrades' => $childrenWithGrades,
            'childrenCount' => $children->count(),
            'unreadNotificationsCount' => Notification::where('user_id', $parent->id)->where('read', false)->count(),
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
}
