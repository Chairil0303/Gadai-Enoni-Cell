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

    // Proses Pembayaran
        // public function processPayment(Request $request)
        // {
        //     $noBon = $request->no_bon;
        //     $userId = auth()->user()->id_users;
        //     $nasabah = Nasabah::where('id_user', $userId)->first();

        //     if (!$nasabah) {
        //         return redirect()->back()->with('error', 'Nasabah tidak ditemukan');
        //     }

        //     $barangGadai = BarangGadai::where('no_bon', $noBon)
        //         ->where('id_nasabah', $nasabah->id_nasabah)
        //         ->with('nasabah')
        //         ->first();

        //     if (!$barangGadai) {
        //         return redirect()->back()->with('error', 'Barang tidak ditemukan');
        //     }

        //     $telat = max($barangGadai->telat, 0);
        //     $denda = ($barangGadai->harga_gadai * 0.01) * $telat;

        //     $tenor = $barangGadai->tenor;
        //     $bungaPersen = match ($tenor) {
        //         7 => 5,
        //         14 => 10,
        //         30 => 15,
        //         default => 0,
        //     };
        //     $bunga = $barangGadai->harga_gadai * ($bungaPersen / 100);
        //     $totalTebus = $barangGadai->harga_gadai + $bunga + $denda;

        //     // Midtrans
        //     Config::$serverKey = config('midtrans.server_key');
        //     Config::$isProduction = config('midtrans.is_production');
        //     Config::$isSanitized = config('midtrans.is_sanitized');
        //     Config::$is3ds = config('midtrans.is_3ds');

        //     $orderId = $barangGadai->no_bon . '-' . time();

        //     $params = [
        //         'transaction_details' => [
        //             'order_id' => $orderId,
        //             'gross_amount' => (int) $totalTebus,
        //         ],
        //         'customer_details' => [
        //             'first_name' => $barangGadai->nasabah->nama,
        //             'phone' => $barangGadai->nasabah->telepon,
        //         ],
        //     ];

        //     $snapToken = Snap::getSnapToken($params);

        //     PendingPayment::create([
        //         'order_id' => $orderId,
        //         'no_bon' => $barangGadai->no_bon,
        //         'id_nasabah' => $nasabah->id_nasabah,
        //         'id_cabang' => optional($nasabah->user->cabang)->id_cabang,
        //         'jumlah_pembayaran' => (int) $totalTebus,
        //         'status' => 'pending',
        //     ]);

        //     return view('tebus.proses', [
        //         'snapToken' => $snapToken,
        //         'totalTebus' => $totalTebus,
        //         'orderId' => $orderId,
        //         'barang' => $barangGadai,
        //         'bunga' => $bunga,
        //         'denda' => $denda,
        //         'telat' => $telat,
        //         'tenor' => $tenor,
        //     ]);
        // }



        // pemisah
        public function processPaymentJson(request $request)
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

        // Hitung Denda
        $telat = max($barangGadai->telat, 0);
        $denda = ($barangGadai->harga_gadai * 0.01) * $telat;

        // Hitung Bunga
        $tenor = $barangGadai->tenor;
        $bungaPersen = match ($tenor) {
            7 => 5,
            14 => 10,
            30 => 15,
            default => 0,
        };
        $bunga = $barangGadai->harga_gadai * ($bungaPersen / 100);

        $totalTebus = $barangGadai->harga_gadai + $bunga + $denda;

        // Midtrans config
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $orderId = $barangGadai->no_bon . '-' . time(); // Tanpa disimpan ke DB

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
        PendingPayment::create([
            'order_id' => $orderId,
            'no_bon' => $barangGadai->no_bon,
            'id_nasabah' => $nasabah->id_nasabah,
            'id_cabang' => optional($nasabah->user->cabang)->id_cabang,
            'jumlah_pembayaran' => (int) $totalTebus,
            'status' => 'pending',
        ]);


        return response()->json([
            'snap_token' => $snapToken,
            'total_tebus' => $totalTebus,
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

    if (in_array($transaction, ['settlement', 'capture'])) {
        $barang->status = 'Ditebus';
        $barang->save();

        $pending = PendingPayment::where('order_id', $orderId)->first();
        if (!$pending || $pending->status === 'paid') {
            return response()->json(['message' => 'Transaksi tidak valid atau sudah diproses.']);
        }

        $id_cabang = optional($barang->nasabah->user->cabang)->id_cabang;

        $pending->status = 'paid';
    //         $pending->save();

        TransaksiTebus::create([
            'no_bon' => $barang->no_bon,
            'id_cabang' => $id_cabang,
            'id_nasabah' => $barang->id_nasabah,
            'tanggal_tebus' => Carbon::now(),
            'jumlah_pembayaran' => (int) $grossAmount,
            'status' => 'Berhasil',
        ]);

        // Kirim notifikasi WhatsApp ke nasabah
        $nasabah = $barang->nasabah;
        $noHp = preg_replace('/^0/', '62', $nasabah->telepon); // ubah 08xx ke 62xxx

        $message = "*📦 Transaksi Tebus Berhasil!*\n\n" .
            "🆔 No BON: {$barang->no_bon}\n" .
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

    return response()->json(['message' => 'Notifikasi diproses.', 'wa_notif' => $responseWA ?? 'Tidak ada WA']);
}

// pake pending payment
    // public function handleNotificationJson(Request $request)
    // {
    //     // \Log::info('Webhook masuk:', $request->all());
    //     $data = $request->all();

    //     $transaction = $data['transaction_status'];
    //     $orderId = $data['order_id'];
    //     $grossAmount = $data['gross_amount'] ?? 0;

    //     $noBon = explode('-', $orderId)[0];

    //     $barang = BarangGadai::with('nasabah.user.cabang')->where('no_bon', $noBon)->first();

    //     if (!$barang) {
    //         return response()->json(['message' => 'Barang tidak ditemukan'], 404);
    //     }

    //     $pending = PendingPayment::where('order_id', $orderId)->first();

    //     if (!$pending) {
    //         return response()->json(['message' => 'Data pending tidak ditemukan']);
    //     }

    //     // Cek jika status sudah diproses sebelumnya
    //     if (in_array($pending->status, ['paid', 'cancelled', 'expired'])) {
    //         return response()->json(['message' => 'Transaksi sudah diproses sebelumnya.']);
    //     }

    //     $responseWA = null;

    //     if (in_array($transaction, ['settlement', 'capture'])) {
    //         // Update status barang
    //         $barang->status = 'Ditebus';
    //         $barang->save();

    //         // Update status pending jadi paid
    //         $pending->status = 'paid';
    //         $pending->save();

    //         // Simpan transaksi tebus
    //         TransaksiTebus::create([
    //             'no_bon' => $barang->no_bon,
    //             'id_cabang' => optional($barang->nasabah->user->cabang)->id_cabang,
    //             'id_nasabah' => $barang->id_nasabah,
    //             'tanggal_tebus' => Carbon::now(),
    //             'jumlah_pembayaran' => (int) $grossAmount,
    //             'status' => 'Berhasil',
    //         ]);

    //         // Kirim notifikasi ke WA
    //         $nasabah = $barang->nasabah;
    //         $noHp = preg_replace('/^0/', '62', $nasabah->telepon);

    //         $message = "*📦 Transaksi Tebus Berhasil!*\n\n" .
    //             "🆔 No BON: {$barang->no_bon}\n" .
    //             "👤 Nama: {$nasabah->nama}\n" .
    //             "💰 Jumlah: Rp " . number_format($grossAmount, 0, ',', '.') . "\n" .
    //             "📅 Tanggal: " . now()->format('d-m-Y') . "\n\n" .
    //             "Terima kasih telah menebus barang Anda di *Pegadaian Kami* 🙏";

    //         $responseWA = WhatsappHelper::send($noHp, $message);

    //     } elseif (in_array($transaction, ['cancel', 'deny'])) {
    //         $pending->status = 'cancelled';
    //         $pending->save();
    //     } elseif ($transaction === 'expire') {
    //         $pending->status = 'expired';
    //         $pending->save();
    //     } else {
    //         $pending->status = 'pending'; // fallback jika status tak dikenali
    //         $pending->save();
    //     }

    //     return response()->json([
    //         'message' => "Status transaksi: {$transaction}",
    //         'wa_notif' => $responseWA
    //     ]);
    // }












    // Handle notification dari Midtrans (untuk konfirmasi pembayaran)
    // public function handleNotification(Request $request)
    // {
    //     $serverKey = config('midtrans.server_key');
    //     $signatureKey = hash('sha512', $request->order_id.$request->status_code.$request->gross_amount.$serverKey);

    //     if ($signatureKey !== $request->signature_key) {
    //         return response()->json(['error' => 'Invalid signature'], 403);
    //     }

    //     if ($request->transaction_status == 'settlement') {
    //         // Cek apakah transaksi sudah ada di database
    //         $transaksi = TransaksiTebus::where('order_id', $request->order_id)->first();

    //         if (!$transaksi) {
    //             // Ambil barang berdasarkan no_bon dari order_id
    //             $barang = BarangGadai::where('no_bon', str_replace('Tebus-', '', $request->order_id))->first();

    //             if ($barang) {
    //                 // Simpan transaksi tebus
    //                 TransaksiTebus::create([
    //                     'no_bon' => $barang->no_bon,
    //                     'id_user' => $barang->id_nasabah,
    //                     'id_nasabah' => $barang->id_nasabah,
    //                     'tanggal_tebus' => Carbon::now(),
    //                     'jumlah_pembayaran' => $request->gross_amount,
    //                     'status' => 'Berhasil',
    //                 ]);

    //                 // Update status barang jadi "Ditebus"
    //                 $barang->status = 'Ditebus';
    //                 $barang->save();
    //             }
    //         }
    //     }

    //     return response()->json(['success' => true]);
    // }

    public function getPaymentJsonByBon($noBon)
    {
        // Ambil user yang login
        $userId = auth()->user()->id_users;

        // Ambil data nasabah yang terhubung
        $nasabah = Nasabah::where('id_user', $userId)->first();

        if (!$nasabah) {
            return response()->json(['message' => 'Nasabah tidak ditemukan'], 404);
        }

        // Cek data barang berdasarkan no_bon dan id_nasabah
        $barang = BarangGadai::with('nasabah')
        ->whereRaw('LOWER(no_bon) = ?', [strtolower($noBon)])
        ->where('id_nasabah', $nasabah->id_nasabah)
        ->first();
        // $barang = BarangGadai::with('nasabah')
        //     ->whereRaw('LOWER(no_bon) = ?', [strtolower($noBon)])
        //     ->where('id_nasabah', $nasabah->id_nasabah)
        //     ->first();

        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }

        // Hitung bunga dan denda
        $bungaPersen = 1;
        $bunga = ($barang->harga_gadai * $bungaPersen) / 100;
        $denda = $barang->telat > 0 ? ($barang->telat * 5000) : 0;
        $totalTebus = $barang->harga_gadai + $bunga + $denda;

        // Return JSON
        return response()->json([
            'no_bon' => $barang->no_bon,
            'nama_barang' => $barang->nama_barang,
            'harga_gadai' => $barang->harga_gadai,
            'bunga' => $bunga,
            'denda' => $denda,
            'total_tebus' => $totalTebus,
            'payment_method' => 'snap',
        ]);
    }






}
