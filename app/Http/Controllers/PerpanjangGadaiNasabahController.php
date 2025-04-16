<?php

namespace App\Http\Controllers;use App\Models\BarangGadai;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Nasabah;
use App\Models\TransaksiTebus;
use Midtrans\Config;
use Midtrans\Snap;
use Auth;

class perpanjangGadaiNasabahController extends Controller
{
    public function details(Request $request)
    {
        // $query = BarangGadai::query();
        $query = BarangGadai::where('status', 'tergadai');


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

        // Hitung Total Perpanjang
        $totalPerpanjang=  $barangGadai->harga_gadai * ($bungaPersen / 100) +$denda ;
        $totalTebus = $barangGadai->harga_gadai + $bunga + $denda;

        return view('nasabah.detailPerpanjangGadai', compact('barangGadai', 'nasabah', 'denda', 'totalTebus', 'totalPerpanjang','bungaPersen','bunga'));
    }



    public function handleNotification(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $signatureKey = hash('sha512', $request->order_id.$request->status_code.$request->gross_amount.$serverKey);

        if ($signatureKey !== $request->signature_key) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        if ($request->transaction_status == 'settlement') {
            $transaksi = TransaksiTebus::where('order_id', $request->order_id)->first();
            if ($transaksi) {
                $barang = BarangGadai::where('no_bon', $transaksi->no_bon)->first();
                if ($barang) {
                    $barang->status = 'Ditebus';
                    $barang->save();
                }
            }
        }

        return response()->json(['success' => true]);
    }
}

// perpanjang gadai nasabah
