<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

Route::get('/test', function () {
    return response()->json(['message' => 'API aktif']);
});


Route::post('/login-api', function (Request $request) {
    $credentials = $request->only('username', 'password');

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Username atau password salah'], 401);
    }

    $user = Auth::user();

    // Cek role kalau perlu
    if ($user->role !== 'Nasabah') {
        return response()->json(['message' => 'Hanya nasabah yang bisa login dari API ini'], 403);
    }

    $token = $user->createToken('nasabah-token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'username' => $user->username,
            'nama' => $user->nama,
            'role' => $user->role,
        ]
    ]);
});


Route::middleware('auth:sanctum')->get('/nasabah/dashboard', function (Request $request) {
    $user = $request->user();

    if ($user->role === 'Nasabah') {
        $nasabah = $user->nasabah;

        if (!$nasabah) {
            return response()->json(['message' => 'Data nasabah tidak ditemukan.'], 404);
        }

        $barangGadai = $nasabah->barangGadai()->get();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'nama' => $user->nama,
                'email' => $user->email,
                'username' => $user->username,
                'role' => $user->role,
            ],
            'nasabah' => [
                'id' => $nasabah->id,
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


// Route::post('/nasabah/payment-notification', [NasabahPaymentController::class, 'handleNotification']);
