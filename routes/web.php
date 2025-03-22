<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\Auth\LoginController;

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

    // Route untuk admin (batasi akses untuk Superadmin)
    Route::get('/dashboard/admin', function () {
        if (auth()->user()->role === 'Superadmin') {
            abort(403, 'Unauthorized action.');
        }
        return view('components.dashboard.admin');
    })->name('dashboard.admin');

    // Route untuk superadmin (batasi akses untuk Admin)
    Route::get('/dashboard/superadmin', function () {
        if (auth()->user()->role === 'Admin') {
            abort(403, 'Unauthorized action.');
        }
        return view('components.dashboard.superadmin');
    })->name('dashboard.superadmin');

    // Route untuk profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__.'/auth.php';
