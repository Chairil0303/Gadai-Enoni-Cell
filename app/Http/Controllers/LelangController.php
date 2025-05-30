<?php

namespace App\Http\Controllers;

use App\Models\BarangGadai;
use App\Models\Lelang;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ActivityLogger;

class LelangController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        $query = BarangGadai::with(['nasabah.user', 'kategori', 'cabang'])
            ->whereNotIn('status', ['Tebus', 'Lunas', 'Dilelang'])  // status aktif
            ->whereDate('tempo', '<', now());  // yang sudah lewat tempo

        // Jika user bukan superadmin dan ada kolom id_cabang, filter cabang
        if ($user->id !== 1 && Schema::hasColumn('barang_gadai', 'id_cabang')) {
            if (!is_null($user->id_cabang)) {
                $query->where('id_cabang', $user->id_cabang);
            }
        }

        $barangGadai = $query->get()->map(function ($barang) {
            $tempo = Carbon::parse($barang->tempo);
            $selisih = now()->diffInDays($tempo, false);

            $barang->telat = $selisih < 0 ? abs($selisih) : 0;
            $barang->sisa_hari = $selisih >= 0 ? $selisih : 0;

            return $barang;
        })->filter(function ($barang) {
            return $barang->telat > 0; // hanya yang telat
        });

        return view('nasabah.lelang.index', compact('barangGadai'));
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
            'foto_barang' => 'nullable|array',
            'foto_barang.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $fotoPaths = [];
        if ($request->hasFile('foto_barang')) {
            foreach ($request->file('foto_barang') as $file) {
                $fotoPaths[] = $file->store('lelang_foto', 'public');
            }
        }
    
        // ✅ Simpan ke dalam variabel $lelang
        $lelang = Lelang::create([
            'barang_gadai_no_bon' => $request->barang_gadai_no_bon,
            'kondisi_barang' => $request->kondisi_barang,
            'keterangan' => $request->keterangan,
            'harga_lelang' => $request->harga_lelang,
            'foto_barang' => json_encode($fotoPaths),
        ]);
    
        BarangGadai::where('no_bon', $request->barang_gadai_no_bon)->update([
            'status' => 'Dilelang',
        ]);
    
        // Logging aktivitas
        ActivityLogger::log(
            kategori: 'lelang',
            aksi: 'create',
            deskripsi: "Menambahkan data lelang untuk bon: {$lelang->barang_gadai_no_bon}",
            dataLama: null,
            dataBaru: $lelang->toArray()
        );
    
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
    
        $dataLama = $lelang->toArray(); // Simpan data sebelum update
    
        $fotoPaths = json_decode($lelang->foto_barang, true) ?? [];
    
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
    
        if ($request->hasFile('foto_barang')) {
            foreach ($request->file('foto_barang') as $file) {
                $filePath = $file->store('lelang_foto', 'public');
                $fotoPaths[] = $filePath;
            }
        }
    
        $lelang->kondisi_barang = $request->kondisi_barang;
        $lelang->keterangan = $request->keterangan;
        $lelang->harga_lelang = $request->harga_lelang;
        $lelang->foto_barang = json_encode($fotoPaths);
        $lelang->save();
    
        $dataBaru = $lelang->toArray(); // Simpan data setelah update
    
        // Logging aktivitas
        ActivityLogger::log(
            kategori: 'lelang',
            aksi: 'update',
            deskripsi: "Update data lelang untuk bon: {$no_bon}",
            dataLama: $dataLama,
            dataBaru: $dataBaru
        );
    
        return redirect()->route('dashboard')->with('success', 'Data lelang berhasil diperbarui.');
    }
    





}
