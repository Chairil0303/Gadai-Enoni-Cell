<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index($id)
    {
        $cabang = Cabang::findOrFail($id);

        if (Auth::user()->cabang_id !== $cabang->id_cabang) {
            abort(403);
        }

        return view('admin.dashboard', compact('cabang'));
    }
}
