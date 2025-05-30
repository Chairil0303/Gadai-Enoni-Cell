<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Models\LogAktivitas;

class ActivityLogger
{
    public static function log($kategori, $aksi, $deskripsi = null, $dataLama = null, $dataBaru = null)
    {
        $user = Auth::user();

        LogAktivitas::create([
            'id_users'       => $user->id_users ?? null,
            'id_cabang'      => $user->id_cabang ?? null,
            'kategori'       => $kategori,
            'aksi'           => $aksi,
            'deskripsi'      => $deskripsi,
            'data_lama'      => $dataLama,
            'data_baru'      => $dataBaru,
            'ip_address'     => Request::ip(),
            'user_agent'     => Request::header('User-Agent'),
            'waktu_aktivitas'=> now(),
        ]);
    }
}
