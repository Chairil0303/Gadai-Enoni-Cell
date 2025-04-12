<?php

namespace App\Http\Controllers;use App\Models\BarangGadai;
use Illuminate\Http\Request;

class PerpanjangGadaiController extends Controller
{
    public function create()
    {
        return view('perpanjang_gadai.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_bon_lama' => 'required|exists:barang_gadai,no_bon',
            'no_bon_baru' => 'required|unique:barang_gadai,no_bon',
            'tenor' => 'required|in:7,14,30',
            'harga_gadai' => 'required|numeric|min:0',
            'bunga' => 'required|numeric|min:0',
        ]);

        $lama = BarangGadai::where('no_bon', $request->no_bon_lama)->firstOrFail();

        // Hitung tempo baru berdasarkan tenor
        $tempo_baru = now()->addDays((int) $request->tenor);

        // Update status bon lama
        $lama->update([
            'status' => 'diperpanjang',
        ]);

        // Buat bon baru
        BarangGadai::create([
            'no_bon' => $request->no_bon_baru,
            'id_nasabah' => $lama->id_nasabah,
            'nama_barang' => $lama->nama_barang,
            'deskripsi' => $lama->deskripsi,
            'imei' => $lama->imei,
            'tenor' => $request->tenor,
            'tempo' => $tempo_baru,
            'telat' => 0,
            'harga_gadai' => $request->harga_gadai,
            'bunga' => $request->bunga,
            'status' => 'Tergadai',
            'id_kategori' => $lama->id_kategori,
        ]);

        // Simpan ke histori perpanjangan
        \App\Models\PerpanjanganGadai::create([
            'no_bon_lama' => $request->no_bon_lama,
            'no_bon_baru' => $request->no_bon_baru,
            'tenor_baru' => $request->tenor,
            'harga_gadai_baru' => $request->harga_gadai,
            'bunga_baru' => $request->bunga,
            'tempo_baru' => $tempo_baru,
        ]);

        return redirect()->route('barang_gadai.index')->with('success', 'Perpanjangan berhasil disimpan.');
    }


    public function konfirmasi(Request $request)
    {
        $request->validate([
            'no_bon_lama' => 'required|string',
            'no_bon_baru' => 'required|string',
            'tenor' => 'required|integer|in:7,14,30',
            'harga_gadai' => 'required|numeric|min:0',
        ]);

        $lama = BarangGadai::where('no_bon', $request->no_bon_lama)->first();

        if (!$lama) {
            return redirect()->back()->with('error', 'Data dengan bon lama tidak ditemukan.');
        }

        // Ambil data nasabah
        $nasabah = \App\Models\Nasabah::where('id_nasabah', $lama->id_nasabah)->first();

        // Hitung bunga
        $bunga_persen = match ((int) $request->tenor) {
            7 => 0.05,
            14 => 0.10,
            30 => 0.15,
            default => 0,
        };
        $bunga_baru = $request->harga_gadai * $bunga_persen;

        $total_lama = $lama->harga_gadai + $lama->bunga;
        $total_baru = $request->harga_gadai + $bunga_baru;
        $total_tagihan = $total_lama + $total_baru;

        $baru = [
        'no_bon' => $request->no_bon_baru,
        'tenor' => $request->tenor,
        'harga_gadai' => $request->harga_gadai,
        'bunga' => $bunga_baru,
    ];


        return view('perpanjang_gadai.detail', [
            'lama' => $lama,
            'nasabah' => $nasabah,
            'no_bon_baru' => $request->no_bon_baru,
            'tenor' => $request->tenor,
            'harga_gadai' => $request->harga_gadai,
            'bunga_baru' => $bunga_baru,
            'total_lama' => $total_lama,
            'total_baru' => $total_baru,
            'total_tagihan' => $total_tagihan,
            'baru' => $baru,
        ]);
    }





}
