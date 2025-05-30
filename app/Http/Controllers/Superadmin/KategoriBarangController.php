<?php
namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriBarang;
use App\Helpers\ActivityLogger;

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

        $kategori = KategoriBarang::create($request->all());

        ActivityLogger::log(
            kategori: 'kategori_barang',
            aksi: 'create',
            deskripsi: "Menambahkan kategori baru: {$kategori->nama_kategori}",
            dataLama: null,
            dataBaru: $kategori->toArray()
        );

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

        $dataLama = $kategori_barang->toArray();

        $kategori_barang->update($request->all());

        ActivityLogger::log(
            kategori: 'kategori_barang',
            aksi: 'update',
            deskripsi: "Memperbarui kategori: {$kategori_barang->nama_kategori}",
            dataLama: $dataLama,
            dataBaru: $kategori_barang->toArray()
        );

        return redirect()->route('superadmin.kategori-barang.index')->with('success', 'Kategori berhasil diperbarui');
    }


    public function destroy(KategoriBarang $kategori_barang)
    {
        $dataLama = $kategori_barang->toArray();
        $kategori_barang->delete();

        ActivityLogger::log(
            kategori: 'kategori_barang',
            aksi: 'delete',
            deskripsi: "Menghapus kategori: {$dataLama['nama_kategori']}",
            dataLama: $dataLama,
            dataBaru: null
        );

        return redirect()->route('superadmin.kategori-barang.index')->with('success', 'Kategori berhasil dihapus');
    }

}
