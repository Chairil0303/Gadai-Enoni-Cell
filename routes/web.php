<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\RoleMiddleware;



Route::get('/', function () {
    return redirect('/login');
});


// Route untuk login
Route::post('/login', [LoginController::class, 'login'])->name('login');

// Redirect dashboard berdasarkan role
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'Superadmin') {
            return redirect()->route('dashboard.superadmin');
        } elseif (auth()->user()->role === 'Admin') {
            return redirect()->route('dashboard.admin');
        }
        return view('dashboard');
    })->name('dashboard');

    // Route untuk admin
    Route::middleware(RoleMiddleware::class . ':Admin')->group(function () {
        Route::get('/dashboard/admin', function () {
            return view('components.dashboard.admin');
        })->name('dashboard.admin');
    });

    // Route untuk superadmin
    Route::middleware(RoleMiddleware::class . ':Superadmin')->group(function () {
        Route::get('/dashboard/superadmin', function () {
            return view('components.dashboard.superadmin');
        })->name('dashboard.superadmin');
    });

    // Route untuk profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
