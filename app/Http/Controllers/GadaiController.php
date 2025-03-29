<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nasabah;
use App\Models\BarangGadai;
use App\Models\KategoriBarang;

class GadaiController extends Controller
{
    public function create()
{
    $nasabah = Nasabah::all();
    $kategori = KategoriBarang::all();
    return view('transaksi_gadai.create', [
        'nasabah' => $nasabah,
        'kategori_barang' => $kategori // Ubah nama variabel yang dikirim ke Blade
    ]);
}




    public function store(Request $request)
    {
        // Validasi Input
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:16|unique:nasabah,nik',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:15',
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'imei' => 'nullable|string',
            'tenor' => 'required|integer',
            'harga_gadai' => 'required|numeric',
            'id_kategori' => 'required|integer|exists:kategori_barang,id_kategori',
        ]);

        // Simpan Data Nasabah
        $nasabah = Nasabah::create([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
        ]);

        // Simpan Data Barang Gadai
        BarangGadai::create([
            'id_nasabah' => $nasabah->id, // Menggunakan ID Nasabah yang baru dibuat
            'nama_barang' => $request->nama_barang,
            'deskripsi' => $request->deskripsi,
            'imei' => $request->imei,
            'tenor' => $request->tenor,
            'harga_gadai' => $request->harga_gadai,
            'id_kategori' => $request->id_kategori,
        ]);

        // Redirect ke halaman daftar barang gadai dengan pesan sukses
        return redirect()->route('barang_gadai.index')->with('success', 'Data berhasil disimpan!');
    }
}