<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangGadai;
use App\Models\Nasabah;
use App\Models\KategoriBarang;
use Illuminate\Support\Facades\Schema;

class BarangGadaiController extends Controller
{
    public function index()
{
    $userId = auth()->id(); // Ambil ID admin yang sedang login

    // Periksa apakah kolom 'id_user' ada di dalam tabel
    if ($userId == 1) {
        $barangGadai = BarangGadai::with('nasabah', 'kategori')->get();
    } else {
        if (Schema::hasColumn('barang_gadai', 'id_user')) {
            // Jika kolom id_user ada, filter berdasarkan id_user
            $barangGadai = BarangGadai::with('nasabah', 'kategori')
                            ->where('id_user', $userId)
                            ->get();
        } else {
            // Jika kolom tidak ada, tampilkan tabel kosong tanpa error
            $barangGadai = collect(); // Mengembalikan collection kosong
        }
    }

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

        BarangGadai::create([
            'id_user' => auth()->id(), // Isi dengan ID admin yang login
            'id_nasabah' => $request->id_nasabah,
            'nama_barang' => $request->nama_barang,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
            'id_kategori' => $request->id_kategori,
        ]);

        return redirect()->route('barang_gadai.index')->with('success', 'Barang gadai berhasil ditambahkan.');
    }


    public function show(BarangGadai $barangGadai)
    {
        return view('barang_gadai.show', compact('barangGadai'));
    }

    public function edit(BarangGadai $barangGadai)
    {
        if ($barangGadai->id_user !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $nasabah = Nasabah::all();
        $kategori = KategoriBarang::all();
        return view('barang_gadai.edit', compact('barangGadai', 'nasabah', 'kategori'));
    }


    public function update(Request $request, BarangGadai $barangGadai)
    {
        if ($barangGadai->id_user !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

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
    if ($barangGadai->id_user !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    $barangGadai->delete();
    return redirect()->route('barang_gadai.index')->with('success', 'Barang gadai berhasil dihapus.');
}

}