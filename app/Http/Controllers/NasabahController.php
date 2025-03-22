<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nasabah;
use Illuminate\Support\Facades\Hash;

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
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:16|unique:nasabah,nik',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:15',
            'username' => 'required|string|unique:nasabah,username',
            'password' => 'required|string|min:6',
        ]);

        Nasabah::create([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('nasabah.index')->with('success', 'Nasabah berhasil ditambahkan.');
    }

    public function show(Nasabah $nasabah)
    {
        return view('nasabah.show', compact('nasabah'));
    }

    public function edit(Nasabah $nasabah)
    {
        return view('nasabah.edit', compact('nasabah'));
    }

    public function update(Request $request, Nasabah $nasabah)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:16|unique:nasabah,nik,' . $nasabah->id_nasabah,
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:15',
            'username' => 'required|string|unique:nasabah,username,' . $nasabah->id_nasabah,
        ]);

        $data = $request->only(['nama', 'nik', 'alamat', 'telepon', 'username']);
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $nasabah->update($data);

        return redirect()->route('nasabah.index')->with('success', 'Nasabah berhasil diperbarui.');
    }

    public function destroy(Nasabah $nasabah)
    {
        $nasabah->delete();
        return redirect()->route('nasabah.index')->with('success', 'Nasabah berhasil dihapus.');
    }
}
