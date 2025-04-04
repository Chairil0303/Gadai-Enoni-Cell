<?php

// app/Http/Controllers/CabangController.php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    public function index()
    {
        // Misalnya kamu mau ambil cabang tertentu berdasarkan ID
        $cabang = Cabang::where('id_cabang', 1)->first(); // Ganti 1 dengan ID cabang yang kamu inginkan
        
        // Jika ingin mengambil semua cabang, gunakan:
        // $cabang = Cabang::all();

        return view('tebus_gadai.navbar', compact('cabang'));
    }
}
