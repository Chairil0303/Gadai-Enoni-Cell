<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nasabah;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\TermsCondition;
use App\Helpers\ActivityLogger;

class NasabahController extends Controller
{

    public function index(Request $request)
    {
        $query = Nasabah::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                ->orWhere('nik', 'like', "%{$search}%")
                ->orWhere('telepon', 'like', "%{$search}%");
            });
        }

        // PAGINATION: tampilkan 10 per halaman
        $nasabah = $query->paginate(10)->withQueryString();

        return view('nasabah.index', compact('nasabah'));
    }



  public function showTerms()
{
    $terms = TermsCondition::first();
    return view('nasabah.terms', compact('terms'));
}


    public function Profil()
    {
        $nasabah = Nasabah::with('barangGadai')
            ->where('id_user', auth()->user()->id_users)
            ->firstOrFail();

        return view('nasabah.show', compact('nasabah'));
    }




    public function show()
    {

    $user = Auth::user()->cabang;
    $nasabah = Nasabah::all();

        // return view('nasabah.profile'); // Pastikan path view kamu benar, misalnya resources/views/nasabah/profile.blade.php
        // Mendapatkan data nasabah berdasarkan user yang sedang login
        $nasabah = Nasabah::where('id_user', Auth::id())->first(); // Sesuaikan relasi jika berbeda

        if (!$nasabah) {
            return redirect()->route('dashboard.nasabah')->with('error', 'Data nasabah tidak ditemukan.');
        }
        $barangGadai = $nasabah->barangGadai;


        return view('nasabah.dashboard', compact('nasabah', 'barangGadai','user'));
    }

    public function myProfile()
    {
        $nasabah = Nasabah::where('id_users', auth()->user()->id_users)->firstOrFail();

        return view('components.dashboard.nasabah', compact('nasabah'));


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
        // Ambil data nasabah beserta user
        $nasabah = Nasabah::with('user')->findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'nik' => 'required|unique:nasabah,nik,' . $id . ',id_nasabah',
            'alamat' => 'required',
            'telepon' => 'required',
            'username' => 'required|unique:users,username,' . $nasabah->user->id_users . ',id_users',
        ]);

        // Simpan data lama untuk logging sebelum update
        $dataLama = [
            'nasabah' => $nasabah->only(['nama', 'nik', 'alamat', 'telepon', 'status_blacklist']),
            'user' => $nasabah->user->only(['username']),
        ];

        // Update data nasabah
        $nasabah->update([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'status_blacklist' => $request->input('status_blacklist', 0),
        ]);

        // Update data user (password hanya diubah jika diisi)
        $nasabah->user->update([
            'username' => $request->username,
            'password' => $request->password ? Hash::make($request->password) : $nasabah->user->password,
        ]);

        // Data baru setelah update
        $dataBaru = [
            'nasabah' => $nasabah->only(['nama', 'nik', 'alamat', 'telepon', 'status_blacklist']),
            'user' => $nasabah->user->only(['username']),
        ];

        // Deskripsi perubahan
        $deskripsi = "Update data Nasabah ID {$nasabah->id_nasabah} - Nama: {$nasabah->nama}";

        // Panggil helper ActivityLogger untuk log aktivitas
        ActivityLogger::log(
            kategori: 'nasabah',
            aksi: 'update',
            deskripsi: $deskripsi,
            dataLama: $dataLama,
            dataBaru: $dataBaru
        );

        return redirect()->route('nasabah.index')->with('success', 'Nasabah berhasil diperbarui');
    }


    public function destroy($id)
    {
        $nasabah = Nasabah::findOrFail($id);

        // Hapus data user juga agar tidak ada data yang menggantung
        $nasabah->user->delete();
        $nasabah->delete();

        return redirect()->route('superadmin.nasabah.index')->with('success', 'Nasabah berhasil dihapus');
    }

    public function profile()
    {
        $nasabah = Nasabah::with('user')
            ->where('id_user', auth()->user()->id_users)
            ->firstOrFail();

        return view('nasabah.profile', compact('nasabah'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diperbarui');
    }
}
