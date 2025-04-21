<?php

// app/Http/Controllers/Superadmin/CabangController.php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    public function index()
    {
        $cabangs = Cabang::all();
        return view('superadmin.cabang.index', compact('cabangs'));
    }

    public function create()
    {
        return view('superadmin.cabang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kontak' => 'required|string|max:255',
        ]);

        Cabang::create($request->all());

        return redirect()->route('superadmin.cabang.index')->with('success', 'Cabang berhasil ditambahkan');
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
