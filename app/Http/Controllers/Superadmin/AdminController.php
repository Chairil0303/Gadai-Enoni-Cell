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

    
    public function create()
    {
        $cabangs = Cabang::all();
        return view('superadmin.admins.create', compact('cabangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'id_cabang' => 'nullable|exists:cabang,id_cabang',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'Admin',
            'id_cabang' => $request->id_cabang,
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
