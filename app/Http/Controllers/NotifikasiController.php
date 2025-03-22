<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifikasi;
use App\Models\Nasabah;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Notifikasi::latest()->get();
        return view('notifikasi.index', compact('notifikasi'));
    }

    public function create()
    {
        $nasabah = Nasabah::all();
        return view('notifikasi.create', compact('nasabah'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_nasabah' => 'required|exists:nasabah,id_nasabah',
            'jenis_notifikasi' => 'required|string',
            'isi_pesan' => 'required|string',
            'status_kirim' => 'boolean',
            'tanggal_kirim' => 'required|date',
        ]);

        Notifikasi::create($request->all());

        return redirect()->route('notifikasi.index')->with('success', 'Notifikasi berhasil dikirim.');
    }

    public function show(Notifikasi $notifikasi)
    {
        return view('notifikasi.show', compact('notifikasi'));
    }

    public function edit(Notifikasi $notifikasi)
    {
        $nasabah = Nasabah::all();
        return view('notifikasi.edit', compact('notifikasi', 'nasabah'));
    }

    public function update(Request $request, Notifikasi $notifikasi)
    {
        $request->validate([
            'id_nasabah' => 'required|exists:nasabah,id_nasabah',
            'jenis_notifikasi' => 'required|string',
            'isi_pesan' => 'required|string',
            'status_kirim' => 'boolean',
            'tanggal_kirim' => 'required|date',
        ]);

        $notifikasi->update($request->all());

        return redirect()->route('notifikasi.index')->with('success', 'Notifikasi berhasil diperbarui.');
    }

    public function destroy(Notifikasi $notifikasi)
    {
        $notifikasi->delete();
        return redirect()->route('notifikasi.index')->with('success', 'Notifikasi berhasil dihapus.');
    }
}
