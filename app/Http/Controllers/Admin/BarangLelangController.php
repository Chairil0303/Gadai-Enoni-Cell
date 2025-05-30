<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangLelang;
use Illuminate\Http\Request;

class BarangLelangController extends Controller
{
    // Tampilkan barang lelang yang belum dikonfirmasi
    public function index()
    {
        $barangLelang = BarangLelang::where('status_lelang', 'Menunggu Konfirmasi')
            ->where('id_cabang', auth()->user()->id_cabang) // hanya cabang admin
            ->get();

        return view('admin.barang_lelang.index', compact('barangLelang'));
    }

    // Form konfirmasi
    public function edit($id)
    {
        $lelang = BarangLelang::findOrFail($id);
        return view('admin.barang_lelang.edit', compact('lelang'));
    }

    // Simpan konfirmasi
    public function update(Request $request, $id)
    {
        $lelang = BarangLelang::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required',
            'harga_lelang' => 'required|numeric',
            'foto' => 'nullable|image|max:2048',
        ]);

        // Simpan foto jika ada
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('barang_lelang', 'public');
            $lelang->foto = $fotoPath;
        }

        $lelang->judul = $request->judul;
        $lelang->deskripsi = $request->deskripsi;
        $lelang->harga_lelang = $request->harga_lelang;
        $lelang->status_lelang = 'Aktif';
        $lelang->save();

        // Update status di barang_gadai
        $lelang->barangGadai->update(['status' => 'Dilelang']);

        return redirect()->route('admin.barang_lelang.index')->with('success', 'Barang berhasil dikonfirmasi sebagai lelang.');
    }
}

