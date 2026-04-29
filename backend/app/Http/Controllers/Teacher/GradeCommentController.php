<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\GradeComment;
use App\Models\Grade;
use App\Models\Notification;
use Illuminate\Http\Request;

class GradeCommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'grade_id' => ['required', 'integer'],
            'comment' => ['required', 'string'],
        ]);

        $grade = Grade::forSchoolContext(auth()->user())
            ->whereHas('evaluation', fn ($query) => $query->where('teacher_id', auth()->id()))
            ->findOrFail($request->grade_id);

        $comment = GradeComment::create([
            'grade_id' => $grade->id,
            'teacher_id' => auth()->id(),
            'comment' => $request->comment,
        ]);

        $student = $grade->student()->with('parents')->first();

        if ($student) {
            $recipients = collect([$student])->merge($student->parents)->unique('id');

            foreach ($recipients as $recipient) {
                Notification::create([
                    'user_id' => $recipient->id,
                    'title' => 'New teacher comment',
                    'body' => sprintf(
                        '%s added feedback for %s in %s.',
                        auth()->user()->name,
                        $student->name,
                        $grade->evaluation?->subject?->name ?? 'an evaluation'
                    ),
                ]);
            }
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'grade_comment_added',
            'description' => sprintf('Comment added on grade #%d.', $grade->id),
        ]);

        if ($request->expectsJson()) {
            return $comment;
        }

        return redirect()
            ->back()
            ->with('status', 'Comment added successfully.');
    }
}
