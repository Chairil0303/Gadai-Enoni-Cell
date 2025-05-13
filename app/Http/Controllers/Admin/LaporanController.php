<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Transaksi;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function filter(Request $request, $jenis)
    {
        if ($jenis === 'harian') {
            $tanggal = $request->get('tanggal');

            if (!$tanggal) {
                return redirect()->back()->with('error', 'Tanggal harus dipilih.');
            }

            $transaksi = Transaksi::whereDate('created_at', $tanggal)->get();

            return view('laporan.harian', [
                'tanggal' => Carbon::parse($tanggal)->translatedFormat('d F Y'),
                'transaksi' => $transaksi,
            ]);
        }

        if ($jenis === 'bulanan') {
            $bulan = $request->get('bulan'); // Format: YYYY-MM

            if (!$bulan || !preg_match('/^\d{4}-\d{2}$/', $bulan)) {
                return redirect()->back()->with('error', 'Format bulan tidak valid.');
            }

            [$tahun, $bulanAngka] = explode('-', $bulan);
            $awal = Carbon::createFromDate($tahun, $bulanAngka, 1)->startOfMonth();
            $akhir = Carbon::createFromDate($tahun, $bulanAngka, 1)->endOfMonth();

            $transaksi = Transaksi::whereBetween('created_at', [$awal, $akhir])->get();

            return view('admin.laporan.bulanan', [
                'bulan' => $awal->translatedFormat('F Y'),
                'transaksi' => $transaksi,
            ]);
        }

        return abort(404);
    }

    public function show($id)
    {
        // ambil tanggal dari request, atau default hari ini
        $tanggal = request('tanggal')
            ? Carbon::parse(request('tanggal'))
            : Carbon::today();

        // ambil cabang_id dari user login
        $cabangId = auth()->user()->id_cabang;

        if ($id === 'harian') {
            $data = Transaksi::whereDate('created_at', $tanggal)
                ->where('id_cabang', $cabangId)
                ->selectRaw('jenis_transaksi, COUNT(*) as jumlah,
                    SUM(CASE WHEN arah = "keluar" THEN nominal ELSE 0 END) as total_keluar,
                    SUM(CASE WHEN arah = "masuk" THEN nominal ELSE 0 END) as total_masuk')
                ->groupBy('jenis_transaksi')
                ->get();

            return view('admin.laporan.harian', [
                'data' => $data,
                'tanggal' => $tanggal->toDateString()
            ]);
        }

    // method lainnya bisa diisi sesuai kebutuhan (create, store, etc.)
}
}
