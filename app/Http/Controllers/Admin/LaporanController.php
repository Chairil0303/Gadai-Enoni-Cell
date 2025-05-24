<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use App\Models\SaldoCabang;

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
        $saldoAwalTanggal = 0;
        $saldoAkhirTanggal = 0;

        if ($tanggal) {
            $user = Auth::user();
            $id_cabang = $user->id_cabang;

            // Ambil data saldo cabang
            $saldoCabang = SaldoCabang::where('id_cabang', $id_cabang)->first();
            $saldo_awal_sistem = $saldoCabang?->saldo_awal ?? 0;

            // Saldo sebelum tanggal dipilih (dari semua transaksi sebelum tanggal)
            $saldoTambahanSebelumTanggal = Transaksi::where('id_cabang', $id_cabang)
                ->whereDate('created_at', '<', $tanggal)
                ->selectRaw("
                    SUM(CASE WHEN arus_kas = 'masuk' THEN jumlah ELSE 0 END) -
                    SUM(CASE WHEN arus_kas = 'keluar' THEN jumlah ELSE 0 END) AS saldo
                ")->value('saldo') ?? 0;

            $saldoAwalTanggal = $saldo_awal_sistem + $saldoTambahanSebelumTanggal;

            // Transaksi hari ini
            $transaksi = Transaksi::with(['nasabah', 'user'])
                ->where('id_cabang', $id_cabang)
                ->whereDate('created_at', $tanggal)
                ->orderBy('created_at', 'desc')
                ->get();

            // Total masuk dan keluar hari ini
            $totalMasuk = $transaksi->where('arus_kas', 'masuk')->sum('jumlah');
            $totalKeluar = $transaksi->where('arus_kas', 'keluar')->sum('jumlah');

            // Saldo akhir hari itu
            $saldoAkhirTanggal = $saldoAwalTanggal + $totalMasuk - $totalKeluar;
        }

        return view('admin.laporan.harian', compact(
            'transaksi', 'tanggal',
            'totalMasuk', 'totalKeluar',
            'saldoAwalTanggal', 'saldoAkhirTanggal'
        ));
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

        $totalMasuk = $transaksi->where('arus_kas', 'masuk')->sum('jumlah');
        $totalKeluar = $transaksi->where('arus_kas', 'keluar')->sum('jumlah');

        $ringkasanJenis = $transaksi->groupBy('jenis_transaksi')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('jumlah'),
            ];
        });


        return view('admin.laporan.bulanan', compact(
            'transaksi', 
            'totalMasuk', 
            'totalKeluar', 
            'ringkasanJenis'
        ));
    }
}
