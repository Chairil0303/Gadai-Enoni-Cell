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
use App\Helpers\WhatsappHelper;
use Illuminate\Support\Str;
use App\Models\PendingPayment;
use App\Models\PerpanjanganGadai;
use App\Models\Transaksi;
use App\Models\LogAktivitas;


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
        $redirectUrl = "https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $snapToken;

        // Simpan data transaksi pending
        PendingPayment::create([
            'order_id' => $orderId,
            'no_bon' => $barangGadai->no_bon,
            'id_nasabah' => $nasabah->id_nasabah,
            'id_cabang' => optional($nasabah->user->cabang)->id_cabang,
            'jumlah_pembayaran' => (int) $totalBayar,
            'status' => 'pending',
            'snap_token' => $snapToken,
            'redirect_url' => $redirectUrl,
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

            // Cek transaksi pending untuk no_bon ini
            $pendingPayment = PendingPayment::where('no_bon', $noBon)
                ->where('status', 'pending')
                ->first();

            if ($pendingPayment) {
                // Jika ada transaksi pending dari nasabah yang sama
                if ($pendingPayment->id_nasabah === $nasabah->id_nasabah) {
                    return response()->json([
                        'message' => 'Anda masih memiliki transaksi pembayaran yang belum selesai untuk barang ini.',
                        'order_id' => $pendingPayment->order_id
                    ], 403);
                }

                // Jika ada transaksi pending dari nasabah lain
                return response()->json([
                    'message' => 'Barang ini sedang dalam proses pembayaran oleh nasabah lain.',
                    'status' => 'pending_other_user'
                ], 403);
            }

            // Bersihkan transaksi pending yang expired (lebih dari 24 jam)
            PendingPayment::where('no_bon', $noBon)
                ->where('status', 'pending')
                ->where('created_at', '<', now()->subHours(24))
                ->update(['status' => 'expired']);

            // Hitung Denda dan Bunga
            $telat = $barangGadai->telat;
            $paymentType = $request->input('payment_type', 'tebus');
            $bungaPersen = 0;

            $denda = $barangGadai->telat * ($barangGadai->harga_gadai * 0.01);

            $hasilBunga = $this->hitungBunga($barangGadai);
            $bunga = $hasilBunga['bunga'];
            $bungaPersen = $hasilBunga['bungaPersen'];
            $totalTebus = $barangGadai->harga_gadai + $bunga + $denda;

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
                    'gross_amount' => (int) $totalTebus,
                ],
                'customer_details' => [
                    'first_name' => $barangGadai->nasabah->nama,
                    'phone' => $barangGadai->nasabah->telepon,
                ],
            ];

            // Mendapatkan Snap Token
            $snapToken = Snap::getSnapToken($params);
            $redirectUrl = "https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $snapToken;

            // Simpan data transaksi pending
            PendingPayment::create([
                'order_id' => $orderId,
                'no_bon' => $barangGadai->no_bon,
                'id_nasabah' => $nasabah->id_nasabah,
                'id_cabang' => optional($nasabah->user->cabang)->id_cabang,
                'jumlah_pembayaran' => (int) $totalTebus,
                'status' => 'pending',
                'snap_token' => $snapToken,
                'redirect_url' => $redirectUrl,
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
            return response()->json([
                'snap_token' => $snapToken,
                'total_bayar' => $totalTebus,
                'order_id' => $orderId,
                'detail' => [
                    'harga_gadai' => $barangGadai->harga_gadai,
                    'bunga' => $bunga,
                    'denda' => $denda,
                    'telat' => $telat,
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
    $isPerpanjang = str_contains($orderId, 'perpanjang');

    $barang = BarangGadai::with('nasabah.user.cabang')->where('no_bon', $noBon)->first();
    if (!$barang) {
        return response()->json(['message' => 'Barang tidak ditemukan'], 404);
    }

    $pending = PendingPayment::where('order_id', $orderId)->first();
    if (!$pending) {
        return response()->json(['message' => 'Transaksi tidak valid atau tidak ditemukan di pending payments.']);
    }

    $user = $barang->nasabah->user ?? null;
    $kategori = $isPerpanjang ? 'perpanjang' : 'tebus';
    $dataLama = $barang->toArray();
    $dataBaru = null;
    $aksi = '';
    $deskripsi = '';

    if (in_array($transaction, ['settlement', 'capture'])) {
        $pending->status = 'paid';
        $pending->save();

        $nasabah = $barang->nasabah;
        $noHp = preg_replace('/^0/', '62', $nasabah->telepon);
        $id_cabang = optional($nasabah->user->cabang)->id_cabang;

        if ($isPerpanjang) {
            $aksi = 'perpanjang_berhasil';
            $deskripsi = 'Pembayaran perpanjang berhasil untuk bon: ' . $barang->no_bon;
            $barang->status = 'Diperpanjang';
            $barang->save();
            $dataBaru = $barang->toArray();
            // WA
            try {
                $responseWA = WhatsappHelper::send($noHp, 'perpanjang', [
                    'no_bon' => $barang->no_bon,
                    'nama_barang' => $barang->nama_barang,
                    'nama' => $nasabah->nama,
                    'jumlah' => number_format($grossAmount, 0, ',', '.'),
                    'tanggal' => now()->format('d-m-Y'),
                    'nama_cabang' => optional($barang->nasabah->user->cabang)->nama_cabang
                ]);
            } catch (\Exception $e) {
                \Log::error('Gagal kirim WA: ' . $e->getMessage());
                $responseWA = 'Gagal kirim WA';
            }
        } else {
            $aksi = 'tebus_berhasil';
            $deskripsi = 'Pembayaran tebus berhasil untuk bon: ' . $barang->no_bon;
            $barang->status = 'Ditebus';
            $barang->save();
            $dataBaru = $barang->toArray();
            // Simpan histori transaksi tebus
            TransaksiTebus::create([
                'no_bon' => $barang->no_bon,
                'id_cabang' => $id_cabang,
                'id_nasabah' => $barang->id_nasabah,
                'tanggal_tebus' => now(),
                'jumlah_pembayaran' => (int) $grossAmount,
                'status' => 'Berhasil',
            ]);
            // WA
            try {
                $responseWA = WhatsappHelper::send($noHp, 'tebus', [
                    'no_bon' => $barang->no_bon,
                    'nama_barang' => $barang->nama_barang,
                    'nama' => $nasabah->nama,
                    'jumlah' => number_format($grossAmount, 0, ',', '.'),
                    'tanggal' => now()->format('d-m-Y'),
                    'nama_cabang' => optional($barang->nasabah->user->cabang)->nama_cabang
                ]);
            } catch (\Exception $e) {
                \Log::error('Gagal kirim WA: ' . $e->getMessage());
                $responseWA = 'Gagal kirim WA';
            }
        }
    }

    if ($transaction === 'expire') {
        $pending->status = 'expired';
        $pending->save();
        $aksi = 'pembayaran_expired';
        $deskripsi = 'Pembayaran expired untuk bon: ' . $barang->no_bon;
        $dataBaru = $barang->toArray();
    } elseif ($transaction === 'cancel') {
        $pending->status = 'cancelled';
        $pending->save();
        $aksi = 'pembayaran_cancelled';
        $deskripsi = 'Pembayaran dibatalkan untuk bon: ' . $barang->no_bon;
        $dataBaru = $barang->toArray();
    }

    // Catat aktivitas
    LogAktivitas::create([
        'id_users'       => $user->id_users ?? null,
        'id_cabang'      => $user->id_cabang ?? null,
        'kategori'       => $kategori,
        'aksi'           => $aksi,
        'deskripsi'      => $deskripsi,
        'data_lama'      => json_encode($dataLama),
        'data_baru'      => json_encode($dataBaru),
        'ip_address'     => request()->ip(),
        'user_agent'     => request()->header('User-Agent'),
        'waktu_aktivitas'=> now(),
    ]);

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

    // Cek dan hapus data di BarangGadai jika ada no_bon
    if ($pending->new_bon) {
        // Cek data di BarangGadai berdasarkan no_bon_baru
        $bonBaru = BarangGadai::where('no_bon', $pending->new_bon)->first();
        if ($bonBaru && $bonBaru->status === 'Tergadai') {
            $bonBaru->delete();
        }

        // Cek dan hapus data di PerpanjanganGadai berdasarkan no_bon_baru
        $perpanjanganGadai = PerpanjanganGadai::where('no_bon_baru', $pending->new_bon)->first();
        if ($perpanjanganGadai) {
            $perpanjanganGadai->delete();
        }
    }

    // Ubah status pending menjadi cancelled
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
    $redirectUrl = "https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $snapToken;

    PendingPayment::create([
        'order_id' => $orderId,
        'no_bon' => $barangGadai->no_bon,
        'id_nasabah' => $nasabah->id_nasabah,
        'id_cabang' => optional($nasabah->user->cabang)->id_cabang,
        'jumlah_pembayaran' => (int) $totalPerpanjang,
        'status' => 'pending',
        'snap_token' => $snapToken,
        'redirect_url' => $redirectUrl,
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
