<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangGadai;
use App\Models\TransaksiTebus;
use Midtrans\Config;
use Midtrans\Snap;
use Carbon\Carbon;
use Auth;
use App\Models\Nasabah;

class TebusGadaiNasabahController extends Controller
{
    public function cari(Request $request)
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

        // Hitung Total Tebus
        $totalTebus = $barangGadai->harga_gadai + $bunga + $denda;

        return view('nasabah.konfirmasi', compact('barangGadai', 'nasabah', 'denda', 'totalTebus','bungaPersen','bunga'));
    }

  
public function konfirmasi($no_bon)
{
    $userId = auth()->id();

    // Ambil id_nasabah berdasarkan id_user
    $nasabah = Nasabah::where('id_user', $userId)->first();

    $barangGadai = BarangGadai::where('no_bon', $no_bon)
        ->where('id_nasabah', $nasabah->id_nasabah)
        ->where('status', 'tergadai')
        ->with('nasabah')
        ->first();


    if (!$barangGadai) {
        abort(404, 'Barang gadai tidak ditemukan atau tidak cocok dengan akun nasabah.');
    }
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
    // Hitung bunga, denda, dll
    $denda = $barangGadai->telat > 0 ? ($barangGadai->telat * 5000) : 0;
    $totalTebus = $barangGadai->harga_gadai + $bunga + $denda;

    return view('nasabah.konfirmasi', compact('barangGadai', 'bunga', 'bungaPersen', 'denda', 'totalTebus'));
}



public function konfirmasiJson($no_bon)
{
    $userId = auth()->user()->id_users;
    $nasabah = Nasabah::where('id_user', $userId)->first();

    $barangGadai = BarangGadai::where('no_bon', $no_bon)
        ->where('id_nasabah', $nasabah->id_nasabah)
        ->with('nasabah')
        ->first();

    if (!$barangGadai) {
        return response()->json(['message' => 'Barang gadai tidak ditemukan'], 404);
    }

    $bungaPersen = 1;
    $bunga = ($barangGadai->harga_gadai * $bungaPersen) / 100;
    $denda = $barangGadai->telat > 0 ? ($barangGadai->telat * 5000) : 0;
    $totalTebus = $barangGadai->harga_gadai + $bunga + $denda;

    return response()->json([
        'barang_gadai' => $barangGadai,
        'bunga' => $bunga,
        'bunga_persen' => $bungaPersen,
        'denda' => $denda,
        'total_tebus' => $totalTebus,
    ]);
}


}
