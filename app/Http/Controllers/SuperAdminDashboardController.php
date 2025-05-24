<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalCabang = DB::table('cabang')->count();

        $totalGadaiAktif = DB::table('barang_gadai')
            ->where('status', 'Tergadai')
            ->count();

        $totalNilaiGadai = DB::table('barang_gadai')
            ->where('status', 'Tergadai')
            ->sum('harga_gadai');

        $transaksiHariIni = DB::table('transaksi')
            ->whereDate('created_at', $today)
            ->count();

        $pendapatanHariIni = DB::table('transaksi')
            ->whereDate('created_at', $today)
            ->where('arus_kas', 'masuk')
            ->sum('jumlah');

        $barangPopuler = DB::table('barang_gadai')
            ->select('nama_barang', DB::raw('count(*) as total'))
            ->groupBy('nama_barang')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topCabang = DB::table('transaksi')
            ->select('id_cabang', DB::raw('SUM(jumlah) as total_pendapatan'))
            ->where('arus_kas', 'masuk')
            ->groupBy('id_cabang')
            ->orderByDesc('total_pendapatan')
            ->limit(5)
            ->get();

        $period = CarbonPeriod::create($today->copy()->subDays(6), $today);
        $chartLabels = [];
        $chartTransaksi = [];
        $chartPendapatan = [];

        foreach ($period as $date) {
            $chartLabels[] = $date->format('Y-m-d');

            $transaksiCount = DB::table('transaksi')
                ->whereDate('created_at', $date)
                ->count();

            $chartTransaksi[] = $transaksiCount;

            $pendapatanSum = DB::table('transaksi')
                ->whereDate('created_at', $date)
                ->where('arus_kas', 'masuk')
                ->sum('jumlah');

            $chartPendapatan[] = $pendapatanSum;
        }

        return view('components.dashboard.superadmin', compact(
            'totalCabang',
            'totalGadaiAktif',
            'totalNilaiGadai',
            'transaksiHariIni',
            'pendapatanHariIni',
            'barangPopuler',
            'topCabang',
            'chartLabels',
            'chartTransaksi',
            'chartPendapatan'
        ));
    }
}
