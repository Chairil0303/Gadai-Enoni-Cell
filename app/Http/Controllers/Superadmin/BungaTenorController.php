<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\BungaTenor;
use Illuminate\Http\Request;
use App\Helpers\ActivityLogger;

class BungaTenorController extends Controller
{
    public function index()
    {
        $data = BungaTenor::all();
        return view('superadmin.bunga_tenor.index', compact('data'));
    }

    public function create()
    {
        return view('superadmin.bunga_tenor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tenor' => 'required|integer|unique:bunga_tenor,tenor',
            'bunga_percent' => 'required|numeric|min:0|max:100',
        ]);

        $bunga = BungaTenor::create($request->only('tenor', 'bunga_percent'));

        ActivityLogger::log(
            kategori: 'bunga_tenor',
            aksi: 'create',
            deskripsi: "Menambahkan data bunga tenor {$bunga->tenor} bulan",
            dataLama: null,
            dataBaru: $bunga->toArray()
        );

        return redirect()->route('superadmin.bunga-tenor.index')->with('success', 'Data bunga tenor berhasil ditambahkan.');
    }


    public function edit(BungaTenor $bungaTenor)
    {
        return view('superadmin.bunga_tenor.edit', compact('bungaTenor'));
    }

    public function update(Request $request, BungaTenor $bungaTenor)
    {
        $request->validate([
            'tenor' => 'required|integer|unique:bunga_tenor,tenor,' . $bungaTenor->id,
            'bunga_percent' => 'required|numeric|min:0|max:100',
        ]);

        $dataLama = $bungaTenor->toArray();

        $bungaTenor->update($request->only('tenor', 'bunga_percent'));

        ActivityLogger::log(
            kategori: 'bunga_tenor',
            aksi: 'update',
            deskripsi: "Memperbarui bunga tenor {$bungaTenor->tenor} bulan",
            dataLama: $dataLama,
            dataBaru: $bungaTenor->toArray()
        );

        return redirect()->route('superadmin.bunga-tenor.index')->with('success', 'Data bunga tenor berhasil diperbarui.');
    }

    public function destroy(BungaTenor $bungaTenor)
    {
        $dataLama = $bungaTenor->toArray();
        $tenorLabel = $bungaTenor->tenor;

        $bungaTenor->delete();

        ActivityLogger::log(
            kategori: 'bunga_tenor',
            aksi: 'delete',
            deskripsi: "Menghapus data bunga tenor {$tenorLabel} bulan",
            dataLama: $dataLama,
            dataBaru: null
        );

        return back()->with('success', 'Data bunga tenor berhasil dihapus.');
    }

}
