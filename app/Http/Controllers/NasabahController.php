<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nasabah;
use App\Models\User;
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

    public function show($id)
{
    // Ambil user yang sedang login
    $user = auth()->user();

    // Debugging untuk cek role user
    // dd("Middleware role dijalankan untuk role: " . $user->role);

    $nasabah = Nasabah::findOrFail($id);
    return view('nasabah.show', compact('nasabah'));
}



    public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'nik' => 'required|string|unique:nasabah,nik',
        'alamat' => 'required|string',
        'telepon' => 'required|string|max:15',
        'username' => 'required|string|unique:users,username',
        'password' => 'required|string|min:6',
    ]);

    // Simpan ke tabel nasabah
    $nasabah = Nasabah::create([
        'nama' => $request->nama,
        'nik' => $request->nik,
        'alamat' => $request->alamat,
        'telepon' => $request->telepon,
        'status_blacklist' => false,
        'username' => $request->username,
        'password' => Hash::make($request->password),
    ]);

    // Simpan ke tabel users
    User::create([
        'nama' => $request->nama,
        'email' => $request->username . '@example.com', // Bisa disesuaikan
        'username' => $request->username,
        'password' => Hash::make($request->password),
        'role' => 'Nasabah', // Role nasabah
        'id_cabang' => null
    ]);

    return redirect()->back()->with('success', 'Nasabah berhasil ditambahkan!');
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
        'status_blacklist' => $request->input('status_blacklist', 0),
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
