<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScrapeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\ScriptsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\NotificationController;

// Public
Route::get('/', fn () => view('index'));
Route::get('/terms', fn () => view('terms'));
Route::view('/pricing', 'pricing')->name('pricing');
Route::view('/resources', 'resources')->name('resources');
Route::get('/book', [\App\Http\Controllers\BookingController::class, 'show'])->name('book');

// Guest auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [LoginController::class, 'register']);
});

// Auth
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');

    // Lead generation
    Route::get('/scrapes', [ScrapeController::class, 'index'])->name('scrapes.index');
    Route::post('/scrapes', [ScrapeController::class, 'start'])->name('scrapes.start');
Route::get('/scrapes/history',   [ScrapeController::class, 'history'])->name('scrapes.history');
Route::get('/scrapes/downloads', [ScrapeController::class, 'downloads'])->name('scrapes.downloads');

Route::get('/scrapes/{runId}', [ScrapeController::class, 'show'])
    ->whereUuid('runId')            // <- important
    ->name('scrapes.show');

Route::get('/scrapes/{runId}/export', [ScrapeController::class, 'export'])
    ->whereUuid('runId')            // <- important
    ->name('scrapes.export');


    // Content pages
    Route::view('/resources/scripts', 'resources.scripts')->name('resources.scripts');
    Route::view('/resources/tutorials', 'resources.tutorials')->name('resources.tutorials');

    // Cold calling scripts (optional controllers)
    Route::get('/scripts', [ScriptsController::class, 'index'])->name('scripts.index');
    Route::get('/scripts/{slug}', [ScriptsController::class, 'show'])->name('scripts.show');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Admin (prefix and name already include "admin")
Route::prefix('admin')->name('admin.')->middleware(['auth','admin'])->group(function () {
    // Dashboard & marketing — NOTE: no extra "/admin" here
    Route::get('/dashboard', [LoginController::class, 'adminDashboard'])->name('dashboard');
    Route::get('/marketing',  [MarketingController::class, 'edit'])->name('marketing.edit');
    Route::post('/marketing', [MarketingController::class, 'update'])->name('marketing.update');

    // Users
    Route::get('/users',            [UserController::class, 'index'])->name('users.index');
    Route::post('/users',           [UserController::class, 'store'])->name('users.store');
    Route::get('/users/export',     [UserController::class, 'export'])->name('users.export');
    Route::get('/users/{user}',     [UserController::class, 'show'])->name('users.show');
    Route::put('/users/{user}',     [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}',  [UserController::class, 'destroy'])->name('users.destroy');

    // Settings
    Route::get('/settings/general',  [SettingsController::class, 'general'])->name('settings.general');
    Route::get('/settings/security', [SettingsController::class, 'security'])->name('settings.security');
    Route::get('/settings/database', [SettingsController::class, 'database'])->name('settings.database');

    // Analytics & notifications
    Route::get('/analytics',     [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
});
