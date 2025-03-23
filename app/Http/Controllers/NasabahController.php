<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nasabah;

class NasabahController extends Controller
{
    public function index()
    {
        $nasabah = Nasabah::all();
        return view('nasabah.index', compact('nasabah'));
    }

    public function create()
    {
        return view('nasabah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nik' => 'required|unique:nasabah,nik',
            'alamat' => 'required',
            'telepon' => 'required',
            'username' => 'required|unique:nasabah,username',
            'password' => 'required|min:6',
        ]);

        Nasabah::create([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'status_blacklist' => $request->has('status_blacklist'),
            'username' => $request->username,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('superadmin.nasabah.index')->with('success', 'Nasabah berhasil ditambahkan');
    }

    public function edit($id)
    {
        $nasabah = Nasabah::findOrFail($id);
        return view('nasabah.edit', compact('nasabah'));
    }

    public function update(Request $request, $id)
    {
        $nasabah = Nasabah::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'nik' => 'required|unique:nasabah,nik,' . $id . ',id_nasabah',
            'alamat' => 'required',
            'telepon' => 'required',
            'username' => 'required|unique:nasabah,username,' . $id . ',id_nasabah',
        ]);

        $nasabah->update([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'status_blacklist' => $request->has('status_blacklist'),
            'username' => $request->username,
            'password' => $request->password ? bcrypt($request->password) : $nasabah->password,
        ]);

        return redirect()->route('superadmin.nasabah.index')->with('success', 'Nasabah berhasil diperbarui');
    }

    public function destroy($id)
    {
        $nasabah = Nasabah::findOrFail($id);
        $nasabah->delete();

        return redirect()->route('superadmin.nasabah.index')->with('success', 'Nasabah berhasil dihapus');
    }
}
