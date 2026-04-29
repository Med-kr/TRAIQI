<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationApiController extends ApiController
{
    public function index(Request $request)
    {
        $notifications = Notification::query()
            ->where('user_id', $request->user()->id)
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->string('type')->toString()))
            ->when($request->filled('read'), fn ($query) => $query->where('read', filter_var($request->string('read')->toString(), FILTER_VALIDATE_BOOL)))
            ->latest()
            ->paginate(20);

        return NotificationResource::collection($notifications);
    }
}
