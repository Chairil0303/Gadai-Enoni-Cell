<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\TransaksiGadai;
use App\Models\LelangBarang;

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
