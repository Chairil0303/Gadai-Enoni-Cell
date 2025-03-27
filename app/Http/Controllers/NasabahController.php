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
        $nasabah = Nasabah::with('user')->get();
        return view('nasabah.index', compact('nasabah'));
    }

    public function myProfile()
    {
        $nasabah = Nasabah::with('barangGadai')
            ->where('id_user', auth()->user()->id_users)
            ->firstOrFail();

        return view('components.dashboard_nasabah.show', compact('nasabah'));
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
            'telepon' => 'required'
        ]);

        // Buat akun user untuk login
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => 'nasabah', // Role otomatis 'nasabah'
        ]);

        // Buat data nasabah
        Nasabah::create([
            'id_users' => $user->id_users, // Hubungkan dengan user yang dibuat
            'nama' => $request->nama,
            'nik' => $request->nik,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'status_blacklist' => $request->has('status_blacklist'),
        ]);

        return redirect()->route('superadmin.nasabah.index')->with('success', 'Nasabah berhasil ditambahkan');
    }

    public function edit($id)
    {
        $nasabah = Nasabah::with('user')->findOrFail($id);
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
            'username' => 'required|unique:users,username,' . $nasabah->id_users . ',id_users',
        ]);

        // Update data nasabah
        $nasabah->update([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'status_blacklist' => $request->input('status_blacklist', 0),
        ]);

        // Update data user
        $nasabah->user->update([
            'username' => $request->username,
            'password' => $request->password ? Hash::make($request->password) : $nasabah->user->password,
        ]);

        return redirect()->route('superadmin.nasabah.index')->with('success', 'Nasabah berhasil diperbarui');
    }

    public function destroy($id)
    {
        $nasabah = Nasabah::findOrFail($id);

        // Hapus data user juga agar tidak ada data yang menggantung
        $nasabah->user->delete();
        $nasabah->delete();

        return redirect()->route('superadmin.nasabah.index')->with('success', 'Nasabah berhasil dihapus');
    }
}
