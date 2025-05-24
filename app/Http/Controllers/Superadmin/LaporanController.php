<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cabang;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\SaldoCabang;

class LaporanController extends Controller
{
    public function index()
    {
        $cabangs = \App\Models\Cabang::all();
        return view('superadmin.laporan.index', compact('cabangs'));
    }

    public function detail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cabang_id' => 'required|exists:cabang,id_cabang',
            'tipe' => 'required|in:harian,bulanan',
            'tanggal' => 'nullable|required_if:tipe,harian|date',
            'bulan' => 'nullable|required_if:tipe,bulanan|date_format:Y-m',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $id_cabang = $request->cabang_id;
        $query = Transaksi::where('id_cabang', $id_cabang);

        // Setup tanggal filter
        if ($request->tipe === 'harian') {
            $query->whereDate('created_at', $request->tanggal);
            $tanggal = Carbon::parse($request->tanggal);
        } else {
            [$tahun, $bulan] = explode('-', $request->bulan);
            $query->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $bulan);
            $tanggal = Carbon::createFromDate($tahun, $bulan, 1);
        }

        $laporan = $query->with(['nasabah', 'barangGadai'])->orderBy('created_at', 'desc')->get();

        $totalMasuk = $laporan->where('arus_kas', 'masuk')->sum('jumlah');
        $totalKeluar = $laporan->where('arus_kas', 'keluar')->sum('jumlah');

        $ringkasanJenis = $laporan->groupBy('jenis_transaksi')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('jumlah'),
            ];
        });

        $saldo_awal_sistem = SaldoCabang::where('id_cabang', $id_cabang)->value('saldo_awal') ?? 0;

        $cutoffDate = $request->tipe === 'harian'
            ? $tanggal->copy()->startOfDay()
            : $tanggal->copy()->startOfMonth();

        $saldoTransaksiSebelumnya = Transaksi::where('id_cabang', $id_cabang)
            ->where('created_at', '<', $cutoffDate)
            ->selectRaw("
                SUM(CASE WHEN arus_kas = 'masuk' THEN jumlah ELSE 0 END) -
                SUM(CASE WHEN arus_kas = 'keluar' THEN jumlah ELSE 0 END) AS saldo
            ")->value('saldo') ?? 0;

        $saldoAwal = $saldo_awal_sistem + $saldoTransaksiSebelumnya;
        $saldoAkhir = $saldoAwal + $totalMasuk - $totalKeluar;

        $cabang = \App\Models\Cabang::find($id_cabang);

        return view('superadmin.laporan.detail', compact(
            'laporan', 'cabang', 'request',
            'totalMasuk', 'totalKeluar', 'ringkasanJenis',
            'saldoAwal', 'saldoAkhir'
        ));
    }

}

