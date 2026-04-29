<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Grade;
use App\Models\Import;
use App\Models\Notification;
use App\Models\ReviewRequest;
use App\Models\School;
use App\Models\AuditLog;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $currentUser = auth()->user();
        $scopedUsers = User::forSchoolContext($currentUser);

        $users = (clone $scopedUsers)
            ->with('roles')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', [
            'users' => $users,
            'usersCount' => (clone $scopedUsers)->count(),
            'studentsCount' => (clone $scopedUsers)->role('student')->count(),
            'parentsCount' => (clone $scopedUsers)->role('parent')->count(),
            'teachersCount' => (clone $scopedUsers)->role('teacher')->count(),
            'activeClassesCount' => \App\Models\Classroom::forSchoolContext($currentUser)->count(),
            'evaluationsCount' => Evaluation::forSchoolContext($currentUser)->count(),
            'gradesCount' => Grade::forSchoolContext($currentUser)->count(),
            'notificationsCount' => Notification::forSchoolContext($currentUser)->count(),
            'reviewRequestsCount' => ReviewRequest::forSchoolContext($currentUser)->count(),
            'schoolsCount' => School::forSchoolContext($currentUser)->count(),
            'recentImports' => Import::forSchoolContext($currentUser)->latest()->take(5)->get(),
            'importsCount' => Import::forSchoolContext($currentUser)->count(),
            'recentAuditLogs' => AuditLog::query()->with('user')->latest()->take(8)->get(),
        ]);
    }

    public function users()
    {
        return view('dashboards.admin-users', [
            'users' => User::forSchoolContext(auth()->user())
                ->with('roles')
                ->latest()
                ->get(),
        ]);
    }
}
