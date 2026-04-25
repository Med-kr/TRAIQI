<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Parent\ParentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Teacher\EvaluationController;
use App\Http\Controllers\Teacher\GradeCommentController;
use App\Http\Controllers\Teacher\GradeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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
});

Route::prefix('teacher')->middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/dashboard', [EvaluationController::class, 'index'])->name('teacher.dashboard');
    Route::get('/evaluations', [EvaluationController::class, 'index'])->name('teacher.evaluations.index');
    Route::post('/evaluations', [EvaluationController::class, 'store'])->name('teacher.evaluations.store');
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
});

Route::prefix('admin')->middleware(['auth', 'role:administration'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('administration.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('administration.users.index');
    Route::get('/reports', [ReportController::class, 'index'])->name('administration.reports.index');
    Route::get('/reports/average', [ReportController::class, 'averageGrades'])->name('administration.reports.average');
    Route::get('/reports/classes', [ReportController::class, 'classStats'])->name('administration.reports.classes');
});

Route::get('/admin', fn () => redirect()->route('administration.dashboard'))
    ->middleware(['auth', 'role:administration']);

require __DIR__ . '/auth.php';
