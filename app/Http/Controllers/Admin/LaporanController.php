<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function harian(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $transaksi = collect();
        $totalMasuk = 0;
        $totalKeluar = 0;

        if ($tanggal) {
            $user = Auth::user();
            $id_cabang = $user->id_cabang;

            $transaksi = Transaksi::with(['nasabah', 'user']) // tambahkan 'user' juga
                ->where('id_cabang', $id_cabang)
                ->whereDate('created_at', $tanggal)
                ->orderBy('created_at', 'desc')
                ->get();

            $totalMasuk = $transaksi->where('arus_kas', 'masuk')->sum('jumlah');
            $totalKeluar = $transaksi->where('arus_kas', 'keluar')->sum('jumlah');
        }

        return view('admin.laporan.harian', compact('transaksi', 'tanggal', 'totalMasuk', 'totalKeluar'));
    }



    
    public function bulanan(Request $request)
    {
        $bulan = $request->input('bulan'); // format: YYYY-MM
        $transaksi = collect();

        if ($bulan) {
            $user = Auth::user();
            $id_cabang = $user->id_cabang;

            $transaksi = Transaksi::with(['nasabah'])
                ->where('id_cabang', $id_cabang)
                ->whereYear('created_at', substr($bulan, 0, 4))
                ->whereMonth('created_at', substr($bulan, 5, 2))
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('admin.laporan.bulanan', compact('transaksi'));
    }
}
