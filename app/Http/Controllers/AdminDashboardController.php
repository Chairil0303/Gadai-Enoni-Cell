<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AdminDashboardController extends Controller
{
    
    public function index()
    {
        $idCabang = auth()->user()->id_cabang;
        $today = Carbon::today();

        // Data yang sudah ada
        $totalGadaiAktif = DB::table('barang_gadai')
            ->where('status', 'Tergadai')
            ->where('id_cabang', $idCabang)
            ->count();

        $totalNilaiGadai = DB::table('barang_gadai')
            ->where('status', 'Tergadai')
            ->where('id_cabang', $idCabang)
            ->sum('harga_gadai');

        $jumlahBarangGadai = DB::table('barang_gadai')
            ->where('id_cabang', $idCabang)
            ->count();

        $transaksiHariIni = DB::table('transaksi')
            ->whereDate('created_at', $today)
            ->where('id_cabang', $idCabang)
            ->count();

        $pendapatanHariIni = DB::table('transaksi')
            ->whereDate('created_at', $today)
            ->where('id_cabang', $idCabang)
            ->where('arus_kas', 'masuk')
            ->sum('jumlah');

        $barangPopuler = DB::table('barang_gadai')
            ->select('nama_barang', DB::raw('count(*) as total'))
            ->where('id_cabang', $idCabang)
            ->groupBy('nama_barang')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $staffCabang = DB::table('users')
            ->where('id_cabang', $idCabang)
            ->where('role', 'Staf')
            ->select('id_users', 'nama', 'role')
            ->get();

        // Grafik data 7 hari terakhir
        $period = CarbonPeriod::create($today->copy()->subDays(6), $today);

        $chartLabels = [];
        $chartTransaksi = [];
        $chartPendapatan = [];

        foreach ($period as $date) {
            $chartLabels[] = $date->format('Y-m-d');

            // Transaksi per hari
            $transaksiCount = DB::table('transaksi')
                ->whereDate('created_at', $date)
                ->where('id_cabang', $idCabang)
                ->count();

            $chartTransaksi[] = $transaksiCount;

            // Pendapatan per hari (arus kas masuk)
            $pendapatanSum = DB::table('transaksi')
                ->whereDate('created_at', $date)
                ->where('id_cabang', $idCabang)
                ->where('arus_kas', 'masuk')
                ->sum('jumlah');

            $chartPendapatan[] = $pendapatanSum;
        }

        return view('components.dashboard.admin', compact(
            'totalGadaiAktif',
            'totalNilaiGadai',
            'jumlahBarangGadai',
            'transaksiHariIni',
            'pendapatanHariIni',
            'barangPopuler',
            'staffCabang',
            'chartLabels',
            'chartTransaksi',
            'chartPendapatan'
        ));
    }
}

