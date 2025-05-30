<?php

namespace App\Http\Controllers;

use App\Models\BarangGadai;
use App\Models\Nasabah;
use App\Models\BungaTenor;
use Illuminate\Http\Request;
use App\Models\TransaksiTebus;
use Carbon\Carbon;
use App\Models\Transaksi;
use App\Models\Lelang;
use App\Helpers\ActivityLogger;

class TebusGadaiController extends Controller
{
    public function index()
    {
        return view('tebus_gadai.index'); // Pastikan kamu sudah membuat file view `tebus_gadai/index.blade.php`
    }

    private function hitungBunga($barangGadai)
    {
        $bungaTenor = $barangGadai->bungaTenor;

        if ($bungaTenor) {
            $bungaPersen = $bungaTenor->bunga_percent;
            $bunga = $barangGadai->harga_gadai * ($bungaPersen / 100);
        } else {
            $bungaPersen = 0;
            $bunga = 0;
        }

        return [
            'bunga' => $bunga,
            'bungaPersen' => $bungaPersen,
        ];
    }

public function cari(Request $request)
{
    $user = auth()->user();

    $query = BarangGadai::query();

    if ($user->id_users != 1) { // jika bukan superadmin
        $query->where('id_cabang', $user->id_cabang);
    }

    if ($request->has('no_bon')) {
        $query->where('no_bon', $request->input('no_bon'));
    }

    if ($request->has('nama_nasabah')) {
        $query->whereHas('nasabah', function ($q) use ($request) {
            $q->where('nama', 'like', '%' . $request->input('nama_nasabah') . '%');
        });
    }

    $barangGadai = $query->with('bungaTenor')->first();

    if (!$barangGadai) {
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }

        // ⛔️ Cek cabang barang dan user
        if ($barangGadai->id_cabang !== auth()->user()->id_cabang) {
            return redirect()->back()->with('error', 'No Bon milik cabang lain.');
        }

    // Cek apakah barang gadai sudah ditebus
    if ($barangGadai->status == 'Ditebus') {
        return redirect()->back()->with('error', 'Barang gadai dengan no bon ' . $barangGadai->no_bon . ' sudah ditebus.');
    }

    // Cek apakah cabang sesuai dengan cabang user
    if ($barangGadai->id_cabang != $user->id_cabang) {
        return redirect()->back()->with('error', 'Anda mencoba mengakses barang gadai dari cabang yang salah.');
    }

    $nasabah = Nasabah::find($barangGadai->id_nasabah);
    $denda = ($barangGadai->harga_gadai * 0.01) * $barangGadai->telat;
    $hasilBunga = $this->hitungBunga($barangGadai);
    $bunga = $hasilBunga['bunga'];
    $bungaPersen = $hasilBunga['bungaPersen'];
    $totalTebus = $barangGadai->harga_gadai + $bunga + $denda;

    return view('tebus_gadai.konfirmasi', compact('barangGadai', 'nasabah', 'denda', 'totalTebus', 'bungaPersen', 'bunga'));
}

    public function tebus(Request $request, $noBon)
    {
        // Ambil data barang gadai berdasarkan noBon
        $barangGadai = BarangGadai::with('bungaTenor')->where('no_bon', $noBon)->first();


        if (!$barangGadai) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // ⛔️ Cek cabang barang dan user
        if ($barangGadai->id_cabang !== auth()->user()->id_cabang) {
            return redirect()->back()->with('error', 'Barang ini bukan milik cabang Anda.');
        }

        // Hitung Denda
        $denda = ($barangGadai->harga_gadai * 0.01) * $barangGadai->telat;

        // Hitung Bunga dan ambil persentasenya
        $hasilBunga = $this->hitungBunga($barangGadai);
        $bunga = $hasilBunga['bunga'];
        $bungaPersen = $hasilBunga['bungaPersen'];

        $totalTebus = $barangGadai->harga_gadai + $bunga + $denda;

        // Simpan transaksi tebus
        TransaksiTebus::create([
            'no_bon' => $barangGadai->no_bon,
            'id_user' => auth()->id(),
            'id_nasabah' => $barangGadai->id_nasabah,
            'tanggal_tebus' => Carbon::now(),
            'id_cabang' => auth()->user()->id_cabang,
            'jumlah_pembayaran' => $totalTebus,
            'status' => 'Berhasil',
        ]);

        Transaksi::create([
            'no_bon' => $barangGadai->no_bon,
            'id_nasabah' => $barangGadai->id_nasabah,
            'id_cabang' => auth()->user()->id_cabang,
            'jenis_transaksi' => 'tebus',
            'jumlah' => $totalTebus,
            'arus_kas' => 'masuk',
            'id_user' => auth()->id(),
        ]);
        
        // Update status barang menjadi 'Ditebus'
        $barangGadai->status = 'Ditebus';
        $barangGadai->save();

        // 🔥 Update status lelang jadi 'Tebus'
        Lelang::where('barang_gadai_no_bon', $barangGadai->no_bon)
        ->update(['status' => 'Tebus']);

        ActivityLogger::log(
            kategori: 'transaksi',
            aksi: 'tebus gadai',
            deskripsi: 'Penebusan barang dengan no bon ' . $barangGadai->no_bon . ' atas nama ' . $barangGadai->nasabah->nama . ' sejumlah Rp ' . number_format($totalTebus),
            dataLama: [
                'status_barang' => 'Belum ditebus',
            ],
            dataBaru: [
                'status_barang' => 'Ditebus',
                'jumlah_tebus' => $totalTebus,
                'bunga' => $bunga,
                'denda' => $denda,
            ]
        );
        

        return redirect()->route('barang_gadai.index')->with('success', 'Barang berhasil ditebus dan transaksi dicatat.');
    }

}
