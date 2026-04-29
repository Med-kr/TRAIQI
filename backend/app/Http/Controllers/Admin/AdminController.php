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
use Illuminate\Http\Request;

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

    public function logs(Request $request)
    {
        $filters = $request->validate([
            'user' => ['nullable', 'string', 'max:120'],
            'action' => ['nullable', 'string', 'max:120'],
            'date' => ['nullable', 'date'],
        ]);

        $logs = AuditLog::query()
            ->with('user')
            ->when($filters['user'] ?? null, function ($query, string $user) {
                $query->whereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', "%{$user}%"));
            })
            ->when($filters['action'] ?? null, fn ($query, string $action) => $query->where('action', 'like', "%{$action}%"))
            ->when($filters['date'] ?? null, fn ($query, string $date) => $query->whereDate('created_at', $date))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $todayLogs = AuditLog::query()->whereDate('created_at', today());

        return view('admin.logs.index', [
            'logs' => $logs,
            'filters' => $filters,
            'todayCount' => (clone $todayLogs)->count(),
            'importsTodayCount' => (clone $todayLogs)->where('action', 'like', '%import%')->count(),
            'sensitiveTodayCount' => (clone $todayLogs)
                ->where(function ($query) {
                    $query->where('action', 'like', '%password%')
                        ->orWhere('action', 'like', '%delete%')
                        ->orWhere('action', 'like', '%user%')
                        ->orWhere('action', 'like', '%login%');
                })
                ->count(),
            'usersWithLogsCount' => AuditLog::query()->whereNotNull('user_id')->distinct('user_id')->count('user_id'),
        ]);
    }
}
