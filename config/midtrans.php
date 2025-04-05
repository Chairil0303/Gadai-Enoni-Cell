<?php

return [
    // Server key untuk Midtrans (gunakan server key kamu)
    'server_key' => env('MIDTRANS_SERVER_KEY'),   // Server key dari akun Midtrans

    // Client key untuk Midtrans (gunakan client key kamu)
    'client_key' => env('MIDTRANS_CLIENT_KEY'),   // Client key dari akun Midtrans

    // Tentukan apakah environment ini production atau sandbox
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false), // Set true untuk production, false untuk sandbox

    // Pengaturan sanitasi input (lebih baik tetap aktifkan)
    'is_sanitized' => true, // Aktifkan sanitasi input

    // Pengaturan untuk 3D Secure (gunakan true jika ingin mengaktifkan)
    'is_3ds' => true, // Aktifkan 3D Secure jika perlu
];
