<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;
use Auth;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function show($jenis, Request $request)
    {
        $id_cabang = Auth::user()->id_cabang; // Cabang user yang login

        if ($jenis === 'harian') {
            $tanggal = $request->input('tanggal');

            $transaksi = Transaksi::with(['nasabah', 'barangGadai'])
                ->whereDate('tanggal_transaksi', $tanggal)
                ->where('id_cabang', $id_cabang)
                ->get();

            return view('admin.laporan.harian', [
                'transaksi' => $transaksi,
                'tanggal' => $tanggal,
            ]);
        }

        abort(404);
    }

    public function filter($jenis, Request $request)
    {
        $id_cabang = Auth::user()->id_cabang;

        if ($jenis === 'bulanan') {
            $bulan = $request->input('bulan');
            $carbon = Carbon::parse($bulan);

            $transaksi = Transaksi::with(['nasabah', 'barangGadai'])
                ->whereMonth('tanggal_transaksi', $carbon->month)
                ->whereYear('tanggal_transaksi', $carbon->year)
                ->where('id_cabang', $id_cabang)
                ->get();

            return view('admin.laporan.bulanan', [
                'transaksi' => $transaksi,
                'bulan' => $carbon->translatedFormat('F Y'),
            ]);
        }

        abort(404);
    }
}
