<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\TransaksiGadai;
use App\Models\LelangBarang;
use Illuminate\Support\Carbon;
use App\Models\Transaksi;

class LaporanController extends Controller
{
    public function index()
    {
        $laporan = Laporan::latest()->get();
        return view('laporan.index', compact('laporan'));
    }

    public function create()
    {
        $transaksi = TransaksiGadai::all();
        $lelang = LelangBarang::all();
        return view('laporan.create', compact('transaksi', 'lelang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_transaksi' => 'nullable|exists:transaksi_gadai,id_transaksi',
            'id_lelang' => 'nullable|exists:lelang_barang,id_lelang',
            'tipe_laporan' => 'required|string',
            'keterangan' => 'nullable|string',
            'jumlah' => 'nullable|numeric',
            'tanggal_laporan' => 'required|date',
        ]);

        Laporan::create($request->all());

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil ditambahkan.');
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

            return view('laporan.bulanan', [
                'bulan' => $awal->translatedFormat('F Y'),
                'transaksi' => $transaksi,
            ]);
        }

        return abort(404);
    }


    public function show(Laporan $laporan)
    {
        return view('laporan.show', compact('laporan'));
    }

    public function edit(Laporan $laporan)
    {
        $transaksi = TransaksiGadai::all();
        $lelang = LelangBarang::all();
        return view('laporan.edit', compact('laporan', 'transaksi', 'lelang'));
    }

    public function update(Request $request, Laporan $laporan)
    {
        $request->validate([
            'id_transaksi' => 'nullable|exists:transaksi_gadai,id_transaksi',
            'id_lelang' => 'nullable|exists:lelang_barang,id_lelang',
            'tipe_laporan' => 'required|string',
            'keterangan' => 'nullable|string',
            'jumlah' => 'nullable|numeric',
            'tanggal_laporan' => 'required|date',
        ]);

        $laporan->update($request->all());

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(Laporan $laporan)
    {
        $laporan->delete();
        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dihapus.');
    }
    
}
