<?php

namespace App\Http\Controllers;use App\Models\BarangGadai;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Nasabah;

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
        $tempo_baru = Carbon::parse($lama->tempo)->addDays((int) $request->tenor);


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
            'harga_gadai' =>$lama->harga_gadai + $request->harga_gadai,
            'bunga' => $request->bunga,
            'status' => 'Tergadai',
            'id_kategori' => $lama->id_kategori,
            'id_cabang' => auth()->user()->id_cabang,

        ]);

        // Simpan ke histori perpanjangan
        \App\Models\PerpanjanganGadai::create([
            'no_bon_lama' => $request->no_bon_lama,
            'no_bon_baru' => $request->no_bon_baru,
            'tenor_baru' => $request->tenor,
            'harga_gadai_baru' =>$lama->harga_gadai + $request->harga_gadai,
            'bunga_baru' => $request->bunga,
            'tempo_baru' => $tempo_baru,
            'id_cabang' => auth()->user()->id_cabang,

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

        $lama = BarangGadai::where('no_bon', $request->no_bon_lama)
        ->where('id_cabang', auth()->user()->id_cabang) // Hanya ambil jika sesuai cabang
        ->first();

        if (!$lama) {
            return redirect()->back()->with('error', 'No Bon Lama tidak ditemukan atau bukan milik cabang Anda.');
        }

        // Cek apakah no_bon_lama valid dan statusnya masih tergadai
        if ($lama->status !== 'Tergadai') {
            if ($lama->status === 'Diperpanjang') {
                return redirect()->back()->with('error', 'Barang dengan No Bon Lama sudah diperpanjang sebelumnya.');
            }

            return redirect()->back()->with('error', 'Barang dengan No Bon Lama sudah tidak dalam status "Tergadai".');
        }

        // Cek apakah no_bon_baru sudah digunakan
        $cekBonBaru = BarangGadai::where('no_bon', $request->no_bon_baru)
        ->where('id_cabang', auth()->user()->id_cabang)
        ->first();


        // Ambil data nasabah
        $nasabah = Nasabah::where('id_nasabah', $lama->id_nasabah)->first();


        // Hitung Denda Keterlambatan
        $denda_lama = ($lama->harga_gadai * 0.01) * $lama->telat;


        // Hitung bunga
        $bunga_persen = match ((int) $request->tenor) {
            7 => 0.05,
            14 => 0.10,
            30 => 0.15,
            default => 0,
        };
        $bunga_baru = $request->harga_gadai * $bunga_persen;

        $total_lama = $lama->harga_gadai + $lama->bunga + $denda_lama;
        $total_baru = $request->harga_gadai + $bunga_baru;
        $total_tagihan = $total_lama + $total_baru;


        $tempo_baru = Carbon::parse($lama->tempo)->addDays((int) $request->tenor);

        $baru = [
        'no_bon' => $request->no_bon_baru,
        'tenor' => $request->tenor,
        'harga_gadai' => $request->harga_gadai,
        'bunga' => $bunga_baru,
        'tempo' => $tempo_baru->format('Y-m-d'),
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
            'denda_lama' => $denda_lama,
            'baru' => $baru,
        ]);
    }





}
