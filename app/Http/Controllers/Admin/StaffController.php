<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $admin = auth()->user();

        // Ambil staf dengan role 'Staf' dan id_cabang yang sama dengan admin yang login
        $staffs = User::where('role', 'Staf')
                    ->where('id_cabang', $admin->id_cabang)
                    ->with('cabang')
                    ->get();

        return view('admin.staff.index', compact('staffs'));
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'id_cabang' => 'nullable|exists:cabang,id_cabang',
        ]);

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'Staf',
            'id_cabang' => $request->id_cabang,
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil ditambahkan.');
    }

    public function show(User $staff)
    {
        return view('admin.staff.show', compact('staff'));
    }

    public function edit(User $staff)
    {
        return view('admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $staff->id_users . ',id_users',
            'username' => 'required|string|unique:users,username,' . $staff->id_users . ',id_users',
            'password' => 'nullable|string|min:6|confirmed',
            'id_cabang' => 'nullable|exists:cabang,id_cabang',
        ]);

        $staff->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'id_cabang' => $request->id_cabang,
            'password' => $request->password ? Hash::make($request->password) : $staff->password,
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil diperbarui.');
    }

    public function destroy(User $staff)
    {
        $staff->delete();
        return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil dihapus.');
    }
}
