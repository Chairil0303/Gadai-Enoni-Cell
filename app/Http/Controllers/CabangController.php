<?php

// app/Http/Controllers/CabangController.php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    public function index()
    {
        // Misalnya kamu mau ambil cabang tertentu berdasarkan ID
        // $cabang = Cabang::where('id_cabang', 1)->first(); // Ganti 1 dengan ID cabang yang kamu inginkan
        $cabangs = Cabang::all();
        
        // Jika ingin mengambil semua cabang, gunakan:
        // $cabang = Cabang::all();

        // return view('tebus_gadai.navbar', compact('cabang'));
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
            'kontak' => 'required|string|max:20',
            'google_maps_link' => 'nullable|url',
        ]);

        Cabang::create([
            'nama_cabang' => $request->nama_cabang,
            'alamat' => $request->alamat,
            'kontak' => $request->kontak,
            'google_maps_link' => $request->google_maps_link,
        ]);

        // return redirect()->route('cabang.index')->with('success', 'Cabang berhasil ditambahkan.');
        // return redirect()->route('superadmin.cabang.index')->with('success', 'Cabang berhasil ditambahkan.');
        return redirect()->route('superadmin.cabang.index')->with('success', 'Cabang berhasil ditambahkan.');

    }

    public function show($id)
    {
        $cabang = Cabang::findOrFail($id);
        return view('superadmin.cabang.show', compact('cabang'));
    }

    public function edit($id)
    {
        $cabang = Cabang::findOrFail($id);
        return view('superadmin.cabang.edit', compact('cabang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kontak' => 'required|string|max:20',
            'google_maps_link' => 'nullable|url',
        ]);

        $cabang = Cabang::findOrFail($id);
        $cabang->update($request->all());

        return redirect()->route('superadmin.cabang.index')->with('success', 'Cabang berhasil diperbarui.');

    }

    public function destroy($id)
    {
        $cabang = Cabang::findOrFail($id);
        $cabang->delete();

        return redirect()->route('superadmin.cabang.index')->with('success', 'Cabang berhasil dihapus.');

    }
}
