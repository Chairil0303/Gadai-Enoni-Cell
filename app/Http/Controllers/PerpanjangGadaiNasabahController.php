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
use App\Models\BungaTenor;

class perpanjangGadaiNasabahController extends Controller
{
    public function details($no_bon)
{
    // Ambil semua tenor dari tabel bunga_tenor untuk pilihan user
    $tenors = BungaTenor::all();

    // Ambil data barang gadai yang masih tergadai
    $barangGadai = BarangGadai::where('status', 'tergadai')
                               ->where('no_bon', $no_bon)
                               ->first();

    if (!$barangGadai) {
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }

    // Ambil data nasabah
    $nasabah = Nasabah::find($barangGadai->id_nasabah);

    // Ambil data bunga berdasarkan tenor LAMA (saat ini)
    $bungaTenorLama = BungaTenor::where('tenor', $barangGadai->tenor)->first();
    $bungaPersen = $bungaTenorLama ? $bungaTenorLama->persentase : 0;

    // Hitung bunga dan denda
    $bunga = $barangGadai->harga_gadai * ($bungaPersen / 100);
    $denda = ($barangGadai->harga_gadai * 0.01) * $barangGadai->telat;
    $totalPerpanjang = $barangGadai->harga_gadai + $bunga + $denda;

    // Kirim semua data ke view
    return view('nasabah.detailPerpanjangGadai', compact(
        'barangGadai', 'nasabah', 'denda', 'totalPerpanjang', 'bungaPersen', 'bunga', 'tenors'
    ));
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
        $tenors = $barangGadai->bungaTenor->tenor;

        if (!$barangGadai) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $nasabah = Nasabah::find($barangGadai->id_nasabah);

        // Hitung denda (1% dari harga gadai dikali hari telat)
        $denda = ($barangGadai->harga_gadai * 0.01) * $barangGadai->telat;
        $tempobaru = Carbon::parse($barangGadai->tempo)->addDays($tenor)->translatedFormat('l, d F Y');
        $bungaTenorBaru = BungaTenor::where('tenor', $tenors)->first();

        if (!$bungaTenorBaru) {
            return redirect()->back()->with('error', 'Data bunga untuk tenor tersebut tidak ditemukan.');
        }

        $bunga_persen_baru = $barangGadai->harga_gadai* $bungaTenorBaru->bunga_percent / 100 ;

        $TenorBaru = BungaTenor::where('tenor', $request->tenor)->first();


        if ($type === 'cicil') {
            $hargaGadai = $barangGadai->harga_gadai;
            $hargaGadaiBaru = $hargaGadai - $cicilan + $bunga_persen_baru + $denda;
            $totalPerpanjang = $bunga_persen_baru + $denda;
        } else {
            $totalPerpanjang = $bunga_persen_baru + $denda;
        }


        // ✅ Simpan data ke session
        session([
            'perpanjangan.tenor' => $tenor,
            'perpanjangan.type' => $type,
            'perpanjangan.cicilan' => $cicilan,
            'perpanjangan.no_bon' => $barangGadai->no_bon,
            'perpanjangan.total' => $totalPerpanjang,
            'tenor.lama'=>$tenors,
        ]);

        return view('nasabah.konfirmasiPerpanjangGadai', compact(
            'barangGadai',
            'tenor',
            'cicilan',
            'type',
            'nasabah',
            'denda',
            'bunga_persen_baru',
            'totalPerpanjang',
            'tempobaru','bungaTenorBaru','tenors','TenorBaru',
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

        if ($barangGadai->status === 'Ditebus') {
            return response()->json(['message' => 'Barang ini sudah ditebus sebelumnya.'], 403);
        }

        $pendingExists = PendingPayment::where('no_bon', $noBon)
            ->where('status', 'pending')
            ->exists();

        if ($pendingExists) {
            return response()->json(['message' => 'Masih ada transaksi pembayaran yang belum selesai untuk barang ini.'], 403);
        }

        // Ambil data dari session
        $tenor = session('perpanjangan.tenor');
        $type = session('perpanjangan.type');
        $cicilan = session('perpanjangan.cicilan');
        $tenors = session('tenor.lama');

        if (!$tenor || !$type) {
            return response()->json(['message' => 'Session perpanjangan tidak ditemukan atau kadaluarsa. Silakan ulangi proses.'], 400);
        }

        // Ambil bunga tenor berdasarkan tenor lama dan tenor baru
        $bungaTenor = BungaTenor::where('tenor', $tenors)->first();
        $TenorBaru = BungaTenor::where('tenor', $tenor)->first();

        if (!$TenorBaru) {
            return response()->json(['message' => 'Tenor baru tidak ditemukan di tabel bunga_tenor.'], 404);
        }

        $denda = $barangGadai->telat * ($barangGadai->harga_gadai * 0.01);
        $bunga = $barangGadai->harga_gadai * ($bungaTenor->bunga_percent / 100);

        // Hitung total bayar berdasarkan jenis perpanjangan
        $total = $bunga + $denda;

        if ($type === 'cicil') {
            $total += $cicilan;
        }

        // Midtrans setup
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $orderId = $barangGadai->no_bon . '-' . strtoupper(Str::random(2)) . '-perpanjang';

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $total,
            ],
            'customer_details' => [
                'first_name' => $barangGadai->nasabah->nama,
                'phone' => $barangGadai->nasabah->telepon,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        $tempobaru = Carbon::parse($barangGadai->tempo)->addDays($tenor);
        $newBon = $barangGadai->no_bon . '-DM';

        // Simpan transaksi pending
        PendingPayment::create([
            'order_id' => $orderId,
            'no_bon' => $barangGadai->no_bon,
            'id_nasabah' => $nasabah->id_nasabah,
            'id_cabang' => optional($nasabah->user->cabang)->id_cabang,
            'jumlah_pembayaran' => (int) $total,
            'new_bon' => $newBon,
            'status' => 'pending',
        ]);

        // Simpan bon baru dengan id_bunga_tenor dari tenor baru
        BarangGadai::create([
            'no_bon' => $newBon,
            'no_bon_lama' => $barangGadai->no_bon,
            'id_bunga_tenor' => $TenorBaru->id,
            'id_nasabah' => $barangGadai->id_nasabah,
            'nama_barang' => $barangGadai->nama_barang,
            'deskripsi' => $barangGadai->deskripsi,
            'imei' => $barangGadai->imei,
            'tempo' => $tempobaru,
            'telat' => 0,
            'harga_gadai' => $barangGadai->harga_gadai - ($type === 'cicil' ? $cicilan : 0),
            'bunga' => $TenorBaru->bunga_percent,
            'status' => 'Tergadai',
            'id_kategori' => $barangGadai->id_kategori,
            'id_cabang' => $barangGadai->id_cabang,
        ]);
        // Simpan histori perpanjangan
        PerpanjanganGadai::create([
            'no_bon_lama' => $barangGadai->no_bon,
            'no_bon_baru' => $newBon,
            'tenor_baru' => $tenor,
            'harga_gadai_baru' => $barangGadai->harga_gadai - ($type === 'cicil' ? $cicilan : 0),
            'bunga_baru' => $TenorBaru->bunga_percent,
            'tempo_baru' => $tempobaru,
        ]);

        return response()->json([
            'snap_token' => $snapToken,
            'total_bayar' => $total,
            'order_id' => $orderId,
            'detail' => [
                'harga_gadai' => $barangGadai->harga_gadai,
                'bunga_percent' => $bungaTenor->bunga_percent,
                'bunga_nominal' => $bunga,
                'denda' => $denda,
                'telat' => $barangGadai->telat,
                'tenor' => $tenor,
                'nama_nasabah' => $barangGadai->nasabah->nama,
                'telepon' => $barangGadai->nasabah->telepon,
            ]
        ]);
    }


}

// perpanjang gadai nasabah
