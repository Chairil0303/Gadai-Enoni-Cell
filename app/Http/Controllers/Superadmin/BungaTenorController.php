<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\BungaTenor;
use Illuminate\Http\Request;

class BungaTenorController extends Controller
{
    public function index()
    {
        $data = BungaTenor::all();
        return view('superadmin.bunga_tenor.index', compact('data'));
    }

    public function create()
    {
        return view('superadmin.bunga_tenor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tenor' => 'required|integer|unique:bunga_tenor,tenor',
            'bunga_percent' => 'required|numeric|min:0|max:100',
        ]);

        BungaTenor::create($request->only('tenor', 'bunga_percent'));

        return redirect()->route('superadmin.bunga-tenor.index')->with('success', 'Data bunga tenor berhasil ditambahkan.');
    }

    public function edit(BungaTenor $bungaTenor)
    {
        return view('superadmin.bunga_tenor.edit', compact('bungaTenor'));
    }

    public function update(Request $request, BungaTenor $bungaTenor)
    {
        $request->validate([
            'tenor' => 'required|integer|unique:bunga_tenor,tenor,' . $bungaTenor->id,
            'bunga_percent' => 'required|numeric|min:0|max:100',
        ]);

        $bungaTenor->update($request->only('tenor', 'bunga_percent'));

        return redirect()->route('superadmin.bunga-tenor.index')->with('success', 'Data bunga tenor berhasil diperbarui.');
    }

    public function destroy(BungaTenor $bungaTenor)
    {
        $bungaTenor->delete();
        return back()->with('success', 'Data bunga tenor berhasil dihapus.');
    }
}
