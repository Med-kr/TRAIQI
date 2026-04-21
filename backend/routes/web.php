<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Teacher\EvaluationController;
use App\Http\Controllers\Teacher\GradeController;
use App\Http\Controllers\Teacher\GradeCommentController;
use App\Http\Controllers\Admin\ReportController;
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
});

Route::prefix('teacher')->middleware(['auth'])->group(function () {

    Route::get('/evaluations', [EvaluationController::class, 'index']);
    Route::post('/evaluations', [EvaluationController::class, 'store']);

    Route::post('/evaluations/{id}/grades', [GradeController::class, 'store']);
    Route::get('/evaluations/{id}/grades', [GradeController::class, 'show']);

    Route::post('/grades/comments', [GradeCommentController::class, 'store']);

});
Route::prefix('admin')->middleware(['auth'])->group(function () {

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications', [NotificationController::class, 'store']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

});
Route::prefix('admin')->middleware(['auth'])->group(function () {

    Route::get('/reports', [ReportController::class, 'index']);
    Route::get('/reports/average', [ReportController::class, 'averageGrades']);
    Route::get('/reports/classes', [ReportController::class, 'classStats']);

});

Route::middleware(['auth'])->group(function () {

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications', [NotificationController::class, 'store']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

});

require __DIR__ . '/auth.php';
