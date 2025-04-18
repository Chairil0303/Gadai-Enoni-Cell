<?php

namespace App\Http\Controllers;use App\Models\BarangGadai;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Nasabah;

class PerpanjangGadaiController extends Controller
{
    public function create()
    {
        return view('perpanjang_gadai.create');
    }

    public function store(Request $request)
{
    $data = session('konfirmasi_data');

    if (!$data) {
        return redirect()->route('perpanjang_gadai.create')->with('error', 'Data tidak ditemukan. Silakan isi ulang formulir.');
    }

    $request->validate([
        'no_bon_lama' => 'required|exists:barang_gadai,no_bon',
        'no_bon_baru' => 'required|unique:barang_gadai,no_bon',
    ]);

    $lama = BarangGadai::where('no_bon', $request->no_bon_lama)
        ->where('id_cabang', auth()->user()->id_cabang)
        ->firstOrFail();

    // Gunakan data dari session, bukan hitung ulang
    $harga_gadai_baru = $data['baru']['harga_gadai'];
    $bunga_baru = $data['baru']['bunga'];
    $tenor_baru = $data['baru']['tenor'];
    $tempo_baru = $data['baru']['tempo'];
    $total_baru = $data['total_baru'];

    // Update status bon lama
    $lama->update([
        'status' => 'diperpanjang',
    ]);

    $user = auth()->user();

    // Buat bon baru
    BarangGadai::create([
        'no_bon' => $request->no_bon_baru,
        'id_nasabah' => $lama->id_nasabah,
        'nama_barang' => $lama->nama_barang,
        'deskripsi' => $lama->deskripsi,
        'imei' => $lama->imei,
        'tenor' => $tenor_baru,
        'tempo' => $tempo_baru,
        'telat' => 0,
        'harga_gadai' => $harga_gadai_baru, // total_baru = harga_gadai + bunga
        'bunga' => $bunga_baru,
        'status' => 'Tergadai',
        'id_kategori' => $lama->id_kategori,
        'id_cabang' => $user->id_cabang,
    ]);

    // Simpan histori perpanjangan
    \App\Models\PerpanjanganGadai::create([
        'no_bon_lama' => $request->no_bon_lama,
        'no_bon_baru' => $request->no_bon_baru,
        'tenor_baru' => $tenor_baru,
        'harga_gadai_baru' => $harga_gadai_baru, // sesuai perhitungan submitForm
        'bunga_baru' => $bunga_baru,
        'tempo_baru' => $tempo_baru,
    ]);

    // Bersihkan session agar tidak dobel saat reload
    session()->forget('konfirmasi_data');

    return redirect()->route('barang_gadai.index')->with('success', );
}





    public function konfirmasi()
{
    $data = session('konfirmasi_data');

    if (!$data) {
        return redirect()->route('perpanjang_gadai.create')->with('error', 'Data tidak ditemukan. Silakan isi ulang formulir.');
    }

    return view('perpanjang_gadai.detail', $data); // tampilkan view konfirmasi
}



    public function submitForm(Request $request)
{

        $request->validate([
            'no_bon_lama' => 'required|string|exists:barang_gadai,no_bon',
            'no_bon_baru' => 'required|string|unique:barang_gadai,no_bon',
            'tenor' => 'required|integer|in:7,14,30',
            'jenis_perpanjangan' => 'required|in:tanpa_perubahan,penambahan,pengurangan',
            'penambahan' => 'nullable|numeric|min:0',
            'pengurangan' => 'nullable|numeric|min:0',
        ]);

        // Ambil data barang gadai lama
        $lama = BarangGadai::where('no_bon', $request->no_bon_lama)
            ->where('id_cabang', auth()->user()->id_cabang)
            ->firstOrFail();

        // Cek status bon lama
        if ($lama->status !== 'Tergadai') {
            return redirect()->back()->with('error', 'Bon lama tidak valid atau sudah diperpanjang.');
        }

        $nasabah = Nasabah::where('id_nasabah', $lama->id_nasabah)->first();

        // Hitung denda keterlambatan
        $denda_lama = ($lama->harga_gadai * 0.01) * $lama->telat;

        // Bunga lama berdasarkan tenor lama
        $bunga_persen_lama = match ($lama->tenor) {
            7 => 0.05,
            14 => 0.10,
            30 => 0.15,
            default => 0,
        };
        $bunga_lama = $lama->harga_gadai * $bunga_persen_lama;

        // Tentukan nominal penambahan atau pengurangan
        $nominal = 0;
        if ($request->jenis_perpanjangan === 'penambahan') {
            $nominal = $request->penambahan ?? 0; // Penambahan harga gadai
        } elseif ($request->jenis_perpanjangan === 'pengurangan') {
            $nominal = $request->pengurangan ?? 0; // Pengurangan harga gadai
        }

        // Tambahkan logika untuk menghitung denda
        $denda = 0;
        if ($lama->telat > 0) {
            // Misalnya denda dihitung 1% per hari keterlambatan
            $denda = $lama->harga_gadai * 0.01 * $lama->telat;
        }

        // Bunga baru berdasarkan tenor baru
        $bunga_persen_baru = match (intval($request->tenor)) {
            7 => 0.05,
            14 => 0.10,
            30 => 0.15,
            default => 0,
        };

        // Siapkan variabel awal
        $harga_gadai_baru = $lama->harga_gadai;
        $bunga_baru = 0;
        $total_lama = $bunga_lama + $denda_lama; // hanya bunga lama dan denda yang dibayar sekarang
        $total_baru = 0;
        $total_tagihan = 0;
        $catatan = '';

        // Proses per jenis perpanjangan
        if ($request->jenis_perpanjangan === 'tanpa_perubahan') {
            // Kondisi tanpa perubahan harga gadai
            $harga_gadai_baru = $lama->harga_gadai;
            $bunga_baru = $harga_gadai_baru * $bunga_persen_baru;
            $catatan = 'Perpanjangan tanpa perubahan harga gadai.';
        } elseif ($request->jenis_perpanjangan === 'penambahan') {
            // Kondisi penambahan harga gadai
            $harga_gadai_baru += $request->penambahan ?? 0;
            $bunga_baru = $harga_gadai_baru * $bunga_persen_baru;
            $catatan = 'Perpanjangan dengan penambahan harga gadai sebesar Rp' . number_format($request->penambahan ?? 0, 0, ',', '.');
        } elseif ($request->jenis_perpanjangan === 'pengurangan') {
            // Kondisi pengurangan harga gadai
            $harga_gadai_baru -= $request->pengurangan ?? 0;
            $bunga_baru = $harga_gadai_baru * $bunga_persen_baru;
            $catatan = 'Perpanjangan dengan pengurangan harga gadai sebesar Rp' . number_format($request->pengurangan ?? 0, 0, ',', '.');
        }

        // Hitung total baru dan total tagihan
        $total_baru = $harga_gadai_baru + $bunga_baru;
        $total_tagihan = $total_lama + $total_baru;

        // Tentukan tempo baru sesuai tenor yang dipilih
        $tenor = (int) $request->tenor;
        if (!is_numeric($tenor)) {
            return redirect()->back()->with('error', 'Tenor tidak valid.');
        }

        // Kalau tempo lama belum lewat, maka tempo_baru = tempo_lama + tenor
        // Kalau tempo lama sudah lewat, maka tempo_baru = hari ini + tenor
        $mulai_dari = Carbon::now()->gt(Carbon::parse($lama->tempo))
            ? Carbon::now()
            : Carbon::parse($lama->tempo);

        $tempo_baru = $mulai_dari->copy()->addDays((int) $request->tenor);


        // Siapkan data untuk bon baru
        $baru = [
            'no_bon' => $request->no_bon_baru,
            'tenor' => $request->tenor,
            'harga_gadai' => $harga_gadai_baru,
            'bunga' => $bunga_baru,
            'tempo' => $tempo_baru->format('Y-m-d'),
        ];

        // Kirim data ke view konfirmasi
        $data=[
            'lama' => $lama,
            'nasabah' => $nasabah,
            'no_bon_baru' => $request->no_bon_baru,
            'tenor' => $request->tenor,
            'jenis_perpanjangan' => $request->jenis_perpanjangan,
            'penambahan' => $request->penambahan,
            'pengurangan' => $request->pengurangan,
            'nominal' => $nominal,
            'bunga_lama' => $bunga_lama,
            'bunga_baru' => $bunga_baru,
            'total_lama' => $total_lama,
            'total_baru' => $total_baru,
            'total_tagihan' => $total_tagihan,
            'denda_lama' => $denda_lama,
            'baru' => $baru,
            'denda' => $denda,
            'catatan' => $catatan,
        ];

        session(['konfirmasi_data' => $data]);
    // Redirect GET
    return redirect()->route('perpanjang_gadai.konfirmasi');
}










}
