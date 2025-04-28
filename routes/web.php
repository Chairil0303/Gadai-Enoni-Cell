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
use App\Http\Controllers\TebusGadaiController;
use App\Http\Controllers\TebusGadaiNasabahController;
use App\Http\Controllers\NasabahPaymentController;
use App\Http\Controllers\PerpanjangGadaiController;
use App\Http\Controllers\PerpanjangGadaiNasabahController;
use App\Http\Controllers\Superadmin\AdminController;




Route::get('/cek-auth', function () {
    return response()->json([
        'user' => auth()->user(),
        'id' => auth()->id(),
        'session' => session()->all(),
    ]);
});


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
        } elseif (auth()->user()->role === 'Nasabah') {
            return redirect()->route('profile');//gua ubah jadi profile
        }elseif (auth()->user()->role === 'staff') {
            return redirect()->route('dashboard.staff');
        }
        // Jika role tidak dikenali, arahkan ke halaman login
        return redirect('/login');
    })->name('dashboard');


    // Route untuk admin
    Route::middleware(RoleMiddleware::class . ':Admin')->group(function () {
        Route::get('/dashboard/admin', function () {
            return view('components.dashboard.admin');
        })->name('dashboard.admin');
    });

    Route::middleware(RoleMiddleware::class . ':Staff')->group(function () {
        Route::get('/dashboard/Staff', function () {
            return view('components.dashboard.staff');
        })->name('dashboard.staff');
    });



    // ketika login user dari nasabah di arahin kesini jadi langsung ke profile
    Route::middleware(['auth', RoleMiddleware::class .':Nasabah'])->prefix('nasabah')->group(function () {
        Route::get('/dashboard', [NasabahController::class, 'show'])->name('profile');
    });

    // profil nasabah
    route::get('/nasabah/profil', [NasabahController::class, 'profil'])->name('nasabah.profil');


        // tebus gadai
    Route::prefix('transaksi_gadai')->group(function () {
        Route::get('/tebus_gadai', [TebusGadaiController::class, 'index'])->name('admin.tebus.index');
        Route::get('/tebus_gadai/cari', [TebusGadaiController::class, 'cari'])->name('admin.tebus.cari');
        Route::post('/tebus_gadai/{noBon}', [TebusGadaiController::class, 'tebus'])->name('admin.tebus.proses');
    });

      // tebus gadaiNasabah
        Route::middleware(['auth'])->prefix('nasabah')->group(function () {
        Route::get('/cari', [TebusGadaiNasabahController::class, 'cari'])->name('tebus.cari');
        Route::get('/konfirmasi/{no_bon}', [TebusGadaiNasabahController::class, 'konfirmasi'])->name('nasabah.konfirmasi');
        Route::post('/tebus/{no_bon}', [TebusGadaiNasabahController::class, 'tebus'])->name('tebus.tebus');
    });
    // Perpanjang gadai Nasabah Start
        Route::middleware(['auth'])->prefix('nasabah')->group(function () {
            Route::get('/nasabah/perpanjang-gadai/detail/{no_bon}', [PerpanjangGadaiNasabahController::class, 'details'])->name('nasabah.perpanjang.details');
            // Route::get('/perpanjang-gadai/detai/{no_bon}l', [PerpanjangGadaiNasabahController::class, 'Details'])->name('nasabah.perpanjang.details');
            Route::get('/perpanjang-gadai/konfirmasi', [PerpanjangGadaiNasabahController::class, 'konfirmasi'])->name('nasabah.konfirmasi.Perpanjang');
            Route::post('process-perpanjang-payment', [PerpanjangGadaiNasabahController::class, 'processPayment']);

        });

    // Perpanjang gadai nasabah End


    // perpanjang gadai
    Route::get('/perpanjang-gadai/create', [PerpanjangGadaiController::class, 'create'])->name('perpanjang_gadai.create');
    Route::post('/perpanjang-gadai/submit', [PerpanjangGadaiController::class, 'submitForm'])->name('perpanjang_gadai.submit'); // <- proses form
    Route::get('/perpanjang-gadai/konfirmasi', [PerpanjangGadaiController::class, 'konfirmasi'])->name('perpanjang_gadai.konfirmasi'); // <- tampil konfirmasi
    Route::post('/perpanjang-gadai/store', [PerpanjangGadaiController::class, 'store'])->name('perpanjang_gadai.store'); // <- simpan akhir


    Route::middleware(RoleMiddleware::class . ':Nasabah')->group(function () {
        Route::get('/dashboard/nasabah', function () {
            return view('components.dashboard.nasabah');
        })->name('dashboard.Nasabah');
    });

    // Route untuk superadmin
    Route::middleware(RoleMiddleware::class . ':Superadmin')->group(function () {
        Route::get('/dashboard/superadmin', function () {
            return view('components.dashboard.superadmin');
        })->name('dashboard.superadmin');
    });

    // cabang controller
    // Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->group(function () {
    //     Route::resource('cabang', \App\Http\Controllers\Superadmin\CabangController::class);
    // });
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


    //Route::resource() secara otomatis membuat semua route RESTful (index, create, store, show, edit, update, destroy).
    // crud admin di superadmin
    Route::prefix('superadmin')
        ->middleware(['auth', RoleMiddleware::class . ':Superadmin'])
        ->name('superadmin.')
        ->group(function () {
            Route::resource('admins', \App\Http\Controllers\Superadmin\AdminController::class);
            Route::resource('kategori-barang', \App\Http\Controllers\Superadmin\KategoriBarangController::class);
            Route::resource('cabang', CabangController::class);
            Route::resource('bunga-tenor', \App\Http\Controllers\Superadmin\BungaTenorController::class);
    });




    Route::resource('barang_gadai', BarangGadaiController::class);
    // Menambahkan route untuk halaman barang_gadai.index
    Route::get('/barang_gadai', [BarangGadaiController::class, 'index'])->name('barang_gadai.index');
    Route::get('/barang_gadai/edit',[BarangGadaiController::class,'edit'])->name('barang_gadai.update');

    // route untuk view
    Route::resource('nasabah', NasabahController::class);
    Route::resource('barang_gadai', BarangGadaiController::class);
    Route::resource('transaksi_gadai', TransaksiGadaiController::class);
    Route::resource('lelang_barang', LelangBarangController::class);
    Route::resource('laporan', LaporanController::class);
    Route::resource('notifikasi', NotifikasiController::class);


    // Route untuk profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // untuk terima gadai
    Route::post('/gadai/store', [GadaiController::class, 'store'])->name('gadai.store');

    // buat kategori
    Route::get('/gadai/create', [GadaiController::class, 'create'])->name('gadai.create');

});
// Route::post('/midtrans/webhook', [NasabahPaymentController::class, 'handleNotificationTEST']);

Route::post('/nasabah/process-tebus-payment', [NasabahPaymentController::class, 'processPaymentJson']);
Route::post('/nasabah/cancel-payment', [NasabahPaymentController::class, 'cancelPayment']);
Route::post('/nasabah/validate-pending-payment', [NasabahPaymentController::class, 'validatePending']);

require __DIR__.'/auth.php';
