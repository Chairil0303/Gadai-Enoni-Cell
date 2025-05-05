<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function show($id)
    {
        if ($id === 'harian') {
            return view('admin.laporan.harian');
        } elseif ($id === 'bulanan') {
            return view('admin.laporan.bulanan');
        } else {
            abort(404);
        }
    }
    // method lainnya bisa diisi sesuai kebutuhan (create, store, etc.)
}
