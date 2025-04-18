<?php
use App\Http\Controllers\perpanjangGadaiNasabahController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Nasabah;
use App\Http\Controllers\TebusGadaiNasabahController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\NasabahPaymentController;

Route::get('/test', function () {
    return response()->json(['message' => 'API aktif']);
});


Route::post('/login-api', function (Request $request) {
    $credentials = $request->only('username', 'password');

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Username atau password salah'], 401);
    }

    $user = Auth::user();

    if ($user->role !== 'Nasabah') {
        return response()->json(['message' => 'Hanya nasabah yang bisa login dari API ini'], 403);
    }

    // Ambil data nasabah berdasarkan user ID
    $nasabah = Nasabah::where('id_user', $user->id_users)->first();

    if (!$nasabah) {
        return response()->json(['message' => 'Data nasabah tidak ditemukan'], 404);
    }

    $token = $user->createToken('nasabah-token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => [
            'id_user' => $user->id_users,
            'username' => $user->username,
            'nama' => $user->nama,
            'role' => $user->role,
            'cabang' => $user->cabang,
        ],
        'nasabah' => [
            'id_nasabah' => $nasabah->id_nasabah,
            'nama' => $nasabah->nama,
            'telepon' => $nasabah->telepon,
        ]
    ]);
});


Route::middleware('auth:sanctum')->get('/nasabah/dashboard', function (Request $request) {
    $user = $request->user();

    if ($user->role === 'Nasabah') {
        // Cari nasabah berdasarkan id_user (relasi manual, bukan via model)
        $nasabah = Nasabah::where('id_user', $user->id_users)->first();

        if (!$nasabah) {
            return response()->json(['message' => 'Data nasabah tidak ditemukan.'], 404);
        }

        $barangGadai = $nasabah->barangGadai()->get();

        return response()->json([
            'user' => [
                'id' => $user->id_users,
                'nama' => $user->nama,
                'email' => $user->email,
                'username' => $user->username,
                'role' => $user->role,
            ],
            'nasabah' => [
                'id' => $nasabah->id_nasabah,
                'nama' => $nasabah->nama,
                'nik' => $nasabah->nik,
                'alamat' => $nasabah->alamat,
                'telepon' => $nasabah->telepon,
                'status_blacklist' => $nasabah->status_blacklist,
            ],
            'barang_gadai' => $barangGadai
        ]);
    }

    return response()->json(['message' => 'Role tidak dikenali atau tidak didukung untuk API ini.'], 403);
});

Route::middleware('auth:sanctum')->get('/nasabah/konfirmasi-json/{no_bon}', [TebusGadaiNasabahController::class, 'konfirmasiJson']);

Route::post('/nasabah/payment-notification', [NasabahPaymentController::class, 'handleNotification']);


Route::middleware('auth:sanctum')->get('/nasabah/sample-payment-json/{noBon}', [perpanjangGadaiNasabahController::class, 'getPaymentJsonByBon']);

// Route::middleware('auth:sanctum')->get('/nasabah/sample-payment-json', [NasabahPaymentController::class, 'getSamplePaymentJson']);

// Proses pembuatan Snap Token Midtrans
Route::middleware('auth:sanctum')->post('/nasabah/payment/{no_bon}', [NasabahPaymentController::class, 'processPayment']);

// Proses pembuatan Snap Token Midtrans untuk perpanjang gadai
Route::middleware('auth:sanctum')->post('/nasabah/payment-perpanjang/{no_bon}', [perpanjangGadaiNasabahController::class, 'processPayment']);

// Endpoint notifikasi callback dari Midtrans
Route::post('/midtrans/notification', [NasabahPaymentController::class, 'handleNotificationJson']);
