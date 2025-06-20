<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\BarangGadai;
use App\Models\Lelang;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ActivityLogger;
    

/**
 * LelangController handles the auction-related functionalities.
 * It allows users to create, update, and view auctions for pawned items.
 */
class LelangController extends Controller
{
    public function pilihan()
    {
        return view('lelang.pilihan');
    }
    
    public function index()
    {
        $user = Auth::user();

        $barangLelang = Lelang::with(['barangGadai.nasabah.user', 'barangGadai.kategori', 'barangGadai.cabang'])
            ->where('status', 'Aktif')
            ->when($user->role !== 'Superadmin', function ($query) use ($user) {
                $query->whereHas('barangGadai', function ($q) use ($user) {
                    $q->where('id_cabang', $user->id_cabang);
                });
            })
            ->latest()
            ->get();

        return view('nasabah.lelang.index', compact('barangLelang'));
    }


    public function create($no_bon)
    {
        $barang = BarangGadai::where('no_bon', $no_bon)->firstOrFail();
        return view('lelang.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_gadai_no_bon' => 'required|exists:barang_gadai,no_bon',
            'kondisi_barang' => 'required',
            'keterangan' => 'nullable',
            'harga_lelang' => 'nullable|numeric',
            'foto_barang' => 'nullable|array',
            'foto_barang.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $fotoPaths = [];
        if ($request->hasFile('foto_barang')) {
            foreach ($request->file('foto_barang') as $file) {
                $fotoPaths[] = $file->store('lelang_foto', 'public');
            }
        }

        // ✅ Simpan ke dalam variabel $lelang
        $lelang = Lelang::create([
            'barang_gadai_no_bon' => $request->barang_gadai_no_bon,
            'kondisi_barang' => $request->kondisi_barang,
            'keterangan' => $request->keterangan,
            'harga_lelang' => $request->harga_lelang,
            'foto_barang' => json_encode($fotoPaths),
        ]);

        BarangGadai::where('no_bon', $request->barang_gadai_no_bon)->update([
            'status' => 'Dilelang',
        ]);

        // Logging aktivitas
        ActivityLogger::log(
            kategori: 'lelang',
            aksi: 'create',
            deskripsi: "Menambahkan data lelang untuk bon: {$lelang->barang_gadai_no_bon}",
            dataLama: null,
            dataBaru: $lelang->toArray()
        );

        return redirect()->route('dashboard')->with('success', 'Data lelang berhasil ditambahkan.');
    }

    public function edit($no_bon)
    {
        $lelang = Lelang::where('barang_gadai_no_bon', $no_bon)->first();

        if (!$lelang) {
            return redirect()->route('dashboard')->with('error', 'Data lelang tidak ditemukan.');
        }

        // Decode JSON foto menjadi array
        $fotoBarang = json_decode($lelang->foto_barang, true) ?? [];

        // Kirim ke view
        return view('lelang.edit', compact('lelang', 'fotoBarang'));
    }


    public function update(Request $request, $no_bon)
    {
        $request->validate([
            'kondisi_barang' => 'required',
            'keterangan' => 'nullable',
            'harga_lelang' => 'required|numeric',
            'foto_barang' => 'nullable|array',
            'foto_barang.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $lelang = Lelang::where('barang_gadai_no_bon', $no_bon)->first();
        if (!$lelang) {
            return redirect()->route('dashboard')->with('error', 'Data lelang tidak ditemukan.');
        }

        $dataLama = $lelang->toArray(); // Simpan data sebelum update

        $fotoPaths = json_decode($lelang->foto_barang, true) ?? [];

        if ($request->has('delete_foto')) {
            foreach ($request->delete_foto as $foto) {
                if (in_array($foto, $fotoPaths)) {
                    if (Storage::exists('public/' . $foto)) {
                        Storage::delete('public/' . $foto);
                    }
                    $fotoPaths = array_diff($fotoPaths, [$foto]);
                }
            }
        }

        if ($request->hasFile('foto_barang')) {
            foreach ($request->file('foto_barang') as $file) {
                $filePath = $file->store('lelang_foto', 'public');
                $fotoPaths[] = $filePath;
            }
        }

        $lelang->kondisi_barang = $request->kondisi_barang;
        $lelang->keterangan = $request->keterangan;
        $lelang->harga_lelang = $request->harga_lelang;
        $lelang->foto_barang = json_encode($fotoPaths);
        $lelang->save();

        $dataBaru = $lelang->toArray(); // Simpan data setelah update

        // Logging aktivitas
        ActivityLogger::log(
            kategori: 'lelang',
            aksi: 'update',
            deskripsi: "Update data lelang untuk bon: {$no_bon}",
            dataLama: $dataLama,
            dataBaru: $dataBaru
        );

        return redirect()->route('dashboard')->with('success', 'Data lelang berhasil diperbarui.');
    }

    public function daftarBarangLelang(Request $request)
    {
        $user = Auth::user();

        $query = Lelang::with('barangGadai')
            ->where('status', 'Aktif')
            ->when($user->role !== 'Superadmin', function ($query) use ($user) {
                $query->whereHas('barangGadai', function ($q) use ($user) {
                    $q->where('id_cabang', $user->id_cabang);
                });
            });
        // Filter pencarian
        if ($search = $request->get('search')) {
            $query->whereHas('barangGadai', function ($q) use ($search) {
                $q->where('no_bon', 'like', "%$search%")
                  ->orWhere('nama_barang', 'like', "%$search%");
            });
        }
    
        // Sorting
        if ($sort = $request->get('sort')) {
            if ($sort === 'no_bon') {
                $query->join('barang_gadai', 'lelang.barang_gadai_no_bon', '=', 'barang_gadai.no_bon')
                      ->orderBy('barang_gadai.no_bon')
                      ->select('lelang.*');
            } elseif ($sort === 'nama_barang') {
                $query->join('barang_gadai', 'lelang.barang_gadai_no_bon', '=', 'barang_gadai.no_bon')
                      ->orderBy('barang_gadai.nama_barang')
                      ->select('lelang.*');
            } elseif ($sort === 'harga_lelang') {
                $query->orderBy('harga_lelang');
            }
        } else {
            $query->latest();
        }
    
        $barangLelang = $query->paginate(10)->appends($request->all());
    
        return view('lelang.baranglelang', compact('barangLelang'));
    }

    public function jual($id)
    {
        $lelang = Lelang::with('barangGadai')->findOrFail($id);
         // Ambil user yang login
        $user = Auth::user();

        // Simpan data lama untuk log
        $dataLama = $lelang->toArray();

        // Update status jadi 'Tebus'
        $lelang->status = 'Tebus';
        $lelang->save();

        // Data baru setelah update
        $dataBaru = $lelang->toArray();

        // Simpan transaksi ke tabel `transaksi`
        Transaksi::create([
            'no_bon'          => $lelang->barang_gadai_no_bon,
            'id_nasabah'      => $lelang->barangGadai->id_nasabah,
            'id_user'         => $user->id_users,
            'id_cabang'       => $user->id_cabang,
            'jenis_transaksi' => 'tebus',
            'arus_kas'        => 'masuk',
            'jumlah'          => $lelang->harga_lelang,
        ]);

        // Logging aktivitas
        ActivityLogger::log(
            kategori: 'transaksi',
            aksi: 'jual',
            deskripsi: 'Menjual barang lelang No Bon: ' . $lelang->barang_gadai_no_bon . ' - ' . $lelang->barangGadai->nama_barang,
            dataLama: json_encode($dataLama),
            dataBaru: json_encode($dataBaru)
        );

        return redirect()->back()->with('success', 'Barang berhasil dijual dan transaksi dicatat.');
    }







}
