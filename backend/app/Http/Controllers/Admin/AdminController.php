<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Grade;
use App\Models\Notification;
use App\Models\ReviewRequest;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $users = User::with('roles')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboards.admin', [
            'users' => $users,
            'usersCount' => User::count(),
            'evaluationsCount' => Evaluation::count(),
            'gradesCount' => Grade::count(),
            'notificationsCount' => Notification::count(),
            'reviewRequestsCount' => ReviewRequest::count(),
        ]);
    }

    public function users()
    {
        return view('dashboards.admin-users', [
            'users' => User::with('roles')->latest()->get(),
        ]);
    }
}
