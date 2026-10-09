<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\SiteSection;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SiteSectionController extends Controller
{
    // Sections de la page d'accueil : textes, ordre d'affichage et activation
    public function index()
    {
        SiteSection::syncDefaults();

        $sections = SiteSection::resolved();
        return view('backend.pages.sections.index', compact('sections'));
    }

    public function update(Request $request, SiteSection $section)
    {
        $data = $request->validate([
            'eyebrow' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:1000',
            'order' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        $section->update($data);

        Alert::success('Opération réussie', 'La section a été modifiée avec succès');
        return back();
    }
}
