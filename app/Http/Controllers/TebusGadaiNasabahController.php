<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangGadai;
use App\Models\TransaksiTebus;
use Midtrans\Config;
use Midtrans\Snap;
use Carbon\Carbon;
use Auth;

class TebusGadaiNasabahController extends Controller
{
    public function processPayment(Request $request)
    {
        $barang = BarangGadai::where('id', $request->barang_id)
            ->where('id_nasabah', Auth::id())
            ->first();

        if (!$barang) {
            return response()->json(['error' => 'Barang tidak ditemukan'], 404);
        }

        $denda = ($barang->harga_gadai * 0.01) * $barang->telat;
        $bunga = ($barang->harga_gadai * ($barang->tenor == 7 ? 5 : ($barang->tenor == 14 ? 10 : 15)) / 100);
        $totalTebus = $barang->harga_gadai + $bunga + $denda;

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        $transactionDetails = [
            'transaction_details' => [
                'order_id' => uniqid(),
                'gross_amount' => $totalTebus,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->telepon,
            ],
            'item_details' => [
            [
                'id' => 'harga-gadai',
                'price' => (int) $barang->harga_gadai,
                'quantity' => 1,
                'name' => 'Harga Gadai',
            ],
            [
                'id' => 'bunga',
                'price' => (int) $bunga,
                'quantity' => 1,
                'name' => 'Bunga Gadai',
            ],
            [
                'id' => 'denda',
                'price' => (int) $denda,
                'quantity' => 1,
                'name' => 'Denda Keterlambatan',
            ],
        ],
        ];

        $snapToken = Snap::getSnapToken($transactionDetails);

        return response()->json(['snap_token' => $snapToken]);
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
