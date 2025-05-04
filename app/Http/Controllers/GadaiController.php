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
use App\Models\BungaTenor; 
use App\Models\Transaksi;


class GadaiController extends Controller
{

    public function index()
{
    $barangGadai = BarangGadai::with(['kategori', 'nasabah'])->get();
    return view('barang_gadai.index', compact('barangGadai'));
}

    public function create()
    {
        $nasabah = Nasabah::all();
        $kategori = KategoriBarang::all();
        $bunga_tenors = BungaTenor::all();

        return view('transaksi_gadai.create', compact('nasabah', 'kategori', 'bunga_tenors'));

    }





public function store(Request $request)
{
    $validTenors = BungaTenor::pluck('tenor')->toArray();
    // Validasi Input SEBELUM penyimpanan data
    $request->validate([
        'nama' => 'required|string|max:255',
        'nik' => 'required|string|max:16|unique:nasabah,nik',
        'alamat' => 'required|string',
        'telepon' => 'required|string|max:15',
        'nama_barang' => 'required|string|max:255',
        'deskripsi' => 'nullable|string',
        'imei' => 'nullable|string',
        'tenor'        => 'required|in:' . implode(',', $validTenors),
        'harga_gadai' => 'required|numeric',
        'id_kategori' => 'required|integer|exists:kategori_barang,id_kategori',
    ]);

    $tenor = (int) $request->tenor;

    // Cari ID BungaTenor berdasarkan tenor
    $bungaTenor = BungaTenor::where('tenor', $tenor)->first();

    if (!$bungaTenor) {
        return back()->withErrors(['tenor' => 'Bunga untuk tenor ini belum diatur.']);
    }

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
        'id_bunga_tenor' => $bungaTenor->id,
        'bunga' => $bungaTenor->bunga_percent,
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
        'bunga' => $bungaTenor->bunga_percent,
        'jatuh_tempo' => now()->addDays($tenor),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Simpan ke tabel transaksi umum
    Transaksi::create([
        'jenis_transaksi' => 'terima_gadai',
        'arah' => 'keluar', // karena uang keluar dari kas
        'nominal' => $request->harga_gadai,
    ]);


    return redirect()->route('barang_gadai.index')->with('success', 'Data berhasil disimpan!');
}




}
