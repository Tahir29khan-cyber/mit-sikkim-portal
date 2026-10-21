<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\Auth\LoginPageController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\AdminDashboardController;

// Redirect root to student login or syllabus
Route::get('/', fn () => redirect()->route('login'));

// Student Auth
Route::get('/login', [LoginPageController::class, 'student'])->name('login');
Route::post('/login', [StudentAuthController::class, 'login']);

// Admin Auth
Route::get('/admin/login', [LoginPageController::class, 'admin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Protected Student Routes
Route::middleware('student.auth')->group(function () {
    Route::get('/syllabus', [PortalController::class, 'syllabus'])->name('syllabus');
    Route::get('/theory-paper', [PortalController::class, 'theory'])->name('theory');
    Route::get('/mcq', [PortalController::class, 'mcq'])->name('mcq');
    Route::post('/logout', [StudentAuthController::class, 'logout'])->name('logout');
});

// Protected Admin Routes
Route::middleware('admin.auth')->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/settings', [AdminDashboardController::class, 'update'])->name('admin.settings.update');
});

// Public Routes
Route::get('/contact', [PortalController::class, 'contact'])->name('contact');
Route::get('/about', [PortalController::class, 'about'])->name('about');