<?php

namespace App\Http\Controllers;

use App\Models\BarangGadai;
use App\Models\Nasabah;
use Illuminate\Http\Request;
use App\Models\TransaksiTebus;
use Carbon\Carbon;

class TebusGadaiController extends Controller
{
    public function index()
    {
        return view('tebus_gadai.index'); // Pastikan kamu sudah membuat file view `tebus_gadai/index.blade.php`
    }

    public function cari(Request $request)
    {
        $query = BarangGadai::query();

        if ($request->has('no_bon')) {
            $query->where('no_bon', $request->input('no_bon'));
        }

        if ($request->has('nama_nasabah')) {
            $query->whereHas('nasabah', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->input('nama_nasabah') . '%');
            });
        }

        $barangGadai = $query->first();

        if (!$barangGadai) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Ambil data nasabah terkait
        $nasabah = Nasabah::find($barangGadai->id_nasabah);

        // Hitung Denda (1% dari harga gadai dikali hari telat)
        $denda = ($barangGadai->harga_gadai * 0.01) * $barangGadai->telat;

         // Hitung Bunga Berdasarkan Tenor
        if ($barangGadai->tenor == 7) {
            $bungaPersen = 5;
        } elseif ($barangGadai->tenor == 14) {
            $bungaPersen = 10;
        } elseif ($barangGadai->tenor == 30) {
            $bungaPersen = 15;
        } else {
            $bungaPersen = 0; // Kalau tenor tidak sesuai
        }

        $bunga = $barangGadai->harga_gadai * ($bungaPersen / 100);

        // Hitung Total Tebus
        $totalTebus = $barangGadai->harga_gadai + $bunga + $denda;

        return view('tebus_gadai.konfirmasi', compact('barangGadai', 'nasabah', 'denda', 'totalTebus','bungaPersen','bunga'));
    }

    public function tebus(Request $request, $noBon)
    {
        // Ambil data barang gadai berdasarkan noBon
        $barangGadai = BarangGadai::where('no_bon', $noBon)->first();

        if (!$barangGadai) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Hitung Denda
        $denda = ($barangGadai->harga_gadai * 0.01) * $barangGadai->telat;

        // Hitung Bunga Berdasarkan Tenor
        if ($barangGadai->tenor == 7) {
            $bungaPersen = 5;
        } elseif ($barangGadai->tenor == 14) {
            $bungaPersen = 10;
        } elseif ($barangGadai->tenor == 30) {
            $bungaPersen = 15;
        } else {
            $bungaPersen = 0;
        }

        $bunga = $barangGadai->harga_gadai * ($bungaPersen / 100);
        $totalTebus = $barangGadai->harga_gadai + $bunga + $denda;

        // Simpan transaksi tebus
        TransaksiTebus::create([
            'no_bon' => $barangGadai->no_bon,
            'id_nasabah' => $barangGadai->id_nasabah,
            'tanggal_tebus' => Carbon::now(),
            'jumlah_pembayaran' => $totalTebus,
            'status' => 'Berhasil',
        ]);

        // Update status barang menjadi 'Ditebus'
        $barangGadai->status = 'Ditebus';
        $barangGadai->save();

        return redirect()->route('barang_gadai.index')->with('success', 'Barang berhasil ditebus dan transaksi dicatat.');
    }

}
