<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangGadai;
use App\Models\Nasabah;
use App\Models\KategoriBarang;
use App\Models\BungaTenor;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class BarangGadaiController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        if ($userId == 1) {
            // Superadmin: ambil semua data
            $barangGadai = BarangGadai::with('nasabah.user', 'kategori')->get();
        } else {
            if (Schema::hasColumn('barang_gadai', 'id_cabang')) {
                $barangGadai = BarangGadai::with('nasabah.user', 'kategori')
                    ->where('id_cabang', auth()->user()->id_cabang) // Ambil berdasarkan cabang user login
                    ->get();
            } else {
                $barangGadai = collect(); // Kolom tidak tersedia
            }
        }

        return view('barang_gadai.index', compact('barangGadai'));
    }




    public function create()
    {
        $nasabah = Nasabah::all();
        $kategori = KategoriBarang::all();
        $bunga_tenors = BungaTenor::all(); // ambil data bunga tenors

        return view('transaksi_gadai.create', [
            'nasabah' => $nasabah,
            'kategori_barang' => $kategori,
            'bunga_tenors' => $bunga_tenors // lempar ke view
        ]);
    }

    public function store(Request $request)
    {
        $validTenors = BungaTenor::pluck('tenor')->toArray();

        // Validasi input
        $request->validate([
            'id_nasabah'   => 'required|exists:nasabah,id_nasabah',
            'nama_barang'  => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'imei'         => 'required|string|unique:barang_gadai,imei', // Wajib diisi dan unik
            'tenor'        => 'required|in:' . implode(',', $validTenors),
            'harga_gadai'  => 'required|numeric|min:0',
            'status'       => 'required|in:Tergadai,Ditebus,Dilelang',
            'id_kategori'  => 'nullable|exists:kategori_barang,id_kategori',
        ]);

        // Konversi tenor ke integer agar kompatibel dengan Carbon
            $tenor = (int) $request->tenor;
            $tempo = Carbon::now()->addDays($tenor)->format('Y-m-d');


        BarangGadai::create([

            'id_user' => auth()->id(), // Isi dengan ID admin yang login
            'no_bon' => $request->no_bon,
            'id_nasabah'   => $request->id_nasabah,
            'nama_barang'  => $request->nama_barang,
            'deskripsi'    => $request->deskripsi,
            'imei'         => $request->imei,
            'tenor'        => $request->tenor,
            'bunga'        => $bungaTenor->bunga_percent,
            'tempo'        => $tempo, // Menyimpan tanggal jatuh tempo
            'telat'        => 0, // Default keterlambatan 0
            'harga_gadai'  => $request->harga_gadai,
            'status'       => $request->status,
            'id_kategori'  => $request->id_kategori,
        ]);

        // return redirect()->route('barang_gadai.index')->with('success', 'Barang gadai berhasil ditambahkan.');
        return redirect()->route('barang_gadai.index')->with('success', 'Barang Gadai berhasil ditambahkan!');
    }


    public function show(BarangGadai $barangGadai)
    {
        return view('barang_gadai.show', compact('barangGadai'));
    }

    public function edit(BarangGadai $barangGadai)
    {
        if (!auth()->user()->isSuperadmin() && $barangGadai->id_user !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $nasabah = Nasabah::all();
        $kategori = KategoriBarang::all();
        return view('barang_gadai.edit', compact('barangGadai', 'nasabah', 'kategori'));
    }


    public function update(Request $request, BarangGadai $barangGadai)
    {
        if ($barangGadai->id_user !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'id_nasabah' => 'required|exists:nasabah,id_nasabah',
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:Tergadai,Ditebus,Dilelang',
            'id_kategori' => 'nullable|exists:kategori_barang,id_kategori',
        ]);

        $barangGadai->update($request->all());

        return redirect()->route('barang_gadai.index')->with('success', 'Barang gadai berhasil diperbarui.');
    }



    public function destroy(BarangGadai $barangGadai)
    {
        if ($barangGadai->id_user !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $barangGadai->delete();
        return redirect()->route('barang_gadai.index')->with('success', 'Barang gadai berhasil dihapus.');
    }

}
