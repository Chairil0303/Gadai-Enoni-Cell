<?php

namespace App\Http\Controllers;use App\Models\BarangGadai;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Nasabah;
use App\Models\TransaksiTebus;
use Midtrans\Config;
use Midtrans\Snap;
use Auth;
use App\Models\PendingPayment;
use Illuminate\Support\Str;

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

        return view('nasabah.detailPerpanjangGadai', compact('barangGadai', 'nasabah', 'denda', 'totalPerpanjang','bungaPersen','bunga'));
    }
    public function konfirmasi(Request $request)
{
    $query = BarangGadai::where('status', 'tergadai');

    $tenor = $request->query('tenor');
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

    // Ambil data nasabah
    $nasabah = Nasabah::find($barangGadai->id_nasabah);

    // Hitung denda (1% dari harga gadai dikali hari telat)
    $denda = ($barangGadai->harga_gadai * 0.01) * $barangGadai->telat;

    // Hitung bunga berdasarkan tenor baru
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

    // Hitung total perpanjang berdasarkan tipe
    if ($type === 'cicil') {
        $hargaGadai = $barangGadai->harga_gadai;
        $hargaGadaiBaru = $hargaGadai -$cicilan + $bunga + $denda;
        $totalPerpanjang = $bunga + $denda;
    } else {
        $totalPerpanjang = $bunga + $denda;
    }

    return view('nasabah.konfirmasiPerpanjangGadai', compact(
        'barangGadai',
        'tenor',
        'cicilan',
        'type',
        'nasabah',
        'denda',
        'bunga',
        'bungaPersen',
        'totalPerpanjang'
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

        // Hitung Bunga berdasarkan tenor
        $tenor = $barangGadai->tenor;
        switch ($tenor) {
            case 7:
                $bungaPersen = 5;
                break;
            case 14:
                $bungaPersen = 10;
                break;
            case 30:
                $bungaPersen = 15;
                break;
            default:
                $bungaPersen = 0;
                break;
        }

        // Jika jenis pembayaran adalah perpanjang
        $bunga = $barangGadai->harga_gadai * ($bungaPersen / 100);
        $denda = $barangGadai->telat * ($barangGadai->harga_gadai * 0.01);
        $cicilan = $request->input('cicilan', 0); // pastikan ini tetap di atas
        $totalPerpanjang = ($barangGadai->harga_gadai * ($bungaPersen / 100) + $denda) - $cicilan;
        if ($totalPerpanjang < 0) $totalPerpanjang = 0;



        // Midtrans config
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $orderId = $barangGadai->no_bon . '-' . strtoupper(Str::random(6)) . '-perpanjang';


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

        $newBon = $barangGadai->no_bon . '-' . strtoupper(Str::random(2));

        $hargaGadaiBaru = $barangGadai->harga_gadai -$cicilan;
        // simpan bon baru dengan status tergadai
        BarangGadai::create([
            'no_bon' => $newBon,
            'id_nasabah' => $barangGadai->id_nasabah,
            'nama_barang' => $barangGadai->nama_barang,
            'deskripsi' => $barangGadai->deskripsi,
            'imei' => $barangGadai->imei,
            'tenor' => $barangGadai->tenor,
            'tempo' => $barangGadai->tempo,
            'telat' => 0,
            'harga_gadai' => $barangGadai->harga_gadai, // total_baru = harga_gadai + bunga
            'bunga' => $barangGadai->bunga,
            'status' => 'Tergadai',
            'id_kategori' => $barangGadai->id_kategori,
            'id_cabang' => $barangGadai->id_cabang,
        ]);

        // Simpan data transaksi pending
        PendingPayment::create([
            'order_id' => $orderId,
            'no_bon' => $barangGadai->no_bon,
            'id_nasabah' => $nasabah->id_nasabah,
            'id_cabang' => optional($nasabah->user->cabang)->id_cabang,
            'jumlah_pembayaran' => (int) $totalPerpanjang,
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
