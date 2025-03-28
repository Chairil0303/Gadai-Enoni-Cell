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

// Redirect dashboard berdasarkan role
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'Superadmin') {
            return redirect()->route('dashboard.superadmin');
        } elseif (auth()->user()->role === 'Admin') {
            return redirect()->route('dashboard.admin');
        }elseif (auth()->user()->role === 'Nasabah') {
            return redirect()->route('dashboard.nasabah');
        }
        return view('dashboard');
    })->name('dashboard');


    Route::get('/dashboard/nasabah', function () {
        return view('components.dashboard_nasabah.index');
    })->name('dashboard.nasabah');

    Route::middleware(['auth'])->group(function () {
        Route::get('/nasabah/profile', [NasabahController::class, 'myProfile'])->name('nasabah.profile');
    });


    // Route untuk admin
    Route::middleware(RoleMiddleware::class . ':Admin')->group(function () {
        Route::get('/dashboard/admin', function () {
            return view('components.dashboard.admin');
        })->name('dashboard.admin');
    });

    
    Route::get('/transaksi_gadai/create', [BarangGadaiController::class, 'create'])->name('transaksi-gadai.create');

    Route::prefix('admin')->group(function () {
        Route::get('/transaksi_gadai/terima_gadai', [TransaksiGadaiController::class, 'create'])->name('admin.transaksi_gadai.create');
    });

    // Route untuk superadmin
    Route::middleware(RoleMiddleware::class . ':Superadmin')->group(function () {
        Route::get('/dashboard/superadmin', function () {
            return view('components.dashboard.superadmin');
        })->name('dashboard.superadmin');
    });

    // cabang controller
    Route::middleware(['auth', 'role:Superadmin'])->prefix('superadmin')->group(function () {
        Route::resource('cabang', \App\Http\Controllers\Superadmin\CabangController::class);
    });
    Route::middleware(['auth', 'role:Admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard/{id}', [App\Http\Controllers\Admin\DashboardController::class, 'index']);
    });


    Route::prefix('superadmin')->group(function () {
        Route::get('/nasabah', [NasabahController::class, 'index'])->name('superadmin.nasabah.index');
        Route::get('/nasabah/create', [NasabahController::class, 'create'])->name('superadmin.nasabah.create');
        Route::post('/nasabah', [NasabahController::class, 'store'])->name('superadmin.nasabah.store');
        Route::get('/nasabah/{id}/edit', [NasabahController::class, 'edit'])->name('superadmin.nasabah.edit');
        Route::put('/nasabah/{id}', [NasabahController::class, 'update'])->name('superadmin.nasabah.update');
        Route::delete('/nasabah/{id}', [NasabahController::class, 'destroy'])->name('superadmin.nasabah.destroy');
});


Route::resource('barang_gadai', BarangGadaiController::class);
    // route untuk view
    Route::resource('nasabah', NasabahController::class);
    Route::resource('barang_gadai', BarangGadaiController::class);
    Route::resource('transaksi_gadai', TransaksiGadaiController::class);
    Route::resource('lelang_barang', LelangBarangController::class);
    Route::resource('laporan', LaporanController::class);
    Route::resource('notifikasi', NotifikasiController::class);


    
    // Route::post('/barang-gadai/store', [BarangGadaiController::class, 'store'])->name('barang_gadai.store');



    // route superadmin


    Route::middleware('auth')->group(function () {
        Route::get('/superadmin/cabang', [CabangController::class, 'index'])->name('cabang.index');
    });
    Route::middleware('auth')->prefix('superadmin')->group(function () {
        Route::get('/cabang/create', [CabangController::class, 'create'])->name('superadmin.cabang.create');
    });
    Route::middleware('auth')->prefix('superadmin')->group(function () {
        Route::get('cabang/{cabang}/edit', [CabangController::class, 'edit'])->name('superadmin.cabang.edit');
        Route::put('cabang/{cabang}', [CabangController::class, 'update'])->name('superadmin.cabang.update');
    });

    Route::middleware('auth')->prefix('superadmin')->group(function () {
    Route::post('/cabang', [CabangController::class, 'store'])->name('superadmin.cabang.store');
    });
    Route::middleware('auth')->group(function () {
        Route::delete('/superadmin/cabang/{cabang}', [CabangController::class, 'destroy'])->name('cabang.destroy');
    });

    // Route untuk profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    // untuk terima gadai
    Route::post('/gadai/store', [GadaiController::class, 'store'])->name('gadai.store');

    // buat kategori
    Route::get('/gadai/create', [GadaiController::class, 'create'])->name('gadai.create');




});



require __DIR__.'/auth.php';
