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
        // nanti isi logic laporan harian di sini
        return view('admin.laporan.harian');
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
