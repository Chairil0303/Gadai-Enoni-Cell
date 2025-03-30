<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nasabah;
use App\Models\BarangGadai;
use App\Models\KategoriBarang;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class GadaiController extends Controller
{

    public function index()
{
    $barangGadai = BarangGadai::with(['kategori', 'nasabah'])->get();
    return view('barang_gadai.index', compact('barangGadai'));
}

    public function create()
{
    $barangGadai = BarangGadai::with(['kategori', 'nasabah'])->get();
    $nasabah = Nasabah::all();
    $kategori = KategoriBarang::all();
    return view('transaksi_gadai.create', [
        'nasabah' => $nasabah,
        'kategori_barang' => $kategori // Ubah nama variabel yang dikirim ke Blade
    ]);
}




public function store(Request $request)
{
    // Validasi Input SEBELUM penyimpanan data
    $request->validate([
        'nama' => 'required|string|max:255',
        'nik' => 'required|string|max:16|unique:nasabah,nik',
        'alamat' => 'required|string',
        'telepon' => 'required|string|max:15',
        'nama_barang' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'imei' => 'nullable|string',
        'tenor' => 'required|integer|in:7,14,30', // ✅ Pastikan tenor adalah angka yang valid
        'harga_gadai' => 'required|numeric',
        'id_kategori' => 'required|integer|exists:kategori_barang,id_kategori',
    ]);

    // Konversi tenor ke integer (untuk menghindari error di Carbon)
    $tenor = (int) $request->tenor;

    // Buat User Baru
    $user = User::create([
        'nama' => $request->nama,
        'email' => $request->email ?? null,
        'username' => $request->nama,
        'password' => Hash::make(substr($request->nik, 0, 6)), // Ambil 6 karakter pertama dari NIK
        'role' => 'Nasabah',
    ]);

    // Simpan Data Nasabah dengan ID User
    $nasabah = Nasabah::create([
        'nama' => $request->nama,
        'nik' => $request->nik,
        'alamat' => $request->alamat,
        'telepon' => $request->telepon,
        'id_user' => $user->id_users, // Sesuai dengan primary key di tabel users
    ]);

    // Simpan Data Barang Gadai
    BarangGadai::create([
        'no_bon' => 'BON-' . time(), // Nomor bon bisa digenerate sesuai kebutuhan
        'id_nasabah' => $nasabah->id_nasabah,
        'nama_barang' => $request->nama_barang,
        'deskripsi' => $request->deskripsi,
        'imei' => $request->imei,
        'tenor' => $tenor, // ✅ Pastikan tenor sudah integer
        'tempo' => now()->addDays($tenor), // ✅ Tidak error karena sudah integer
        'harga_gadai' => $request->harga_gadai,
        'id_kategori' => $request->id_kategori,
        'id_user' => auth()->user()->id_users,  //ngambil data user yang login
    ]);

    // Redirect ke halaman daftar barang gadai dengan pesan sukses
    return redirect()->route('barang_gadai.index')->with('success', 'Data berhasil disimpan!');
}



}