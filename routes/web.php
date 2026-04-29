<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/library', [\App\Http\Controllers\LibraryController::class, 'index'])->name('library');

    Route::get('/borrowing', [\App\Http\Controllers\PeminjamanController::class, 'index'])->name('borrowing');
    Route::get('/borrowing/create', [\App\Http\Controllers\PeminjamanController::class, 'create'])->name('borrowing.create');
    Route::post('/borrowing', [\App\Http\Controllers\PeminjamanController::class, 'store'])->name('borrowing.store');
    Route::patch('/borrowing/{id}/status', [\App\Http\Controllers\PeminjamanController::class, 'updateStatus'])->name('borrowing.status');

    Route::get('/success', function () {
        return view('success');
    })->name('success');

    // Profile Routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');

    // Admin Specific Routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::resource('kategori', \App\Http\Controllers\KategoriController::class);
        Route::resource('buku', \App\Http\Controllers\BukuController::class);
        Route::get('/report', [\App\Http\Controllers\ReportController::class, 'index'])->name('report.index');
    });
});
