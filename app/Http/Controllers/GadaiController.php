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
use App\Helpers\ActivityLogger;

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
        $kategori_barang = KategoriBarang::all();
        $bunga_tenors = BungaTenor::all();

        return view('transaksi_gadai.create', compact('nasabah', 'kategori_barang', 'bunga_tenors'));
    }




    public function preview(Request $request)
    {

        $request->merge([
            'harga_gadai' => str_replace('.', '', $request->harga_gadai),
        ]);
        
        $validTenors = BungaTenor::pluck('tenor')->toArray();

        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:16|unique:nasabah,nik',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:15',
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'imei' => 'nullable|string',
            'tenor' => 'required|in:' . implode(',', $validTenors),
            'harga_gadai' => 'required|numeric',
            'id_kategori' => 'required|integer|exists:kategori_barang,id_kategori',
            'no_bon' => 'required|string',
        ]);

        // Simpan data ke session
        session(['preview_data' => $request->all()]);

        return redirect()->route('gadai.showPreview'); // route name, bukan file

    }

    public function showPreview()
    {
        $data = session('preview_data');

        if (!$data) {
            return redirect()->route('gadai.create')->with('error', 'Tidak ada data untuk dikonfirmasi.');
        }

        $bunga = BungaTenor::where('tenor', $data['tenor'])->first();
        $kategori_barang = KategoriBarang::find($data['id_kategori']);

        return view('transaksi_gadai.preview', compact('data', 'bunga', 'kategori_barang'));
    }



    public function store(Request $request)
    {
        $data = session('preview_data');
        if (!$data) {
            return redirect()->route('gadai.create')->with('error', 'Tidak ada data yang bisa disimpan.');
        }
    
        $bungaTenor = BungaTenor::where('tenor', (int)$data['tenor'])->first();
    
        $username = Str::of($data['nama'])->lower()->replace(' ', '') .
                    substr(preg_replace('/[^0-9]/', '', $data['telepon']), -2);
    
        $user = User::create([
            'nama' => $data['nama'],
            'email' => $data['email'] ?? null,
            'username' => $username,
            'password' => Hash::make(substr($data['nik'], 0, 6)),
            'role' => 'Nasabah',
            'id_cabang' => auth()->user()->id_cabang,
        ]);
    
        $nasabah = Nasabah::create([
            'nama' => $data['nama'],
            'nik' => $data['nik'],
            'alamat' => $data['alamat'],
            'telepon' => $data['telepon'],
            'id_user' => $user->id_users,
        ]);
    
        $barangGadai = BarangGadai::create([
            'no_bon' => $data['no_bon'],
            'id_nasabah' => $nasabah->id_nasabah,
            'id_cabang' => auth()->user()->id_cabang,
            'nama_barang' => $data['nama_barang'],
            'deskripsi' => $data['deskripsi'],
            'imei' => $data['imei'],
            'id_bunga_tenor' => $bungaTenor->id,
            'bunga' => $bungaTenor->bunga_percent,
            'tempo' => now()->addDays((int) $data['tenor']),
            'harga_gadai' => $data['harga_gadai'],
            'id_kategori' => $data['id_kategori'],
        ]);
    
        TransaksiGadai::create([
            'id_user' => auth()->user()->id_users,
            'id_nasabah' => $nasabah->id_nasabah,
            'no_bon' => $data['no_bon'],
            'tanggal_gadai' => now(),
            'jumlah_pinjaman' => $data['harga_gadai'],
            'bunga' => $bungaTenor->bunga_percent,
            'jatuh_tempo' => now()->addDays((int) $data['tenor']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Transaksi::create([
            'no_bon' => $data['no_bon'],
            'id_nasabah' => $nasabah->id_nasabah,
            'id_user' => auth()->user()->id_users,
            'id_cabang' => auth()->user()->id_cabang,
            'jenis_transaksi' => 'terima',
            'arus_kas' => 'keluar',
            'jumlah' => $data['harga_gadai'],
        ]);
        
    
        ActivityLogger::log(
            kategori: 'transaksi',
            aksi: 'terima gadai',
            deskripsi: 'Menerima gadai atas nama ' . $data['nama'] . ' dengan barang ' . $data['nama_barang'] . ' sejumlah Rp ' . number_format($data['harga_gadai']),
            dataLama: null,
            dataBaru: [
                'user' => $user,
                'nasabah' => $nasabah,
                'barang_gadai' => $barangGadai,
            ]
        );
        
        // Hapus session preview
        session()->forget('preview_data');
    
        return redirect()->route('barang_gadai.index')->with('success', 'Transaksi berhasil disimpan!');
    }
    




}
