<?php

namespace App\Http\Controllers;

use App\Models\BarangGadai;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;
use app\models\TransaksiTebus;
use Carbon\Carbon;


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
    public function processPayment(Request $request)
{
    $noBon = $request->no_bon;
    $paymentMethod = $request->payment_method;
    $amount = $request->amount;

    // Cari barang yang sesuai dengan no_bon
    $barang = BarangGadai::where('no_bon', $noBon)->first();

    if (!$barang) {
        return response()->json(['status' => 'error', 'error' => 'Barang tidak ditemukan.']);
    }

    // Order ID harus unik (pakai timestamp)
    $orderId = 'Tebus-' . $barang->no_bon . '-' . time();

    $transaction_details = [
        'order_id' => $orderId,
        'gross_amount' => $amount,
    ];

    $items = [
        [
            'id' => $barang->no_bon,
            'price' => $amount,
            'quantity' => 1,
            'name' => $barang->nama_barang,
        ]
    ];

    $customer_details = [
        'first_name'    => $barang->nasabah->nama,
        'email'         => $barang->nasabah->email,
        'phone'         => $barang->nasabah->telepon,
    ];

    $transaction_data = [
        'payment_type' => $paymentMethod,
        'transaction_details' => $transaction_details,
        'item_details' => $items,
        'customer_details' => $customer_details,
    ];

    // Proses transaksi dengan Midtrans Snap
    try {
        $snapToken = Snap::getSnapToken($transaction_data);
        return response()->json([
            'status' => 'success',
            'order_id' => $orderId, // Kirim order_id agar bisa dipakai nanti
            'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken
        ]);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
    }
}


    // Handle notification dari Midtrans (untuk konfirmasi pembayaran)
    public function handleNotification(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $signatureKey = hash('sha512', $request->order_id.$request->status_code.$request->gross_amount.$serverKey);

        if ($signatureKey !== $request->signature_key) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        if ($request->transaction_status == 'settlement') {
            // Cek apakah transaksi sudah ada di database
            $transaksi = TransaksiTebus::where('order_id', $request->order_id)->first();

            if (!$transaksi) {
                // Ambil barang berdasarkan no_bon dari order_id
                $barang = BarangGadai::where('no_bon', str_replace('Tebus-', '', $request->order_id))->first();

                if ($barang) {
                    // Simpan transaksi tebus
                    TransaksiTebus::create([
                        'no_bon' => $barang->no_bon,
                        'id_user' => $barang->id_nasabah,
                        'id_nasabah' => $barang->id_nasabah,
                        'tanggal_tebus' => Carbon::now(),
                        'jumlah_pembayaran' => $request->gross_amount,
                        'status' => 'Berhasil',
                    ]);

                    // Update status barang jadi "Ditebus"
                    $barang->status = 'Ditebus';
                    $barang->save();
                }
            }
        }

        return response()->json(['success' => true]);
    }

}
