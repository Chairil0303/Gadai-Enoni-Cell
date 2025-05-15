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
        'type' => 'required|unique:whatsapp_templates,type',
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

        return redirect()->route('admin.whatsapp_template.index')->with('success', 'Template berhasil diupdate.');
    }

    public function destroy($id)
    {
        $template = WhatsappTemplate::findOrFail($id);
        $template->delete();

        return redirect()->route('admin.whatssapp_template.index')->with('success', 'Template berhasil dihapus.');
    }
}
