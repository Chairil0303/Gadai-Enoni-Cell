<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\BarangGadai;

class PerpanjangGadaiController extends Controller
{
    public function create()
    {
        return view('perpanjang_gadai.create');
    }

    public function proses(Request $request)
    {
        $request->validate([
            'no_bon_lama' => 'required',
            'no_bon_baru' => 'required',
        ]);

        return redirect()->route('perpanjang_gadai.detail', [
            'no_bon_lama' => $request->no_bon_lama,
            'no_bon_baru' => $request->no_bon_baru,
        ]);
    }

    public function detail(Request $request)
    {
        $no_bon_lama = $request->no_bon_lama;
        $no_bon_baru = $request->no_bon_baru;

        $data = BarangGadai::where('no_bon', $no_bon_lama)->first();

        if (!$data) {
            return redirect()->route('perpanjang_gadai.create')->with('error', 'No. Bon tidak ditemukan.');
        }

        return view('perpanjang_gadai.detail', compact('data', 'no_bon_baru'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'no_bon_lama' => 'required|exists:barang_gadai,no_bon',
            'no_bon_baru' => 'required|unique:barang_gadai,no_bon',
            'tenor' => 'required|in:7,14,30',
            'harga_gadai' => 'required|numeric|min:0',
        ]);

        $lama = BarangGadai::where('no_bon', $request->no_bon_lama)->first();

        $bunga = match($request->tenor) {
            7 => 0.05 * $request->harga_gadai,
            14 => 0.10 * $request->harga_gadai,
            30 => 0.15 * $request->harga_gadai,
        };

        BarangGadai::create([
            'no_bon' => $request->no_bon_baru,
            'id_nasabah' => $lama->id_nasabah,
            'nama_barang' => $lama->nama_barang,
            'deskripsi' => $lama->deskripsi,
            'imei' => $lama->imei,
            'tenor' => $request->tenor,
            'tempo' => now()->addDays($request->tenor),
            'telat' => 0,
            'harga_gadai' => $request->harga_gadai,
            'bunga' => $bunga,
            'status' => 'gadai',
            'id_kategori' => $lama->id_kategori,
        ]);

        return redirect()->route('perpanjang_gadai.create')->with('success', 'Perpanjangan berhasil disimpan.');
    }
}
