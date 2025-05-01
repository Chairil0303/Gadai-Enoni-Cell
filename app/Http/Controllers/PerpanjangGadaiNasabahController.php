<?php

namespace App\Http\Controllers;use App\Models\BarangGadai;
use App\Models\PerpanjanganGadai;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Nasabah;
use App\Models\TransaksiTebus;
use Midtrans\Config;
use Midtrans\Snap;
use Auth;
use App\Models\PendingPayment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use App\Models\Cabang;

class perpanjangGadaiNasabahController extends Controller
{
    public function details($no_bon)
    {
        // Ambil data barang gadai dengan no_bon
        $barangGadai = BarangGadai::where('status', 'tergadai')
                                   ->where('no_bon', $no_bon)
                                   ->first();

        // Cek apakah barang gadai ditemukan
        if (!$barangGadai) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Ambil data nasabah terkait
        $nasabah = Nasabah::find($barangGadai->id_nasabah);

        // Hitung Denda dan Bunga
        $denda = ($barangGadai->harga_gadai * 0.01) * $barangGadai->telat;
        $bungaPersen = ($barangGadai->tenor == 7) ? 5 : (($barangGadai->tenor == 14) ? 10 : (($barangGadai->tenor == 30) ? 15 : 0));
        $bunga = $barangGadai->harga_gadai * ($bungaPersen / 100);
        $totalPerpanjang = $barangGadai->harga_gadai + $bunga + $denda;

        // Kirim data ke view
        return view('nasabah.detailPerpanjangGadai', compact('barangGadai', 'nasabah', 'denda', 'totalPerpanjang', 'bungaPersen', 'bunga'));
    }



    public function konfirmasi(Request $request)
    {
        $query = BarangGadai::where('status', 'tergadai');

        $tenor = (int)$request->query('tenor');
        $cicilan = $request->query('cicilan');
        $type = $request->query('type'); // 'biasa' atau 'cicil'

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

        $nasabah = Nasabah::find($barangGadai->id_nasabah);

        // Hitung denda (1% dari harga gadai dikali hari telat)
        $denda = ($barangGadai->harga_gadai * 0.01) * $barangGadai->telat;
        $tempobaru = Carbon::parse($barangGadai->tempo)->addDays($tenor)->translatedFormat('l, d F Y');

        if ($tenor == 7) {
            $bungaPersen = 5;
        } elseif ($tenor == 14) {
            $bungaPersen = 10;
        } elseif ($tenor == 30) {
            $bungaPersen = 15;
        } else {
            $bungaPersen = 0;
        }

        $bunga = $barangGadai->harga_gadai * ($bungaPersen / 100);

        if ($type === 'cicil') {
            $hargaGadai = $barangGadai->harga_gadai;
            $hargaGadaiBaru = $hargaGadai - $cicilan + $bunga + $denda;
            $totalPerpanjang = $bunga + $denda;
        } else {
            $totalPerpanjang = $bunga + $denda;
        }

        // ✅ Simpan data ke session
        session([
            'perpanjangan.tenor' => $tenor,
            'perpanjangan.type' => $type,
            'perpanjangan.cicilan' => $cicilan,
            'perpanjangan.no_bon' => $barangGadai->no_bon,
            'perpanjangan.total' => $totalPerpanjang
        ]);

        return view('nasabah.konfirmasiPerpanjangGadai', compact(
            'barangGadai',
            'tenor',
            'cicilan',
            'type',
            'nasabah',
            'denda',
            'bunga',
            'bungaPersen',
            'totalPerpanjang',
            'tempobaru',
        ));
    }




    public function processPayment(Request $request)
    {

        $noBon = $request->no_bon;
        $userId = auth()->user()->id_users;
        $nasabah = Nasabah::where('id_user', $userId)->first();
        if (!$nasabah) {
            return response()->json(['message' => 'Nasabah tidak ditemukan'], 404);
        }

        $barangGadai = BarangGadai::where('no_bon', $noBon)
            ->where('id_nasabah', $nasabah->id_nasabah)
            ->with('nasabah')
            ->first();

        if (!$barangGadai) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }

        // Cek apakah barang sudah ditebus
        if ($barangGadai->status === 'Ditebus') {
            return response()->json(['message' => 'Barang ini sudah ditebus sebelumnya.'], 403);
        }

        // Cek apakah sudah ada transaksi pending untuk no_bon ini
        $pendingExists = PendingPayment::where('no_bon', $noBon)
            ->where('status', 'pending')
            ->exists();

        if ($pendingExists) {
            return response()->json(['message' => 'Masih ada transaksi pembayaran yang belum selesai untuk barang ini.'], 403);
        }

        // Hitung Denda dan Bunga
        $telat = $barangGadai->telat;
        $paymentType = $request->input('payment_type', 'tebus'); // default tebus
        $bungaPersen = 0;
        $cicilan = session('perpanjangan.cicilan');
        // Hitung Bunga berdasarkan tenor
        $tenor = session('perpanjangan.tenor');
        switch ($tenor) {
            case 7:
                $bungaPersen = 5;
                $id_bunga_tenor = 1;
                break;
            case 14:
                $bungaPersen = 10;
                $id_bunga_tenor = 2;
                break;
            case 30:
                $bungaPersen = 15;
                $id_bunga_tenor = 3;
                break;
            default:
                $bungaPersen = 0;
                $id_bunga_tenor = null; // Atau bisa juga 0 jika perlu default ID
                break;
        }

        // Jika jenis pembayaran adalah perpanjang
        $bunga = $barangGadai->harga_gadai * ($bungaPersen / 100);
        $denda = $barangGadai->telat * ($barangGadai->harga_gadai * 0.01);
        // $cicilan = $request->input('cicilan', 0);
        $totalPerpanjang = ($barangGadai->harga_gadai * ($bungaPersen / 100) + $denda + $cicilan);

        // $tenor = $request->input('tenor', $barangGadai->tenor); // fallback ke tenor lama kalau tidak dikirim


        // Midtrans config
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $orderId = $barangGadai->no_bon . '-' . strtoupper(Str::random(2)) . '-perpanjang';


        // Parameter untuk Snap
        $params = [
            'transaction_details' => [

                'order_id' => $orderId,
                'gross_amount' => (int) $totalPerpanjang,

            ],
            'customer_details' => [
                'first_name' => $barangGadai->nasabah->nama,
                'phone' => $barangGadai->nasabah->telepon,
            ],
        ];

        // Mendapatkan Snap Token
        $snapToken = Snap::getSnapToken($params);
        $tempobaru=Carbon::parse($barangGadai->tempo)->addDays($tenor);



        $newBon = $barangGadai->no_bon . '-DM';


        $hargaGadaiBaru = $barangGadai->harga_gadai -$cicilan;
        // simpan bon baru dengan status tergadai
        BarangGadai::create([
            'no_bon' => $newBon,
            'no_bon_lama' => $barangGadai->no_bon,
            'id_bunga_tenor' => $id_bunga_tenor,
            'id_nasabah' => $barangGadai->id_nasabah,
            'nama_barang' => $barangGadai->nama_barang,
            'deskripsi' => $barangGadai->deskripsi,
            'imei' => $barangGadai->imei,
            'tempo' => $tempobaru,
            'telat' => 0,
            'harga_gadai' => $barangGadai->harga_gadai-$cicilan, // total_baru = harga_gadai + bunga
            'bunga' => $bungaPersen,
            'status' => 'Tergadai',
            'id_kategori' => $barangGadai->id_kategori,
            'id_cabang' => $barangGadai->id_cabang,
        ]);

        PerpanjanganGadai::create([
        'no_bon_lama' => $barangGadai->no_bon,
        'no_bon_baru' => $newBon,
        'tenor_baru' => $tenor,
        'harga_gadai_baru' => $barangGadai->harga_gadai-$cicilan, // sesuai perhitungan submitForm
        'bunga_baru' => $bungaPersen,
        'tempo_baru' => $tempobaru,
    ]);

        // Simpan data transaksi pending
        PendingPayment::create([
            'order_id' => $orderId,
            'no_bon' => $barangGadai->no_bon,
            'id_nasabah' => $nasabah->id_nasabah,
            'id_cabang' => optional($nasabah->user->cabang)->id_cabang,
            'jumlah_pembayaran' => (int) $totalPerpanjang,
            'new_bon' => $newBon,
            'status' => 'pending',
        ]);

        return response()->json([
            'snap_token' => $snapToken,
            'total_bayar' => $totalPerpanjang,
            'order_id' => $orderId,
            'detail' => [
                'harga_gadai' => $barangGadai->harga_gadai,
                'bunga' => $bunga,
                'denda' => $denda,
                'telat' => $telat,
                'tenor' => $tenor,
                'nama_nasabah' => $barangGadai->nasabah->nama,
                'telepon' => $barangGadai->nasabah->telepon,
            ]
        ]);
    }



}

// perpanjang gadai nasabah
