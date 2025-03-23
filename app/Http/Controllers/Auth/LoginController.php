<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Menangani proses login
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            return $this->authenticated($request, Auth::user());
        }

        // Jika gagal, tampilkan pesan error menggunakan session
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    // Redirect berdasarkan role setelah login sukses
    protected function authenticated(Request $request, $user)
{
    if ($user->role === 'Superadmin') {
        // Kirim flash message ke session
        session()->flash('success', 'Selamat datang, Superadmin!');
        return redirect('/dashboard/superadmin');
    } elseif ($user->role === 'Admin') {
        session()->flash('success', 'Selamat datang, Admin!');
        return redirect('/dashboard/admin');
    }

    session()->flash('success', 'Selamat datang di Dashboard!');
    return redirect('/dashboard');
}

}
