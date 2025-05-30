<?php

// app/Http/Controllers/Superadmin/CabangController.php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use Illuminate\Http\Request;
use App\Models\SaldoCabang;
use App\Helpers\ActivityLogger;

class CabangController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
    
        $cabangs = Cabang::with('saldoCabang') // eager load saldoCabang
            ->when($search, function($query, $search) {
                return $query->where('nama_cabang', 'like', "%{$search}%");
            })
            ->orderBy('nama_cabang')
            ->paginate(10);
    
        return view('superadmin.cabang.index', compact('cabangs'));
    }
    
    
    public function create()
    {
        return view('superadmin.cabang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kontak' => 'required|string|max:20',
            'google_maps_link' => 'nullable|url',
            'saldo_awal' => 'required|numeric|min:0',
        ]);

        $saldo_awal = $validated['saldo_awal'];
        unset($validated['saldo_awal']);

        $cabang = Cabang::create($validated);

        $saldo = SaldoCabang::create([
            'id_cabang' => $cabang->id_cabang,
            'saldo_awal' => $saldo_awal,
            'saldo_saat_ini' => $saldo_awal,
        ]);

        ActivityLogger::log(
            kategori: 'cabang',
            aksi: 'create',
            deskripsi: "Menambahkan cabang baru: {$cabang->nama_cabang} dengan saldo awal",
            dataLama: null,
            dataBaru: [
                'cabang' => $cabang->toArray(),
                'saldo' => $saldo->toArray(),
            ]
        );

        return redirect()->route('superadmin.admins.create', ['id_cabang' => $cabang->id_cabang])
                        ->with('message', 'Cabang berhasil ditambahkan beserta saldo awal. Silakan buat admin.');
    }



    public function edit(Cabang $cabang)
    {
        return view('superadmin.cabang.edit', compact('cabang'));
    }


    public function update(Request $request, Cabang $cabang)
    {
        $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kontak' => 'required|string|max:255',
        ]);

        $dataLama = $cabang->toArray();

        $cabang->update($request->all());

        ActivityLogger::log(
            kategori: 'cabang',
            aksi: 'update',
            deskripsi: "Memperbarui data cabang: {$cabang->nama_cabang}",
            dataLama: $dataLama,
            dataBaru: $cabang->toArray()
        );

        return redirect()->route('superadmin.cabang.index')->with('success', 'Cabang berhasil diperbarui');
    }


    public function destroy(Cabang $cabang)
    {
        $dataLama = $cabang->toArray();
        $namaCabang = $cabang->nama_cabang;

        $cabang->delete();

        ActivityLogger::log(
            kategori: 'cabang',
            aksi: 'delete',
            deskripsi: "Menghapus cabang: {$namaCabang}",
            dataLama: $dataLama,
            dataBaru: null
        );

        return redirect()->route('superadmin.cabang.index')->with('success', 'Cabang berhasil dihapus');
    }


    
}
