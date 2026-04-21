<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // notifications ديال user الحالي
    public function index()
    {
        return Notification::where('user_id', auth()->id())
            ->latest()
            ->get();
    }

    // create notification (system/internal)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'message' => 'required',
            'user_id' => 'required'
        ]);

        return Notification::create([
            'title' => $request->title,
            'message' => $request->message,
            'user_id' => $request->user_id,
        ]);
    }

    // mark as read
    public function markAsRead($id)
    {
        $notif = Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $notif->update(['read_at' => now()]);

        return response()->json(['message' => 'read']);
    }
}
