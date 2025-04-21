<?php

// app/Http/Controllers/Superadmin/CabangController.php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $cabangs = Cabang::when($search, function($query, $search) {
            return $query->where('nama_cabang', 'like', "%{$search}%");
        })->orderBy('nama_cabang')->paginate(10);

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
        ]);

        $cabang = Cabang::create($validated);

        // Redirect ke form tambah admin, kirim id_cabang
        return redirect()->route('superadmin.admins.create', ['id_cabang' => $cabang->id_cabang])
                         ->with('message', 'Cabang berhasil ditambahkan. Silakan buat admin.');
        // return redirect()->route('superadmin.admins.create', ['id_cabang' => $cabang->id_cabang]);

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

        $cabang->update($request->all());

        return redirect()->route('superadmin.cabang.index')->with('success', 'Cabang berhasil diperbarui');
    }

    public function destroy(Cabang $cabang)
    {
        $cabang->delete();

        return redirect()->route('superadmin.cabang.index')->with('success', 'Cabang berhasil dihapus');
    }
}
