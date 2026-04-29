<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendSchoolMessageRequest;
use App\Models\Notification;
use App\Models\User;
use App\Services\AuditLogService;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService,
        protected AuditLogService $auditLogService,
    ) {
    }

    public function index()
    {
        return Notification::forSchoolContext(auth()->user())
            ->with('user')
            ->latest()
            ->paginate(20);
    }

    public function store(SendSchoolMessageRequest $request)
    {
        $recipients = User::forSchoolContext($request->user())
            ->when($request->filled('role'), fn ($query) => $query->role($request->string('role')->toString()))
            ->get();

        $this->notificationService->sendSchoolMessage(
            $request->user(),
            $recipients,
            $request->string('title')->toString(),
            $request->string('body')->toString(),
            $request->input('action_url')
        );

        $this->auditLogService->record(
            $request->user(),
            'school_message_sent',
            sprintf('School message sent to %d recipients.', $recipients->count())
        );

        return response()->json([
            'message' => 'School message sent successfully.',
            'recipients_count' => $recipients->count(),
        ], 201);
    }

    public function markAsRead($id)
    {
        $notification = Notification::forSchoolContext(auth()->user())->findOrFail($id);
        $notification->update(['read' => true]);

        return response()->json(['message' => 'Marked as read']);
    }
}
