<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nasabah;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class NasabahController extends Controller
{
    public function index()
    {
        $nasabah = Nasabah::with('user')->get();
        $barangGadai = $nasabah->barangGadai;
        // Menghitung keterlambatan
        foreach ($barangGadai as $barang) {
            $today = \Carbon\Carbon::today();
            $tempo = \Carbon\Carbon::parse($barang->tempo);
            $barang->telat = $today->gt($tempo) ? $today->diffInDays($tempo) : 0;
        }

        return view('nasabah.index', compact('nasabah'));
    }

    // public function myProfile()
    // {
    //     $nasabah = Nasabah::with('barangGadai')
    //         ->where('id_user', auth()->user()->id_users)
    //         ->firstOrFail();

    //     return view('components.dashboard_nasabah.show', compact('nasabah'));
    // }


    public function show()
    {
    $nasabah = Nasabah::all();

        // return view('nasabah.profile'); // Pastikan path view kamu benar, misalnya resources/views/nasabah/profile.blade.php
        // Mendapatkan data nasabah berdasarkan user yang sedang login
        $nasabah = Nasabah::where('id_user', Auth::id())->first(); // Sesuaikan relasi jika berbeda

        if (!$nasabah) {
            return redirect()->route('dashboard.nasabah')->with('error', 'Data nasabah tidak ditemukan.');
        }
        $barangGadai = $nasabah->barangGadai;
        foreach ($barangGadai as $barang) {
            $today = \Carbon\Carbon::today();
            $tempo = \Carbon\Carbon::parse($barang->tempo);
            $barang->telat = $today->gt($tempo) ? $today->diffInDays($tempo) : 0;
        }


        return view('nasabah.dashboard', compact('nasabah', 'barangGadai'));
    }

    public function myProfile()
    {
        $nasabah = Nasabah::where('id_users', auth()->user()->id_users)->firstOrFail();
        return view('components.dashboard.nasabah', compact('nasabah'));
        foreach ($barangGadai as $barang) {
            $today = \Carbon\Carbon::today();
            $tempo = \Carbon\Carbon::parse($barang->tempo);
            $barang->telat = $today->gt($tempo) ? $today->diffInDays($tempo) : 0;
        }

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
            'email' => 'nullable|email|unique:users,email',
            'alamat' => 'required|string',
            'telepon' => 'required|string|min:10'
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
