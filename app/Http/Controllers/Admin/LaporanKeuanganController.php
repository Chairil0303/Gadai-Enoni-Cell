<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\SaldoCabang;
use App\Models\Cabang;
use App\Http\Controllers\Controller;


class LaporanKeuanganController extends Controller
{
    public function index(Request $request)
    {
        $cabangId = $request->input('id_cabang', 1); // default cabang 1, bisa diganti pakai dropdown
        $cabang = Cabang::findOrFail($cabangId);

        $saldo = SaldoCabang::where('id_cabang', $cabangId)->first();
        $transaksi = Transaksi::where('id_cabang', $cabangId)->orderBy('created_at', 'desc')->get();

        $totalMasuk = $transaksi->where('arus_kas', 'masuk')->sum('jumlah');
        $totalKeluar = $transaksi->where('arus_kas', 'keluar')->sum('jumlah');

        return view('admin.laporan.keuangan', compact(
            'cabang', 'saldo', 'transaksi', 'totalMasuk', 'totalKeluar'
        ));
    }
}