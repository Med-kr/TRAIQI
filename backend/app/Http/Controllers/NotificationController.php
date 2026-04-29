<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNotificationRequest;
use App\Models\Notification;
use App\Models\User;
use App\Services\AuditLogService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService,
        protected AuditLogService $auditLogService,
    ) {
    }

    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->when(request()->filled('type'), fn ($query) => $query->where('type', request()->string('type')->toString()))
            ->when(request()->filled('read'), fn ($query) => $query->where('read', request()->boolean('read')))
            ->latest()
            ->get();

        if (request()->expectsJson()) {
            return $notifications;
        }

        return view('portal.notifications', [
            'notifications' => $notifications,
        ]);
    }

    public function store(StoreNotificationRequest $request)
    {
        abort_unless($request->user()->isAdmin(), 403);

        $recipient = User::forSchoolContext($request->user())
            ->findOrFail($request->integer('user_id'));

        $notification = $this->notificationService->sendToUser(
            $recipient,
            $request->string('title')->toString(),
            $request->string('body')->toString(),
            $request->input('type', 'system'),
            $request->input('action_url')
        );

        $this->auditLogService->record(
            $request->user(),
            'notification_created',
            sprintf('Notification #%d sent to user #%d.', $notification->id, $recipient->id)
        );

        return $request->expectsJson()
            ? response()->json($notification, 201)
            : back()->with('status', 'Notification sent successfully.');
    }

    public function markAsRead($id)
    {
        $notif = Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $notif->update(['read' => true]);

        return response()->json(['message' => 'read']);
    }
}
