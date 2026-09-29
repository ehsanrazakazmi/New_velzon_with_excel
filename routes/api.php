<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Consumed by the Next.js frontend in admin-portal-web.
|
| Authentication is Sanctum in SPA mode, so these are session-cookie routes,
| not bearer-token ones. The browser must fetch /sanctum/csrf-cookie once
| before its first POST; everything after that rides on the session cookie.
|
| Authorization stays where it already lives on the web routes - named on the
| route with `can:`, never buried in a controller body.
|
*/

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:6,1')
    ->name('api.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'me'])->name('api.user');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('api.dashboard.stats');
});
