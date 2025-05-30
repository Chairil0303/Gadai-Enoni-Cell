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

        $barangPerKategori = DB::table('barang_gadai')
            ->join('kategori_barang', 'barang_gadai.id_kategori', '=', 'kategori_barang.id_kategori')
            ->select('kategori_barang.nama_kategori', DB::raw('count(*) as total'))
            ->where('barang_gadai.status', 'Tergadai')
            ->whereIn('kategori_barang.nama_kategori', ['Laptop', 'HP', 'TV']) // filter sesuai kebutuhan
            ->groupBy('kategori_barang.nama_kategori')
            ->orderByDesc('total')
            ->get();

        $topCabang = DB::table('transaksi')
            ->join('cabang', 'transaksi.id_cabang', '=', 'cabang.id_cabang')
            ->select('cabang.nama_cabang', DB::raw('SUM(transaksi.jumlah) as total_pendapatan'))
            ->where('transaksi.arus_kas', 'masuk')
            ->groupBy('cabang.nama_cabang')
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

                // Query aktivitas terbaru
                $aktivitasTerbaru = DB::table('log_aktivitas')
                ->join('users', 'log_aktivitas.id_users', '=', 'users.id_users')
                ->select('log_aktivitas.*', 'users.nama')
                ->orderByDesc('log_aktivitas.waktu_aktivitas')  // pakai kolom waktu_aktivitas sebagai waktu
                ->limit(5)
            ->get();

        return view('components.dashboard.superadmin', compact(
            'totalCabang',
            'totalGadaiAktif',
            'totalNilaiGadai',
            'transaksiHariIni',
            'pendapatanHariIni',
            'barangPerKategori',
            'topCabang',
            'chartLabels',
            'chartTransaksi',
            'chartPendapatan',
            'aktivitasTerbaru'  // jangan lupa tambahkan variabel baru di sini
        ));
    }
}
