<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Transaksi;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function show($id)
    {
        if ($id === 'harian') {
            $tanggal = Carbon::today();

            $data = Transaksi::whereDate('created_at', $tanggal)
                ->selectRaw('jenis_transaksi, COUNT(*) as jumlah, 
                    SUM(CASE WHEN arah = "keluar" THEN nominal ELSE 0 END) as total_keluar,
                    SUM(CASE WHEN arah = "masuk" THEN nominal ELSE 0 END) as total_masuk')
                ->groupBy('jenis_transaksi')
                ->get();

            return view('admin.laporan.harian', compact('data'));
        }

        // (opsional) bulanan nanti menyusul
    }


    // method lainnya bisa diisi sesuai kebutuhan (create, store, etc.)
}
