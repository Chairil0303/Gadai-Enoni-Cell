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
use App\Models\BungaTenor;

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

        // $barangGadai = $query->first();
        $barangGadai = $query->with('bungaTenor')->first(); // Fetch the first result with the related bungaTenor


        if (!$barangGadai) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Ambil data nasabah terkait
        $nasabah = Nasabah::find($barangGadai->id_nasabah);

        // Hitung Denda (1% dari harga gadai dikali hari telat)
        // $denda = ($barangGadai->harga_gadai * 0.01) * $barangGadai->telat;
        $denda = $barangGadai->telat * ($barangGadai->harga_gadai * 0.01);

        $hasilBunga = $this->hitungBunga($barangGadai);
        $bunga = $hasilBunga['bunga'];
        $bungaPersen = $hasilBunga['bungaPersen'];


        // Hitung Total Tebus
        $totalTebus = $barangGadai->harga_gadai + $bunga + $denda;

        return view('nasabah.konfirmasi', compact('barangGadai', 'nasabah', 'denda', 'totalTebus','bungaPersen','bunga','hasilbunga'));
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


public function konfirmasi($no_bon)
{
    $userId = auth()->id();


    // Ambil id_nasabah berdasarkan id_user
    $nasabah = Nasabah::where('id_user', $userId)->first();

    $barangGadai = BarangGadai::where('no_bon', $no_bon)
        ->where('id_nasabah', $nasabah->id_nasabah)
        ->where('status', 'tergadai')
        ->with('nasabah','bungaTenor')
        ->first();


    if (!$barangGadai) {
        abort(404, 'Barang gadai tidak ditemukan atau tidak cocok dengan akun nasabah.');
    }
    $denda = $barangGadai->telat * ($barangGadai->harga_gadai * 0.01);

    $hasilBunga = $this->hitungBunga($barangGadai);
    $bunga = $hasilBunga['bunga'];
    $bungaPersen = $hasilBunga['bungaPersen'];


    // Hitung Total Tebus
    $totalTebus = $barangGadai->harga_gadai + $bunga + $denda;

    return view('nasabah.konfirmasi', compact('barangGadai', 'bunga', 'bungaPersen', 'denda', 'totalTebus'));
}




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
