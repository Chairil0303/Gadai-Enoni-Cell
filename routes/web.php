<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\BarangGadaiController;
use App\Http\Controllers\TransaksiGadaiController;
use App\Http\Controllers\LelangBarangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\Superadmin\CabangController;
use App\Http\Controllers\GadaiController;

Route::get('/', function () {
    return redirect('/login');
});

// Route untuk login
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'Superadmin') {
            return redirect()->route('dashboard.superadmin');
        } elseif (auth()->user()->role === 'Admin') {
            return redirect()->route('dashboard.admin');
        } elseif (auth()->user()->role === 'Nasabah') {
            return redirect()->route('dashboard.nasabah');
        }
        return redirect('/login');
    })->name('dashboard');

    Route::get('/dashboard/nasabah', function () {
        return view('components.dashboard_nasabah.index');
    })->name('dashboard.nasabah');

    Route::get('/nasabah/profile', [NasabahController::class, 'myProfile'])->name('nasabah.profile');

    // Route untuk Admin
    Route::middleware(RoleMiddleware::class . ':Admin')->group(function () {
        Route::get('/dashboard/admin', function () {
            return view('components.dashboard.admin');
        })->name('dashboard.admin');
    });

    Route::get('/barang_gadai/create', [BarangGadaiController::class, 'create'])->name('transaksi-gadai.create');

    Route::prefix('admin')->group(function () {
        Route::get('/transaksi_gadai/terima_gadai', [TransaksiGadaiController::class, 'create'])->name('admin.transaksi_gadai.create');
    });

    Route::middleware(['auth', RoleMiddleware::class .':Nasabah'])->prefix('nasabah')->group(function () {
        Route::get('/profile', [NasabahController::class, 'show'])->name('profile');
    });

    Route::middleware(RoleMiddleware::class . ':Nasabah')->group(function () {
        Route::get('/dashboard/nasabah', function () {
            return view('components.dashboard.nasabah');
        })->name('dashboard.Nasabah');
    });

    Route::middleware(RoleMiddleware::class . ':Superadmin')->group(function () {
        Route::get('/dashboard/superadmin', function () {
            return view('components.dashboard.superadmin');
        })->name('dashboard.superadmin');

        Route::resource('superadmin/cabang', CabangController::class);
    });

    Route::resource('barang_gadai', BarangGadaiController::class);
    Route::resource('nasabah', NasabahController::class);
    Route::resource('transaksi_gadai', TransaksiGadaiController::class);
    Route::resource('lelang_barang', LelangBarangController::class);
    Route::resource('laporan', LaporanController::class);
    Route::resource('notifikasi', NotifikasiController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/gadai/store', [GadaiController::class, 'store'])->name('gadai.store');
    Route::get('/gadai/create', [GadaiController::class, 'create'])->name('gadai.create');
});

require __DIR__.'/auth.php';
