<?php

namespace App\Http\Controllers;
use App\Models\BarangGadai;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;
use App\Models\TransaksiTebus;
use Illuminate\Support\Carbon;
use Auth;
use App\Models\Nasabah;
use App\Models\Cabang;
use App\Models\TransaksiGadai;
use App\Models\LelangBarang;
use App\Helpers\WhatsappHelper;
use Illuminate\Support\Str;
use App\Models\PendingPayment;



class NasabahPaymentController extends Controller
{
    public function __construct()
    {
        // Mengonfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = false; // Atur ke true jika sudah di produksi
        Config::$isSanitized = true;
        Config::$is3ds = true;
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
        $totalPerpanjang=  $barangGadai->harga_gadai * ($bungaPersen / 100) +$denda ;
        $totalTebus = $barangGadai->harga_gadai + $bunga + $denda;


        if ($paymentType === 'perpanjang') {
            // Total pembayaran perpanjangan (bunga + denda)
            $totalBayar = $denda;
        } else {
            // Total pembayaran penebusan (harga_gadai + bunga + denda)
            $totalBayar = $totalTebus;
        };

        // Midtrans config
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $orderId = $barangGadai->no_bon . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $totalTebus,
            ],
            'customer_details' => [
                'first_name' => $barangGadai->nasabah->nama,
                'phone' => $barangGadai->nasabah->telepon,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

            // Simpan data transaksi pending
            PendingPayment::create([
                'order_id' => $orderId,
                'no_bon' => $barangGadai->no_bon,
                'id_nasabah' => $nasabah->id_nasabah,
                'id_cabang' => optional($nasabah->user->cabang)->id_cabang,
                'jumlah_pembayaran' => (int) $totalBayar,
                'status' => 'pending',
            ]);


        return response()->json([
            'snap_token' => $snapToken,
            'total_tebus' => $totalBayar,
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


        // pemisah
        public function processPaymentJson(Request $request)
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
            $totalPerpanjang=  $barangGadai->harga_gadai * ($bungaPersen / 100) +$denda ;
            $totalTebus = $barangGadai->harga_gadai + $bunga + $denda;


            if ($paymentType === 'perpanjang') {
                // Total pembayaran perpanjangan (bunga + denda)
                $totalBayar = $totalPerpanjang;
            } else {
                // Total pembayaran penebusan (harga_gadai + bunga + denda)
                $totalBayar = $totalTebus;
            }

            // Midtrans config
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.is_3ds');

            $orderId = $barangGadai->no_bon . '-' . time();

            // Parameter untuk Snap
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $totalBayar,
                ],
                'customer_details' => [
                    'first_name' => $barangGadai->nasabah->nama,
                    'phone' => $barangGadai->nasabah->telepon,
                ],
            ];

            // Mendapatkan Snap Token
            $snapToken = Snap::getSnapToken($params);

            // Simpan data transaksi pending
            PendingPayment::create([
                'order_id' => $orderId,
                'no_bon' => $barangGadai->no_bon,
                'id_nasabah' => $nasabah->id_nasabah,
                'id_cabang' => optional($nasabah->user->cabang)->id_cabang,
                'jumlah_pembayaran' => (int) $totalBayar,
                'status' => 'pending',
            ]);

            return response()->json([
                'snap_token' => $snapToken,
                'total_bayar' => $totalBayar,
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



// ini belum ada pending payment
public function handleNotificationJson(Request $request)
{
    $data = $request->all();
    $transaction = $data['transaction_status'];
    $orderId = $data['order_id'];
    $grossAmount = $data['gross_amount'] ?? 0;

    $noBon = explode('-', $orderId)[0];

    $barang = BarangGadai::with('nasabah.user.cabang')->where('no_bon', $noBon)->first();

    if (!$barang) {
        return response()->json(['message' => 'Barang tidak ditemukan'], 404);
    }

    // Cek status transaksi dan update status pembayaran sesuai dengan kondisi
    $pending = PendingPayment::where('order_id', $orderId)->first();

    if (!$pending) {
        return response()->json(['message' => 'Transaksi tidak valid atau tidak ditemukan di pending payments.']);
    }

    // Proses jika transaksi berhasil (settlement atau capture)
    if (in_array($transaction, ['settlement', 'capture'])) {
        $barang->status = 'Ditebus';
        $barang->save();

        // Update status pending payment menjadi 'paid'
        $pending->status = 'paid';
        $pending->save();

        // Buat transaksi tebus baru
        $id_cabang = optional($barang->nasabah->user->cabang)->id_cabang;

        TransaksiTebus::create([
            'no_bon' => $barang->no_bon,
            'id_cabang' => $id_cabang,
            'id_nasabah' => $barang->id_nasabah,
            'tanggal_tebus' => Carbon::now(),
            'jumlah_pembayaran' => (int) $grossAmount,
            'status' => 'Berhasil', // Status transaksi tebus
        ]);

        // Kirim notifikasi WhatsApp ke nasabah
        $nasabah = $barang->nasabah;
        $noHp = preg_replace('/^0/', '62', $nasabah->telepon); // ubah 08xx ke 62xxx

        $message = "*📦 Transaksi Tebus Berhasil!*\n\n" .
            "🆔 No BON: {$barang->no_bon}\n" .
            "🏷 Nama Barang: {$barang->nama_barang}\n" .
            "🏦 Cabang: {$barang->nasabah->user->cabang->nama_cabang}\n" .
            "🏷 Barang: {$barang->nama_barang}\n" .
            "🏦 Cabang: {$barang->nasabah->user->cabang->nama_cabang}\n" .
            "👤 Nama: {$nasabah->nama}\n" .
            "💰 Jumlah: Rp " . number_format($grossAmount, 0, ',', '.') . "\n" .
            "📅 Tanggal: " . now()->format('d-m-Y') . "\n\n" .
            "Terima kasih telah menebus barang Anda di *Pegadaian Kami* 🙏";

        try {
            $responseWA = WhatsappHelper::send($noHp, $message);
        } catch (\Exception $e) {
            // Log error jika WA gagal dikirim
            \Log::error('Gagal kirim WA: ' . $e->getMessage());
            $responseWA = 'Gagal kirim WA';
        }
    }

    // Proses jika transaksi expired
    if ($transaction === 'expire') {
        $pending->status = 'expired';
        $pending->save();
    }

    // Proses jika transaksi dibatalkan
    if ($transaction === 'cancel') {
        $pending->status = 'cancelled';
        $pending->save();
    }

    // Proses jika transaksi masih dalam status pending
    if ($transaction === 'pending') {
        // Tidak perlu melakukan apa-apa karena sudah berstatus pending
    }

    return response()->json(['message' => 'Notifikasi diproses.', 'wa_notif' => $responseWA ?? 'Tidak ada WA']);
}


public function cancelPayment(Request $request)
{
    $orderId = $request->order_id;

    $pending = PendingPayment::where('order_id', $orderId)
        ->where('status', 'pending')
        ->first();

    if (!$pending) {
        return response()->json(['message' => 'Data tidak ditemukan atau status bukan pending'], 404);
    }

    $pending->status = 'cancelled';
    $pending->save();

    return response()->json(['message' => 'Status pembayaran diubah menjadi cancelled.']);
}

    // public function getPaymentJsonByBon($noBon)
    // {
    //     // Ambil user yang login
    //     $userId = auth()->user()->id_users;

    //     // Ambil data nasabah yang terhubung
    //     $nasabah = Nasabah::where('id_user', $userId)->first();

    //     if (!$nasabah) {
    //         return response()->json(['message' => 'Nasabah tidak ditemukan'], 404);
    //     }

    //     // Cek data barang berdasarkan no_bon dan id_nasabah
    //     $barang = BarangGadai::with('nasabah')
    //     ->whereRaw('LOWER(no_bon) = ?', [strtolower($noBon)])
    //     ->where('id_nasabah', $nasabah->id_nasabah)
    //     ->first();
    //     // $barang = BarangGadai::with('nasabah')
    //     //     ->whereRaw('LOWER(no_bon) = ?', [strtolower($noBon)])
    //     //     ->where('id_nasabah', $nasabah->id_nasabah)
    //     //     ->first();

    //     if (!$barang) {
    //         return response()->json(['message' => 'Barang tidak ditemukan'], 404);
    //     }

    //     // Hitung bunga dan denda
    //     $bungaPersen = 1;
    //     $bunga = ($barang->harga_gadai * $bungaPersen) / 100;
    //     $denda = $barang->telat > 0 ? ($barang->telat * 5000) : 0;
    //     $totalTebus = $barang->harga_gadai + $bunga + $denda;

    //     // Return JSON
    //     return response()->json([
    //         'no_bon' => $barang->no_bon,
    //         'nama_barang' => $barang->nama_barang,
    //         'harga_gadai' => $barang->harga_gadai,
    //         'bunga' => $bunga,
    //         'denda' => $denda,
    //         'total_tebus' => $totalTebus,
    //         'payment_method' => 'snap',
    //     ]);
    // }


    public function validatePending(Request $request)
{
    $orderId = $request->order_id;

    $payment = PendingPayment::where('order_id', $orderId)
        ->where('user_id', auth()->id())
        ->where('status', 'pending')
        ->first();

    return response()->json(['is_pending' => $payment ? true : false]);
}


public function processPaymentPerpanjangJson(Request $request)
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

    // Hitung Denda
    $telat = ($barangGadai->telat);


    // Hitung Bunga
    $tenor = $barangGadai->tenor;
    $bungaPersen = match ($tenor) {
        7 => 5,
        14 => 10,
        30 => 15,
        default => 0,
    };
    $bunga = $barangGadai->harga_gadai * ($bungaPersen / 100);
    $denda = $barangGadai->telat > 0 ? ($barangGadai->telat * 5000) : 0;
    $totalTebus = $barangGadai->harga_gadai + $bunga + $denda;
    $totalPerpanjang=  $barangGadai->harga_gadai * ($bungaPersen / 100) +$denda ;

    // Midtrans config
    Config::$serverKey = config('midtrans.server_key');
    Config::$isProduction = config('midtrans.is_production');
    Config::$isSanitized = config('midtrans.is_sanitized');
    Config::$is3ds = config('midtrans.is_3ds');

    $orderId = $barangGadai->no_bon . '-' . time();

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

    $snapToken = Snap::getSnapToken($params);

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
        'total_perpanjang' => $totalPerpanjang,
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


// nasabah payment
