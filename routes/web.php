<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\ProfileController;

// Game Routes
Route::get('/', [GameController::class, 'index'])->name('game.index');

// Game actions that require authentication
Route::middleware(['auth'])->group(function () {
    Route::post('/game/update', [GameController::class, 'update'])->name('game.update');
    Route::post('/game/buy-attempts', [GameController::class, 'buyAttempts'])->name('game.buyAttempts');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/admin/increase-attempts/{user}', [AdminController::class, 'increaseAttempts'])->name('admin.increaseAttempts');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// Remove default GET login and register routes for users
Route::get('/login', function () {
    return redirect('/');
})->name('login');

Route::get('/register', function () {
    return redirect('/');
})->name('register');

// POST routes for user login and registration (used by modals)
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

// Admin login routes
Route::get('/admin/login', [AdminAuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('admin.login');

Route::post('/admin/login', [AdminAuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('admin.login');

// Admin logout route
Route::post('/admin/logout', [AdminAuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('admin.logout');

// Logout route for users
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
