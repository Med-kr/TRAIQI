<?php

namespace App\Http\Controllers\Api;

use App\Models\Classroom;
use App\Models\Evaluation;
use App\Models\Grade;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardSummaryApiController extends ApiController
{
    public function show(Request $request)
    {
        $user = $request->user();

        return $this->success([
            'role' => $user->primaryRole(),
            'users_count' => User::forSchoolContext($user)->count(),
            'classes_count' => Classroom::forSchoolContext($user)->count(),
            'evaluations_count' => Evaluation::forSchoolContext($user)->count(),
            'grades_count' => Grade::forSchoolContext($user)->count(),
            'unread_notifications_count' => Notification::where('user_id', $user->id)->where('read', false)->count(),
        ]);
    }
}
