<?php

namespace App\Http\Controllers;

use App\Models\TermsCondition;
use Illuminate\Http\Request;

class AdminTermsController extends Controller
{
     public function edit()
    {
        $terms = TermsCondition::first();
        return view('admin.terms.edit', compact('terms'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $terms = TermsCondition::first();
        if (!$terms) {
            $terms = new TermsCondition();
        }

        $terms->title = $request->input('title');
        $terms->content = $request->input('content');
        $terms->save();

        return redirect()->route('admin.terms.edit')->with('success', 'Syarat & Ketentuan berhasil diperbarui.');
    }

}
