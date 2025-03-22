<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LelangBarang;
use App\Models\BarangGadai;

class LelangBarangController extends Controller
{
    public function index()
    {
        $lelang = LelangBarang::with('barang')->get();
        return view('lelang_barang.index', compact('lelang'));
    }

    public function create()
    {
        $barang = BarangGadai::where('status', 'Dilelang')->get();
        return view('lelang_barang.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang_gadai,id_barang',
            'tanggal_lelang' => 'required|date',
            'harga_awal' => 'required|numeric',
            'status_penjualan' => 'required|in:Belum Terjual,Terjual',
        ]);

        LelangBarang::create($request->all());

        return redirect()->route('lelang_barang.index')->with('success', 'Lelang berhasil ditambahkan.');
    }

    public function show(LelangBarang $lelangBarang)
    {
        return view('lelang_barang.show', compact('lelangBarang'));
    }

    public function edit(LelangBarang $lelangBarang)
    {
        $barang = BarangGadai::where('status', 'Dilelang')->get();
        return view('lelang_barang.edit', compact('lelangBarang', 'barang'));
    }

    public function update(Request $request, LelangBarang $lelangBarang)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang_gadai,id_barang',
            'tanggal_lelang' => 'required|date',
            'harga_awal' => 'required|numeric',
            'status_penjualan' => 'required|in:Belum Terjual,Terjual',
        ]);

        $lelangBarang->update($request->all());

        return redirect()->route('lelang_barang.index')->with('success', 'Lelang berhasil diperbarui.');
    }

    public function destroy(LelangBarang $lelangBarang)
    {
        $lelangBarang->delete();
        return redirect()->route('lelang_barang.index')->with('success', 'Lelang berhasil dihapus.');
    }
}
