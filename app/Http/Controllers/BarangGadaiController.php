<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangGadai;
use App\Models\Nasabah;
use App\Models\KategoriBarang;
use App\Models\BungaTenor;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class BarangGadaiController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $status = $request->input('status');
        $noBon = $request->input('no_bon');

        if ($userId == 1) {
            // Superadmin: ambil semua data
            $query = BarangGadai::with('nasabah.user', 'kategori');
        } else {
            if (Schema::hasColumn('barang_gadai', 'id_cabang')) {
                $query = BarangGadai::with('nasabah.user', 'kategori')
                    ->where('id_cabang', auth()->user()->id_cabang);
            } else {
                $barangGadai = collect(); // Kolom tidak tersedia
                return view('barang_gadai.index', compact('barangGadai'));
            }
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($noBon) {
            $query->where('no_bon', 'like', '%' . $noBon . '%');
        }

        $barangGadai = $query->get();

        return view('barang_gadai.index', compact('barangGadai'));
    }



    public function tampilBarangDiperpanjangDenganDm()
    {
        $query = BarangGadai::with('nasabah.user', 'kategori', 'cabang', 'bonLama') // tambahkan bonLama
            ->where('no_bon', 'LIKE', '%DM%');

        if (auth()->id() != 1 && Schema::hasColumn('barang_gadai', 'id_cabang')) {
            $query->where('id_cabang', auth()->user()->id_cabang);
        }

        $barangGadai = $query->get();

        return view('transaksi_gadai.ubahnobon', compact('barangGadai'));
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
        'imei'         => 'required|string|unique:barang_gadai,imei',
        'tenor'        => 'required|in:' . implode(',', $validTenors),
        'harga_gadai'  => 'required|numeric|min:0',
        'status'       => 'required|in:Tergadai,Ditebus,Dilelang',
        'id_kategori'  => 'nullable|exists:kategori_barang,id_kategori',
    ]);

    // Konversi tenor ke integer
    $tenor = (int) $request->tenor;
    $tempo = Carbon::now()->addDays($tenor)->format('Y-m-d');

    // Ambil bunga berdasarkan tenor yang dipilih
    $bungaTenor = BungaTenor::where('tenor', $tenor)->first();
    $bungaPercent = $bungaTenor ? $bungaTenor->bunga_percent : 0; // default 0 kalau tidak ketemu

    BarangGadai::create([
        'id_user'      => auth()->id(),
        'no_bon'       => $request->no_bon,
        'id_nasabah'   => $request->id_nasabah,
        'nama_barang'  => $request->nama_barang,
        'deskripsi'    => $request->deskripsi,
        'imei'         => $request->imei,
        'tenor'        => $request->tenor,
        'bunga'        => $bungaPercent,
        'tempo'        => $tempo,
        'telat'        => 0,
        'harga_gadai'  => $request->harga_gadai,
        'status'       => $request->status,
        'id_kategori'  => $request->id_kategori,
    ]);

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

    public function editNobon($no_bon)
{
    $barangGadai = BarangGadai::where('no_bon', $no_bon)->firstOrFail();
    return view('transaksi_gadai.edit', compact('barangGadai'));
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

    public function updateNobon(Request $request, $no_bon)
{
    // Ambil data barang gadai berdasarkan no_bon
    $barangGadai = BarangGadai::where('no_bon', $no_bon)->firstOrFail();

    // Validasi input no_bon agar unik, kecuali untuk barang gadai yang sedang di-update
    $request->validate([
        'no_bon' => [
            'required',
            'string',
            Rule::unique('barang_gadai', 'no_bon')->ignore($barangGadai->no_bon, 'no_bon'),
        ],
    ]);

    // Update no_bon dengan nilai yang diberikan admin
    $barangGadai->update([
        'no_bon' => $request->no_bon,
    ]);

    // Redirect ke halaman index dengan pesan sukses
    return redirect()->route('barang_gadai.diperpanjang_dm')->with('success', 'Nomor Bon berhasil diperbarui.');

    // return redirect()->route('tampilBarangDiperpanjangDenganDm')->with('success', 'Nomor Bon berhasil diperbarui.');
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
