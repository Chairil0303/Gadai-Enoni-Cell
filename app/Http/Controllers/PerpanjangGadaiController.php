<?php

namespace App\Http\Controllers;
use App\Models\BarangGadai;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Nasabah;
use App\Models\BungaTenor;
use App\Models\Transaksi;
use App\Helpers\ActivityLogger;

class PerpanjangGadaiController extends Controller
{
    public function create()
    {
        $tenors = BungaTenor::all(); // ambil semua tenor dari DB
        return view('perpanjang_gadai.create', compact('tenors'));
    }

    public function store(Request $request)
    {
        $data = session('konfirmasi_data');

        if (!$data) {
            return redirect()->route('perpanjang_gadai.create')->with('error', 'Data tidak ditemukan. Silakan isi ulang formulir.');
        }

        // Validasi input
        $request->validate([
            'no_bon_lama' => 'required|exists:barang_gadai,no_bon',
            'no_bon_baru' => 'required|unique:barang_gadai,no_bon',
        ]);

        // Ambil data barang gadai lama berdasarkan no_bon_lama dan id_cabang
        $lama = BarangGadai::where('no_bon', $request->no_bon_lama)
            ->where('id_cabang', auth()->user()->id_cabang)
            ->firstOrFail();

        $harga_gadai_baru = $data['baru']['harga_gadai'];
        $bunga_baru = $data['baru']['bunga'];
        $tenor_baru = $data['id_bunga_tenor'];
        $tempo_baru = $data['baru']['tempo'];

        // Ambil data lama untuk log sebelum update
        $dataLama = [
            'status' => $lama->status,
            'harga_gadai' => $lama->harga_gadai,
            'bunga' => $lama->bunga,
            'tempo' => $lama->tempo,
            'tenor' => $lama->bungaTenor->tenor ?? null,
        ];

        // Update status bon lama menjadi diperpanjang
        $lama->update([
            'status' => 'diperpanjang',
        ]);

        // Buat bon baru dengan data perpanjangan
        $baru = BarangGadai::create([
            'no_bon' => $request->no_bon_baru,
            'id_nasabah' => $lama->id_nasabah,
            'nama_barang' => $lama->nama_barang,
            'deskripsi' => $lama->deskripsi,
            'imei' => $lama->imei,
            'id_bunga_tenor' => $tenor_baru,
            'tempo' => $tempo_baru,
            'telat' => 0,
            'harga_gadai' => $harga_gadai_baru,
            'bunga' => $bunga_baru,
            'status' => 'Tergadai',
            'id_kategori' => $lama->id_kategori,
            'id_cabang' => auth()->user()->id_cabang,
            'no_bon_lama' => $lama->no_bon,
        ]);

        // Simpan histori perpanjangan
        \App\Models\PerpanjanganGadai::create([
            'no_bon_lama' => $request->no_bon_lama,
            'no_bon_baru' => $request->no_bon_baru,
            'tenor_baru' => $tenor_baru,
            'harga_gadai_baru' => $harga_gadai_baru,
            'bunga_baru' => $bunga_baru,
            'tempo_baru' => $tempo_baru,
        ]);

        // Simpan transaksi bunga lama + denda (dibayar saat perpanjangan)
        Transaksi::create([
            'jenis_transaksi' => 'perpanjang_bunga_denda',
            'arus_kas' => 'masuk',
            'jumlah' => $lama->harga_gadai * ($lama->bungaTenor->bunga_percent / 100) + (($lama->telat ?? 0) * 0.01 * $lama->harga_gadai),
            'id_cabang' => auth()->user()->id_cabang,
            'id_user' => auth()->id(),
            'id_nasabah' => $lama->id_nasabah,
            'no_bon' => $lama->no_bon,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Transaksi tambahan atau pengurangan jika ada
        if ($request->jenis_perpanjangan === 'penambahan') {
            $nominal = $data['penambahan'] ?? 0;
            if ($nominal > 0) {
                Transaksi::create([
                    'jenis_transaksi' => 'perpanjang_tambah',
                    'arus_kas' => 'keluar',
                    'jumlah' => $nominal,
                    'id_cabang' => auth()->user()->id_cabang,
                    'id_user' => auth()->id(),
                    'id_nasabah' => $lama->id_nasabah,
                    'no_bon' => $lama->no_bon,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($request->jenis_perpanjangan === 'pengurangan') {
            $nominal = $data['pengurangan'] ?? 0;
            if ($nominal > 0) {
                Transaksi::create([
                    'jenis_transaksi' => 'perpanjang_kurang',
                    'arus_kas' => 'masuk',
                    'jumlah' => $nominal,
                    'id_cabang' => auth()->user()->id_cabang,
                    'id_user' => auth()->id(),
                    'id_nasabah' => $lama->id_nasabah,
                    'no_bon' => $lama->no_bon,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Data baru untuk log (gunakan data bon baru)
        $dataBaru = [
            'no_bon' => $baru->no_bon,
            'status' => $baru->status,
            'harga_gadai' => $baru->harga_gadai,
            'bunga' => $baru->bunga,
            'tempo' => $baru->tempo,
            'tenor' => BungaTenor::find($tenor_baru)->tenor ?? null,
        ];

        // Deskripsi aktivitas
        $deskripsi = 'Perpanjangan gadai no bon lama ' . $lama->no_bon . ' atas nama ' . $lama->nasabah->nama .
                ' menjadi no bon baru ' . $baru->no_bon . ', tenor baru ' . ($dataBaru['tenor'] ?? '-') .
                ', harga gadai Rp ' . number_format($harga_gadai_baru);

        // Logging aktivitas
        ActivityLogger::log(
            kategori: 'transaksi',
            aksi: 'perpanjang gadai',
            deskripsi: $deskripsi,
            dataLama: $dataLama,
            dataBaru: $dataBaru
        );

        // Hapus session konfirmasi_data
        session()->forget('konfirmasi_data');

        // Redirect ke index dengan pesan sukses
        return redirect()->route('barang_gadai.index')->with('success', 'Perpanjangan berhasil disimpan.');
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
        // Ambil semua tenor dari tabel
        $tenorValid = BungaTenor::pluck('tenor')->toArray();

            $request->validate([
                'no_bon_lama' => 'required|string|exists:barang_gadai,no_bon',
                'no_bon_baru' => 'required|string|unique:barang_gadai,no_bon',
                'tenor' => 'required|integer|in:' . implode(',', $tenorValid),
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

           // Bunga lama berdasarkan data dari tabel bunga_tenor
            $bungaTenorLama = $lama->bungaTenor;
            $bunga_persen_lama = $bungaTenorLama ? $bungaTenorLama->bunga_percent / 100 : 0;
            $bunga_lama = $lama->harga_gadai * $bunga_persen_lama;

            $tenorLama = $lama->bungaTenor->tenor;

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
            // Ambil bunga baru berdasarkan tenor dari tabel bunga_tenor
            $bungaTenorBaru = BungaTenor::where('tenor', $request->tenor)->first();

            if (!$bungaTenorBaru) {
                return redirect()->back()->with('error', 'Data bunga untuk tenor tersebut tidak ditemukan.');
            }

            $bunga_persen_baru = $bungaTenorBaru->bunga_percent / 100;


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
                'harga_gadai' => $harga_gadai_baru,
                'bunga' => $bunga_baru,
                'tempo' => $tempo_baru->format('Y-m-d'),
                // 'id_bunga_tenor' => $bungaTenorBaru->id, pondah langsung ke $data bukan ada di $baru lagi
            ];

            // Kirim data ke view konfirmasi
            $data=[
                'id_bunga_tenor' => $bungaTenorBaru->id,
                'lama' => $lama,
                'nasabah' => $nasabah,
                'no_bon_baru' => $request->no_bon_baru,
                'tenor' => $request->tenor,
                'jenis_perpanjangan' => $request->jenis_perpanjangan,
                'penambahan' => $request->penambahan,
                'pengurangan' => $request->pengurangan,
                'nominal' => $nominal,
                'bunga_lama' => $bunga_lama,
                'bunga_persen_baru' => $bunga_persen_baru,
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
