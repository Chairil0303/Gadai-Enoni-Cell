<?php

namespace App\Http\Controllers;

use App\Models\WhatsappTemplate;
use Illuminate\Http\Request;

class WhatsappTemplateController extends Controller
{
    public function index()
    {
        $templates = WhatsappTemplate::all();
        return view('admin.whatssapp_template.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.whatssapp_template.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'message' => 'required',
        ]);

        WhatsappTemplate::create([
            'type' => $request->type,
            'message' => $request->message,
        ]);

        return redirect()->route('admin.whatsapp_template.index')->with('success', 'Template berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $template = WhatsappTemplate::findOrFail($id);
        return view('admin.whatssapp_template.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required',
            'message' => 'required',
        ]);

        $template = WhatsappTemplate::findOrFail($id);
        $template->update($request->only('type', 'message'));

        return redirect()->route('admin.whatsapp_template.index')
            ->with('success', 'Template WhatsApp berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $template = WhatsappTemplate::findOrFail($id);
        $template->delete();

        return redirect()->route('admin.whatsapp_template.index')
            ->with('success', 'Template berhasil dihapus.');
    }


    public function activate($id)
{
    $template = WhatsappTemplate::findOrFail($id);

    // Nonaktifkan semua template dengan tipe yang sama
    WhatsappTemplate::where('type', $template->type)->update(['is_active' => false]);

    // Aktifkan template ini
    $template->is_active = true;
    $template->save();

    return redirect()->back()->with('success', 'Template diaktifkan.');
}

public function deactivate($id)
{
    $template = WhatsappTemplate::findOrFail($id);
    $template->is_active = false;
    $template->save();

    return redirect()->back()->with('success', 'Template dinonaktifkan.');
}

}
