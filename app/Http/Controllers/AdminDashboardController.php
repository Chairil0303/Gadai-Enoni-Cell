<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\BarangGadai;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $idCabang = auth()->user()->id_cabang;
        $today = Carbon::today();

        // Gadai aktif
        $totalGadaiAktif = BarangGadai::where('status', 'Tergadai')
            ->where('id_cabang', $idCabang)
            ->count();

        $totalNilaiGadai = BarangGadai::where('status', 'Tergadai')
            ->where('id_cabang', $idCabang)
            ->sum('harga_gadai');

        $jumlahBarangGadai = BarangGadai::where('id_cabang', $idCabang)->count();

        // Transaksi hari ini (eager load user)
        $transaksiHariIni = Transaksi::with('user')
            ->where('id_cabang', $idCabang)
            ->whereDate('created_at', $today)
            ->count();

        $pendapatanHariIni = Transaksi::where('id_cabang', $idCabang)
            ->where('arus_kas', 'masuk')
            ->whereDate('created_at', $today)
            ->sum('jumlah');

        // Barang populer
        $barangPopuler = BarangGadai::select('nama_barang')
            ->where('id_cabang', $idCabang)
            ->groupBy('nama_barang')
            ->selectRaw('count(*) as total')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Staff cabang (eager load cabang)
        $staffCabang = User::with('cabang')
            ->where('id_cabang', $idCabang)
            ->where('role', 'Staf')
            ->select('id_users', 'nama', 'role', 'id_cabang')
            ->get();

        // Grafik 7 hari terakhir
        $period = CarbonPeriod::create($today->copy()->subDays(6), $today);

        $chartLabels = [];
        $chartTransaksi = [];
        $chartPendapatan = [];

        foreach ($period as $date) {
            $label = $date->format('Y-m-d');
            $chartLabels[] = $label;

            $chartTransaksi[] = Transaksi::where('id_cabang', $idCabang)
                ->whereDate('created_at', $date)
                ->count();

            $chartPendapatan[] = Transaksi::where('id_cabang', $idCabang)
                ->where('arus_kas', 'masuk')
                ->whereDate('created_at', $date)
                ->sum('jumlah');
        }

        // Aktivitas terbaru cabang ini, join users
        $aktivitasTerbaru = DB::table('log_aktivitas')
        ->join('users', 'log_aktivitas.id_users', '=', 'users.id_users')
        ->where('log_aktivitas.id_cabang', $idCabang)
        ->select('log_aktivitas.*', 'users.nama')
        ->orderBy('log_aktivitas.waktu_aktivitas', 'desc')
        ->limit(5)
        ->get();

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
        'chartPendapatan',
        'aktivitasTerbaru' // jangan lupa kirim ke view
    ));
    }
}
