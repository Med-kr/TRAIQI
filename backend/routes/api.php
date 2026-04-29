<?php

use App\Http\Controllers\Api\DashboardSummaryApiController;
use App\Http\Controllers\Api\NotificationApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'throttle:60,1'])->prefix('internal')->group(function () {
    Route::get('/dashboard-summary', [DashboardSummaryApiController::class, 'show']);
    Route::get('/notifications', [NotificationApiController::class, 'index']);
});
