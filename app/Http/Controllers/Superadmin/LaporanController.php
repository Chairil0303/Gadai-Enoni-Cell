<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cabang;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class LaporanController extends Controller
{
    public function index()
    {
        $cabangs = \App\Models\Cabang::all();
        return view('superadmin.laporan.index', compact('cabangs'));
    }

    public function detail(Request $request)
    {
    // dd($request->all());
        $validator = Validator::make($request->all(), [
            'cabang_id' => 'required|exists:cabang,id_cabang',
            'tipe' => 'required|in:harian,bulanan',
            'tanggal' => 'nullable|required_if:tipe,harian|date',
            'bulan' => 'nullable|required_if:tipe,bulanan|date_format:Y-m',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $query = Transaksi::where('id_cabang', $request->cabang_id);

        if ($request->tipe === 'harian') {
            $query->whereDate('created_at', $request->tanggal);
        } else {
            [$tahun, $bulan] = explode('-', $request->bulan);
            $query->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $bulan);
        }

        $laporan = $query->with(['nasabah', 'barangGadai'])->orderBy('created_at', 'desc')->get();
        $cabang = \App\Models\Cabang::find($request->cabang_id);

        return view('superadmin.laporan.detail', compact('laporan', 'cabang', 'request'));
    }
}

