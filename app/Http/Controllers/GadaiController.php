<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nasabah;
use App\Models\BarangGadai;
use App\Models\KategoriBarang;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\TransaksiGadai;
use Illuminate\Support\Str;

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
        'tenor' => 'required|integer|in:7,14,30',
        'harga_gadai' => 'required|numeric',
        'id_kategori' => 'required|integer|exists:kategori_barang,id_kategori',
    ]);

    $tenor = (int) $request->tenor;

    // Tentukan bunga berdasarkan tenor
    $bunga_persen = match ($tenor) {
        7 => 5,
        14 => 10,
        30 => 15,
        default => 0,
    };
    $bunga = ($request->harga_gadai * $bunga_persen) / 100;

    // Buat User Baru
    $user = User::create([
        'nama' => $request->nama,
        'email' => $request->email,
        'username' => Str::of($request->nama)->lower()->replace(' ', ''),
        'password' => Hash::make(substr($request->nik, 0, 6)),
        'role' => 'Nasabah',
        'id_cabang' => auth()->user()->id_cabang,
    ]);


    // Simpan Data Nasabah dengan ID User
    $nasabah = Nasabah::create([
        'nama' => $request->nama,
        'nik' => $request->nik,
        'alamat' => $request->alamat,
        'telepon' => $request->telepon,
        'id_user' => $user->id_users,
    ]);

    // Simpan Data Barang Gadai
    $barangGadai = BarangGadai::create([
        'no_bon' => $request->no_bon,
        'id_nasabah' => $nasabah->id_nasabah,
        'id_cabang' => auth()->user()->id_cabang,
        'nama_barang' => $request->nama_barang,
        'deskripsi' => $request->deskripsi,
        'imei' => $request->imei,
        'tenor' => $tenor,
        'bunga' => $bunga_persen,
        'tempo' => now()->addDays($tenor),
        'harga_gadai' => $request->harga_gadai,
        'id_kategori' => $request->id_kategori,
        // 'id_user' => auth()->user()->id_users,
    ]);

    // Simpan Data Transaksi Gadai
    TransaksiGadai::create([
        'id_user' => auth()->user()->id_users,
        'id_nasabah' => $nasabah->id_nasabah,
        'no_bon' => $request->no_bon,
        'tanggal_gadai' => now(),
        'jumlah_pinjaman' => $request->harga_gadai,
        'bunga' => $bunga_persen,
        'jatuh_tempo' => now()->addDays($tenor),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('barang_gadai.index')->with('success', 'Data berhasil disimpan!');
}




}
