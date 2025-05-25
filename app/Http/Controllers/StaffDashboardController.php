<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class StaffDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $idCabang = $user->id_cabang;
        $today = Carbon::today();

        // --- 1. Statistik Hari Ini ---
        $transaksiHariIni = DB::table('transaksi')
            ->where('id_cabang', $idCabang)
            ->whereDate('created_at', $today)
            ->count();

        $totalMasukHariIni = DB::table('transaksi')
            ->where('id_cabang', $idCabang)
            ->where('arus_kas', 'masuk')
            ->whereDate('created_at', $today)
            ->sum('jumlah');

        $totalKeluarHariIni = DB::table('transaksi')
            ->where('id_cabang', $idCabang)
            ->where('arus_kas', 'keluar')
            ->whereDate('created_at', $today)
            ->sum('jumlah');

        // --- 2. Barang Mendekati Tempo ---
        $mendekatiTempo = DB::table('barang_gadai')
            ->where('id_cabang', $idCabang)
            ->whereDate('tempo', '>=', $today)
            ->whereDate('tempo', '<=', $today->copy()->addDays(5))
            ->where('status', 'Tergadai')
            ->orderBy('tempo')
            ->limit(5)
            ->get();

        // --- 3. Grafik 7 Hari Terakhir ---
        $period = CarbonPeriod::create($today->copy()->subDays(6), $today);
        $chartLabels = [];
        $chartData = [];

        foreach ($period as $date) {
            $chartLabels[] = $date->format('Y-m-d');

            $jumlah = DB::table('transaksi')
                ->where('id_cabang', $idCabang)
                ->whereDate('created_at', $date)
                ->count();

            $chartData[] = $jumlah;
        }

        return view('components.dashboard.staff', compact(
            'transaksiHariIni',
            'totalMasukHariIni',
            'totalKeluarHariIni',
            'mendekatiTempo',
            'chartLabels',
            'chartData'
        ));
    }
}
