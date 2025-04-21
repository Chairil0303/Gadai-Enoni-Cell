<?php
namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriBarang;

class KategoriBarangController extends Controller
{
    public function index()
    {
        $kategori = KategoriBarang::all();
        return view('superadmin.kategori_barang.index', compact('kategori'));
    }

    public function create()
    {
        return view('superadmin.kategori_barang.create');
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        KategoriBarang::create($request->all());
        return redirect()->route('superadmin.kategori-barang.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit(KategoriBarang $kategori_barang)
    {
        return view('superadmin.kategori_barang.edit', compact('kategori_barang'));
    }

    public function update(Request $request, KategoriBarang $kategori_barang)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori_barang->update($request->all());
        return redirect()->route('superadmin.kategori-barang.index')->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(KategoriBarang $kategori_barang)
    {
        $kategori_barang->delete();
        return redirect()->route('superadmin.kategori-barang.index')->with('success', 'Kategori berhasil dihapus');
    }
}
