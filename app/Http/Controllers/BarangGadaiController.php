<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangGadai;
use App\Models\Nasabah;
use App\Models\KategoriBarang;

class BarangGadaiController extends Controller
{
    public function index()
    {
        $barangGadai = BarangGadai::with('nasabah', 'kategori')->get();
        return view('barang_gadai.index', compact('barangGadai'));
    }

    public function create()
    {
        $nasabah = Nasabah::all();
        $kategori = KategoriBarang::all();
        return view('barang_gadai.create', compact('nasabah', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_nasabah' => 'required|exists:nasabah,id_nasabah',
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:Tergadai,Ditebus,Dilelang',
            'id_kategori' => 'nullable|exists:kategori_barang,id_kategori',
        ]);

        BarangGadai::create($request->all());

        return redirect()->route('barang_gadai.index')->with('success', 'Barang gadai berhasil ditambahkan.');
    }

    public function show(BarangGadai $barangGadai)
    {
        return view('barang_gadai.show', compact('barangGadai'));
    }

    public function edit(BarangGadai $barangGadai)
    {
        $nasabah = Nasabah::all();
        $kategori = KategoriBarang::all();
        return view('barang_gadai.edit', compact('barangGadai', 'nasabah', 'kategori'));
    }

    public function update(Request $request, BarangGadai $barangGadai)
    {
        $request->validate([
            'id_nasabah' => 'required|exists:nasabah,id_nasabah',
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:Tergadai,Ditebus,Dilelang',
            'id_kategori' => 'nullable|exists:kategori_barang,id_kategori',
        ]);

        $barangGadai->update($request->all());

        return redirect()->route('barang_gadai.index')->with('success', 'Barang gadai berhasil diperbarui.');
    }

    public function destroy(BarangGadai $barangGadai)
    {
        $barangGadai->delete();
        return redirect()->route('barang_gadai.index')->with('success', 'Barang gadai berhasil dihapus.');
    }
}