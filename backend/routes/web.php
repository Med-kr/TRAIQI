<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherAssignmentController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Parent\ParentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Teacher\EvaluationController;
use App\Http\Controllers\Teacher\GradeCommentController;
use App\Http\Controllers\Teacher\GradeController;
use App\Models\Grade;
use App\Models\ReviewRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/locale', function (Request $request) {
    $supportedLocales = array_keys(config('traiqi.locales', []));

    $validated = $request->validate([
        'locale' => ['required', 'string', 'in:' . implode(',', $supportedLocales)],
    ]);

    return response()->json([
        'locale' => $validated['locale'],
    ])->cookie(
        Cookie::forever('traiqi_locale', $validated['locale'])
    );
})->name('locale.switch');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    Route::get('/grades', function () {
        $user = auth()->user();

        $grades = match ($user->primaryRole()) {
            'teacher' => Grade::forSchoolContext($user)
                ->whereHas('evaluation', fn ($query) => $query->where('teacher_id', $user->id))
                ->with(['evaluation.subject', 'evaluation.classroom', 'comments'])
                ->latest()
                ->get(),
            'parent' => Grade::forSchoolContext($user)
                ->whereIn('student_id', $user->children()->pluck('users.id'))
                ->with(['evaluation.subject', 'evaluation.classroom', 'comments'])
                ->latest()
                ->get(),
            'student' => Grade::forSchoolContext($user)
                ->where('student_id', $user->id)
                ->with(['evaluation.subject', 'evaluation.classroom', 'comments'])
                ->latest()
                ->get(),
            default => Grade::forSchoolContext($user)
                ->with(['evaluation.subject', 'evaluation.classroom', 'comments'])
                ->latest()
                ->take(30)
                ->get(),
        };

        return view('portal.grades', [
            'grades' => $grades,
        ]);
    })->name('grades.index');

    Route::get('/revision-requests', function () {
        $user = auth()->user();

        $requests = ReviewRequest::forSchoolContext($user)
            ->with(['grade.evaluation.subject', 'grade.evaluation.classroom', 'student'])
            ->when($user->hasRole('parent'), fn ($query) => $query->whereIn('student_id', $user->children()->pluck('users.id')))
            ->when($user->hasRole('student'), fn ($query) => $query->where('student_id', $user->id))
            ->when($user->hasRole('teacher'), function ($query) use ($user) {
                $query->whereHas('grade.evaluation', fn ($evaluationQuery) => $evaluationQuery->where('teacher_id', $user->id));
            })
            ->latest()
            ->get();

        return view('portal.revision-requests', [
            'reviewRequests' => $requests,
        ]);
    })->name('revision-requests.index');

    Route::view('/opportunities', 'portal.opportunities')->name('opportunities.index');
    Route::view('/portfolio', 'portal.portfolio')->name('portfolio.index');
    Route::view('/help-center', 'portal.help-center')->name('help-center.index');
});

Route::prefix('teacher')->middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/dashboard', [EvaluationController::class, 'index'])->name('teacher.dashboard');
    Route::get('/evaluations', [EvaluationController::class, 'index'])->name('teacher.evaluations.index');
    Route::post('/evaluations', [EvaluationController::class, 'store'])->name('teacher.evaluations.store');
    Route::post('/evaluations/{id}/publish', [EvaluationController::class, 'publish'])->name('teacher.evaluations.publish');
    Route::post('/evaluations/{id}/lock', [EvaluationController::class, 'lock'])->name('teacher.evaluations.lock');
    Route::post('/evaluations/{id}/grades', [GradeController::class, 'store'])->name('teacher.grades.store');
    Route::get('/evaluations/{id}/grades', [GradeController::class, 'show'])->name('teacher.grades.show');
    Route::post('/grades/comments', [GradeCommentController::class, 'store'])->name('teacher.grades.comments.store');
});

Route::prefix('student')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/evaluations', [StudentController::class, 'evaluations'])->name('student.evaluations.index');
});

Route::prefix('parent')->middleware(['auth', 'role:parent'])->group(function () {
    Route::get('/dashboard', [ParentController::class, 'dashboard'])->name('parent.dashboard');
    Route::get('/children/{studentId}/grades', [ParentController::class, 'childGrades'])->name('parent.children.grades');
    Route::post('/review-requests', [ParentController::class, 'storeReviewRequest'])->name('parent.review-requests.store');
});

Route::prefix('admin')->middleware(['auth', 'role:super_admin,school_admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users.index');
    Route::resource('user-management', UserManagementController::class)
        ->except(['show'])
        ->names('admin.user-management');
    Route::get('/imports', [ImportController::class, 'index'])->name('admin.imports.index');
    Route::post('/imports', [ImportController::class, 'store'])->name('admin.imports.store');
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/reports/average', [ReportController::class, 'averageGrades'])->name('admin.reports.average');
    Route::get('/reports/classes', [ReportController::class, 'classStats'])->name('admin.reports.classes');
    Route::resource('schools', SchoolController::class)
        ->except(['show'])
        ->names('admin.schools');
    Route::resource('academic-years', AcademicYearController::class)
        ->except(['show'])
        ->names('admin.academic-years');
    Route::resource('levels', LevelController::class)
        ->except(['show'])
        ->names('admin.levels');
    Route::resource('classrooms', ClassroomController::class)
        ->except(['show'])
        ->names('admin.classrooms');
    Route::resource('subjects', SubjectController::class)
        ->except(['show'])
        ->names('admin.subjects');
    Route::resource('teacher-assignments', TeacherAssignmentController::class)
        ->except(['show'])
        ->names('admin.teacher-assignments');
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('admin.notifications.index');
    Route::post('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'store'])->name('admin.notifications.store');
});

Route::get('/admin', fn () => redirect()->route('admin.dashboard'))
    ->middleware(['auth', 'role:super_admin,school_admin']);

require __DIR__ . '/auth.php';
