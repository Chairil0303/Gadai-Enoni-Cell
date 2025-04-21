<?php
namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cabang;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        // ambil admin dan cabangnya, untuk eager loading
        $admins = User::where('role', 'Admin')->with('cabang')->get();
        return view('superadmin.admins.index', compact('admins'));
    }

    
    public function create(Request $request)
    {
        $idCabang = $request->get('id_cabang');
        $cabangs = Cabang::all(); // ambil semua untuk dropdown

        return view('superadmin.admins.create', compact('idCabang', 'cabangs'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'username' => 'required|unique:users,username',
            'password' => 'required|string|min:6', // hapus 'confirmed' kalau gak dipakai
            'id_cabang' => 'nullable|exists:cabang,id_cabang',
        ]);

        $user = User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => 'Admin',
            'id_cabang' => $validated['id_cabang'],
        ]);

        return redirect()->route('superadmin.admins.index')->with('success', 'Admin berhasil ditambahkan.');
    }



    public function edit($id)
    {
        $admin = User::findOrFail($id);
        $cabangs = Cabang::all();

        return view('superadmin.admins.edit', compact('admin', 'cabangs'));
    }


    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $id . ',id_users',
            'username' => 'required|string|max:255|unique:users,username,' . $id . ',id_users',
            'id_cabang' => 'nullable|exists:cabang,id_cabang',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $admin->nama = $request->nama;
        $admin->email = $request->email;
        $admin->username = $request->username;
        $admin->id_cabang = $request->id_cabang;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('superadmin.admins.index')->with('success', 'Admin berhasil diperbarui.');
    }


    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Admin berhasil dihapus');
    }
}
