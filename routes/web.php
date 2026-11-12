<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// Public profile page
Route::get('/', [PublicController::class, 'home'])->name('home');

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin panel (login required)
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile');

    $types = 'social|education|documentary|gallery|slide';
    Route::post('/{type}', [AdminController::class, 'store'])->where('type', $types)->name('store');
    Route::delete('/{type}/{id}', [AdminController::class, 'destroy'])->where('type', $types)->name('destroy');
});
