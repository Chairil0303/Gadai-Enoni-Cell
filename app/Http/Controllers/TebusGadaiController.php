<?php

// app/Http/Controllers/TebusGadaiController.php

namespace App\Http\Controllers;

use App\Models\BarangGadai;
use App\Models\TransaksiTebus;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TebusGadaiController extends Controller
{
    public function index()
    {
        $barangGadai = BarangGadai::where('status', 'Tergadai')->get();
        return view('tebus_gadai.index', compact('barangGadai'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_bon' => 'required|exists:barang_gadai,no_bon',
            'id_nasabah' => 'required|exists:nasabah,id_nasabah',
            'jumlah_pembayaran' => 'required|numeric|min:0',
        ]);

        $barangGadai = BarangGadai::where('no_bon', $request->no_bon)->first();
        $barangGadai->status = 'Ditebus';
        $barangGadai->save();

        TransaksiTebus::create([
            'no_bon' => $request->no_bon,
            'id_nasabah' => $request->id_nasabah,
            'tanggal_tebus' => Carbon::now(),
            'jumlah_pembayaran' => $request->jumlah_pembayaran,
            'status' => 'Berhasil'
        ]);

        return redirect()->back()->with('success', 'Barang berhasil ditebus.');
    }
}
