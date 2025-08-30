<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScrapeController;
use App\Http\Controllers\LoginController;

// Public routes
Route::get('/', function () {
    return view('index');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [LoginController::class, 'register']);
});

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');
    
    // Scraping routes (protected)
    Route::get('/scrapes', [ScrapeController::class, 'index'])->name('scrapes.index');
    Route::post('/scrapes', [ScrapeController::class, 'start'])->name('scrapes.start');
    Route::get('/scrapes/{runId}', [ScrapeController::class, 'show'])->name('scrapes.show');
    
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Admin routes (require admin tier)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [LoginController::class, 'adminDashboard'])->name('admin.dashboard');
});
