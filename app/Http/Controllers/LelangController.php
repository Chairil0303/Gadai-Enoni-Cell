<?php

namespace App\Http\Controllers;

use App\Models\BarangGadai;
use App\Models\Lelang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LelangController extends Controller
{

    public function index()
    {
        $barangLelang = Lelang::with('barangGadai')->get();
        return view('nasabah.lelang.index', compact('barangLelang'));
    }

    public function create($no_bon)
    {
        $barang = BarangGadai::where('no_bon', $no_bon)->firstOrFail();
        return view('lelang.create', compact('barang'));
    }

public function store(Request $request)
{
    $request->validate([
        'barang_gadai_no_bon' => 'required|exists:barang_gadai,no_bon',
        'kondisi_barang' => 'required',
        'keterangan' => 'nullable',
        'harga_lelang' => 'nullable|numeric',
        'foto_barang' => 'nullable|array', // Validasi foto_barang sebagai array
        'foto_barang.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi setiap file gambar
    ]);

    // Simpan path foto dalam array
        $fotoPaths = [];
        if ($request->hasFile('foto_barang')) {
            foreach ($request->file('foto_barang') as $file) {
                // Simpan setiap foto dan simpan path-nya ke dalam array
                $fotoPaths[] = $file->store('lelang_foto', 'public');
            }
        }

    // Simpan data lelang
    Lelang::create([
        'barang_gadai_no_bon' => $request->barang_gadai_no_bon,
        'kondisi_barang' => $request->kondisi_barang,
        'keterangan' => $request->keterangan,
        'harga_lelang' => $request->harga_lelang,
        'foto_barang' => json_encode($fotoPaths),  // Simpan array foto dalam format JSON
    ]);

    // Update status barang gadai
    BarangGadai::where('no_bon', $request->barang_gadai_no_bon)->update([
        'status' => 'Dilelang',
    ]);

    return redirect()->route('dashboard')->with('success', 'Data lelang berhasil ditambahkan.');
}






    public function edit($no_bon)
{
    $lelang = Lelang::where('barang_gadai_no_bon', $no_bon)->first();

    if (!$lelang) {
        return redirect()->route('dashboard')->with('error', 'Data lelang tidak ditemukan.');
    }

    // Decode JSON foto menjadi array
    $fotoBarang = json_decode($lelang->foto_barang, true) ?? [];

    // Kirim ke view
    return view('lelang.edit', compact('lelang', 'fotoBarang'));
}


public function update(Request $request, $no_bon)
{
    $request->validate([
        'kondisi_barang' => 'required',
        'keterangan' => 'nullable',
        'harga_lelang' => 'required|numeric',
        'foto_barang' => 'nullable|array',
        'foto_barang.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $lelang = Lelang::where('barang_gadai_no_bon', $no_bon)->first();
    if (!$lelang) {
        return redirect()->route('dashboard')->with('error', 'Data lelang tidak ditemukan.');
    }

    // Ambil data foto lama dari database
    $fotoPaths = json_decode($lelang->foto_barang, true) ?? [];

    // Hapus foto yang dicentang oleh user
    if ($request->has('delete_foto')) {
        foreach ($request->delete_foto as $foto) {
            if (in_array($foto, $fotoPaths)) {
                if (Storage::exists('public/' . $foto)) {
                    Storage::delete('public/' . $foto);
                }
                $fotoPaths = array_diff($fotoPaths, [$foto]);
            }
        }
    }

    // Jika ada upload foto baru
    if ($request->hasFile('foto_barang')) {
        foreach ($request->file('foto_barang') as $file) {
            $filePath = $file->store('lelang_foto', 'public');
            $fotoPaths[] = $filePath;
        }
    }

    // Update data lelang
    $lelang->kondisi_barang = $request->kondisi_barang;
    $lelang->keterangan = $request->keterangan;
    $lelang->harga_lelang = $request->harga_lelang;
    $lelang->foto_barang = json_encode($fotoPaths);
    $lelang->save();

    return redirect()->route('dashboard')->with('success', 'Data lelang berhasil diperbarui.');
}





}
