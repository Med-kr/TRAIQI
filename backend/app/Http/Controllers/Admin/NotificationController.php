<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    // كل notifications
    public function index()
    {
        return Notification::latest()->get();
    }

    // إنشاء notification
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'message' => 'required',
            'user_id' => 'nullable'
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
        $notification = Notification::findOrFail($id);
        $notification->update(['read_at' => now()]);

        return response()->json(['message' => 'Marked as read']);
    }
}
