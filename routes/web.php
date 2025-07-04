<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\BarangGadaiController;
use App\Http\Controllers\TransaksiGadaiController;
use App\Http\Controllers\LelangBarangController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\Superadmin\CabangController;
use App\Http\Controllers\GadaiController;
use App\Http\Controllers\TebusGadaiController;
use App\Http\Controllers\TebusGadaiNasabahController;
use App\Http\Controllers\NasabahPaymentController;
use App\Http\Controllers\PerpanjangGadaiController;
use App\Http\Controllers\PerpanjangGadaiNasabahController;
use App\Http\Controllers\Superadmin\AdminController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\LelangController;
use App\Http\Controllers\WhatsappTemplateController;
use App\Http\Controllers\AdminTermsController;
use App\Http\Controllers\Admin\LaporanKeuanganController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SuperAdminDashboardController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\ActivityController;



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
        }elseif (auth()->user()->role === 'Staf') {
            return redirect()->route('dashboard.staff');
        }
        // Jika role tidak dikenali, arahkan ke halaman login
        return redirect('/login');
    })->name('dashboard');

    Route::get('/dashboard/superadmin', [SuperAdminDashboardController::class, 'index'])->name('dashboard.superadmin');

    Route::middleware(RoleMiddleware::class . ':Admin')->group(function () {
        Route::get('/dashboard/admin', [AdminDashboardController::class, 'index'])->name('dashboard.admin');
    });

    Route::get('/my-profile', [NasabahController::class, 'profile'])->name('nasabah.my-profile');



    Route::middleware(RoleMiddleware::class . ':Staf')->group(function () {
        Route::get('/dashboard/staff', [StaffDashboardController::class, 'index'])->name('dashboard.staff');
    });

    Route::prefix('admin')
    ->middleware('auth')
    ->name('admin.')
    ->group(function () {
        Route::middleware([RoleMiddleware::class . ':Admin'])->group(function () {
            Route::resource('staff', StaffController::class);
            Route::resource('admin/whatsapp-templates', WhatsappTemplateController::class);

            // ✅ Tambahan route untuk fitur laporan
            Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
            Route::get('/laporan/harian', [LaporanController::class, 'harian'])->name('laporan.harian');
            Route::get('/laporan/bulanan', [LaporanController::class, 'bulanan'])->name('laporan.bulanan');
            Route::get('/laporan/keuangan', [LaporanKeuanganController::class, 'index'])->name('laporan.keuangan');
        });
    });

    Route::get('admin/whatsapp_template', [WhatsappTemplateController::class, 'index'])->name('admin.whatsapp_template.index');
    Route::get('admin/whatsapp_template/edit/{id}', [WhatsappTemplateController::class, 'edit'])->name('admin.whatsapp_template.edit');
    Route::put('admin/whatsapp_template/update/{id}', [WhatsappTemplateController::class, 'update'])->name('admin.whatsapp_template.update');
    Route::get('whatsapp_template/create', [WhatsappTemplateController::class, 'create'])->name('admin.whatsapp_template.create');
    Route::post('whatsapp_template', [WhatsappTemplateController::class, 'store'])->name('admin.whatsapp_template.store');
    Route::delete('whatsapp_template/{id}', [WhatsappTemplateController::class, 'destroy'])->name('admin.whatsapp_template.destroy');
    Route::post('/admin/whatsapp-template/{id}/activate', [WhatsappTemplateController::class, 'activate'])->name('admin.whatsapp_template.activate');
    Route::post('/admin/whatsapp-template/{id}/deactivate', [WhatsappTemplateController::class, 'deactivate'])->name('admin.whatsapp_template.deactivate');

        // ketika login user dari nasabah di arahin kesini jadi langsung ke profile
    Route::middleware(['auth', RoleMiddleware::class .':Nasabah'])->prefix('nasabah')->group(function () {
        Route::get('/dashboard', [NasabahController::class, 'show'])->name('profile');
        Route::put('/update-password', [NasabahController::class, 'updatePassword'])->name('nasabah.update-password');

        // Routes untuk alert dan pembayaran pending
        Route::get('/check-pending-payments', [NasabahPaymentController::class, 'checkPendingPayments'])->name('nasabah.check-pending-payments');
        Route::post('/reprocess-payment', [NasabahPaymentController::class, 'reprocessPayment'])->name('nasabah.reprocess-payment');
        Route::get('/check-resumable-payment', [NasabahPaymentController::class, 'checkResumablePayment'])->name('nasabah.check-resumable-payment');
        Route::post('/resume-payment', [NasabahPaymentController::class, 'resumePayment'])->name('nasabah.resume-payment');
        Route::post('/create-new-payment', [NasabahPaymentController::class, 'createNewPayment'])->name('nasabah.create-new-payment');
    });

    // profil nasabah
    route::get('/nasabah/profil', [NasabahController::class, 'profil'])->name('nasabah.profil');

    // Laporan harian dan bulanan
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    });

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

    // admin terms
    Route::get('/admin/terms', [AdminTermsController::class, 'edit'])->name('admin.terms.edit');
Route::post('/admin/terms', [AdminTermsController::class, 'update'])->name('admin.terms.update');
// end

    // nasbaah syaratdanketentuan
    Route::get('/nasabah/syarat-ketentuan', [NasabahController::class, 'showTerms'])->name('nasabah.terms');

    // Route::get('/syarat-ketentuan', [NasabahController::class, 'syaratKetentuan'])->name('syarat.ketentuan');
// end syaratdanketentuan


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

             // Tambahkan route laporan
            Route::get('laporan', [\App\Http\Controllers\Superadmin\LaporanController::class, 'index'])->name('laporan.index');
            Route::get('laporan/detail', [\App\Http\Controllers\Superadmin\LaporanController::class, 'detail'])->name('laporan.detail');

        });




    Route::resource('barang_gadai', BarangGadaiController::class);
    // Menambahkan route untuk halaman barang_gadai.index
    Route::get('/barang_gadai', [BarangGadaiController::class, 'index'])->name('barang_gadai.index');
    Route::get('/barang_gadai/edit',[BarangGadaiController::class,'edit'])->name('barang_gadai.update');
    Route::get('/barang-gadai/diperpanjang-dm', [BarangGadaiController::class, 'tampilBarangDiperpanjangDenganDm'])
    ->name('barang_gadai.diperpanjang_dm');
    // Route untuk menampilkan form edit berdasarkan no_bon
    Route::get('/barang_gadai/{no_bon}/edit-nobon', [BarangGadaiController::class, 'editNobon'])->name('barang_gadai.edit_nobon');


    // route untuk lelang
    Route::get('/lelang', [BarangGadaiController::class, 'lelangIndex'])->name('lelang.index');
    Route::post('/barang-gadai/{id}/lelang', [BarangGadaiController::class, 'ubahStatusLelang'])->name('barang-gadai.lelang');
    Route::get('/lelang/{no_bon}/create', [LelangController::class, 'create'])->name('lelang.create');
    Route::post('/lelang/store', [LelangController::class, 'store'])->name('lelang.store');
    Route::get('/nasabah/lelang', [LelangController::class, 'index'])->name('nasabah.lelang');
    Route::get('/lelang/{no_bon}/edit', [LelangController::class, 'edit'])->name('lelang.edit');
    Route::put('/lelang/{no_bon}', [LelangController::class, 'update'])->name('lelang.update');
    Route::delete('/lelang/{id}/hapus-foto/{index}', [LelangController::class, 'hapusFoto'])->name('lelang.hapusFoto');
    Route::get('/barang-gadai/detail/{no_bon}', [BarangGadaiController::class, 'getDetail']);
    Route::get('/admin/barang-lelang', [LelangController::class, 'daftarBarangLelang'])->name('admin.barang-lelang');
    // Halaman pilihan
    Route::get('/lelang/pilihan', [LelangController::class, 'pilihan'])->name('lelang.pilihan');

    Route::post('/lelang/jual/{id}', [LelangController::class, 'jual'])->name('lelang.jual');


// Route untuk update (pastikan ini sesuai juga)
    Route::put('/barang_gadai/{no_bon}/update-nobon', [BarangGadaiController::class, 'updateNobon'])->name('barang_gadai.update_nobon');
    // route untuk view
    Route::resource('nasabah', NasabahController::class);
    Route::resource('barang_gadai', BarangGadaiController::class);
    Route::resource('transaksi_gadai', TransaksiGadaiController::class);
    Route::resource('lelang_barang', LelangController::class);
    Route::resource('laporan', LaporanController::class);
    Route::resource('notifikasi', NotifikasiController::class);


    // Route untuk profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/nasabah/update-password', [NasabahController::class, 'updatePassword'])->name('nasabah.update-password');
    Route::post('/nasabah/validate-password', [NasabahController::class, 'validatePassword'])->name('nasabah.validate-password');

    // Halaman form input gadai
    Route::get('/gadai/create', [GadaiController::class, 'create'])->name('gadai.create');
    // Submit form → validasi → simpan ke session → redirect ke halaman preview
    Route::post('/gadai/preview', [GadaiController::class, 'preview'])->name('gadai.preview');
    // Halaman preview konfirmasi sebelum disimpan ke database
    Route::get('/gadai/preview', [GadaiController::class, 'showPreview'])->name('gadai.showPreview');
    // Submit dari halaman preview untuk benar-benar menyimpan transaksi ke database
    Route::post('/gadai/store', [GadaiController::class, 'store'])->name('gadai.store');


    // buat kategori
    Route::get('/gadai/create', [GadaiController::class, 'create'])->name('gadai.create');

});
// Route::post('/midtrans/webhook', [NasabahPaymentController::class, 'handleNotificationTEST']);

Route::post('/nasabah/process-tebus-payment', [NasabahPaymentController::class, 'processPaymentJson']);
Route::post('/nasabah/cancel-payment', [NasabahPaymentController::class, 'cancelPayment']);
Route::post('/nasabah/validate-pending-payment', [NasabahPaymentController::class, 'validatePending']);

Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');

require __DIR__.'/auth.php';
