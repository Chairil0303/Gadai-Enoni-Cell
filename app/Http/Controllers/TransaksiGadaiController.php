<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiGadai;
use App\Models\Nasabah;
use App\Models\BarangGadai;

class TransaksiGadaiController extends Controller
{
    public function index()
    {
        $transaksi = TransaksiGadai::with('nasabah', 'barang')->get();
        return view('transaksi_gadai.index', compact('transaksi'));
    }

    public function create()
    {
        $nasabah = Nasabah::all();
        $barang = BarangGadai::where('status', 'Tergadai')->get();
        return view('transaksi_gadai.create', compact('nasabah', 'barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_nasabah' => 'required|exists:nasabah,id_nasabah',
            'id_barang' => 'required|exists:barang_gadai,id_barang',
            'tanggal_gadai' => 'required|date',
            'jumlah_pinjaman' => 'required|numeric',
            'bunga' => 'required|numeric',
            'jatuh_tempo' => 'required|date|after:tanggal_gadai',
        ]);

        TransaksiGadai::create($request->all());

        return redirect()->route('transaksi_gadai.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function show(TransaksiGadai $transaksiGadai)
    {
        return view('transaksi_gadai.show', compact('transaksiGadai'));
    }

    public function edit(TransaksiGadai $transaksiGadai)
    {
        $nasabah = Nasabah::all();
        $barang = BarangGadai::where('status', 'Tergadai')->get();
        return view('transaksi_gadai.edit', compact('transaksiGadai', 'nasabah', 'barang'));
    }

    public function update(Request $request, TransaksiGadai $transaksiGadai)
    {
        $request->validate([
            'id_nasabah' => 'required|exists:nasabah,id_nasabah',
            'id_barang' => 'required|exists:barang_gadai,id_barang',
            'tanggal_gadai' => 'required|date',
            'jumlah_pinjaman' => 'required|numeric',
            'bunga' => 'required|numeric',
            'jatuh_tempo' => 'required|date|after:tanggal_gadai',
        ]);

        $transaksiGadai->update($request->all());

        return redirect()->route('transaksi_gadai.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(TransaksiGadai $transaksiGadai)
    {
        $transaksiGadai->delete();
        return redirect()->route('transaksi_gadai.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
