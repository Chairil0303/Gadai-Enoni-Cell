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
            'foto_barang' => 'nullable|image|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_barang')) {
            $fotoPath = $request->file('foto_barang')->store('lelang_foto', 'public');
        }

        Lelang::create([
            'barang_gadai_no_bon' => $request->barang_gadai_no_bon,
            'kondisi_barang' => $request->kondisi_barang,
            'keterangan' => $request->keterangan,
            'harga_lelang' => $request->harga_lelang,
            'foto_barang' => $fotoPath,
        ]);
         BarangGadai::where('no_bon', $request->barang_gadai_no_bon)->update([
        'status' => 'Dilelang',
     ]);


        return redirect()->route('dashboard')->with('success', 'Data lelang berhasil ditambahkan.');
    }
}

