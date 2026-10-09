<?php

namespace App\Http\Controllers;

use App\Support\ImageOptimizer;
use App\Support\Site;
use App\Models\Projet;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class ProjetController extends Controller
{
    //index -> page de listing des projets
    public function index()
    {
        $projets = \App\Models\Projet::orderBy('order')->get();
        return view('backend.pages.projets.index', compact('projets'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|string',
            'progress' => 'nullable|integer|min:0|max:100',
            'date_start' => 'nullable|date',
            'date_end' => 'nullable|date',
        ]);

        $path = null;

        // upload image
        if ($request->hasFile('image')) {
            $path = ImageOptimizer::store($request->file('image'), 'projets');
        }

        \App\Models\Projet::create([
            'title' => $request->title,
            'slug' => Site::uniqueSlug(Projet::class, $request->title),
            'description' => $request->description,
            'image' => $path,
            'status' => $request->status,
            'progress' => $request->progress ?? 0,
            'date_start' => $request->date_start,
            'date_end' => $request->date_end,
            'order' => 0
        ]);

        Alert::success('Opération réussie', 'Le projet a été créé avec succès');
        return back();
    }

    public function destroy(Projet $projet): JsonResponse
    {
        // supprimer image si existe
        if ($projet->image && Storage::disk('public')->exists($projet->image)) {
            Storage::disk('public')->delete($projet->image);
        }

        $projet->delete();

        return response()->json(['status' => 200]);
    }



    public function update(Request $request, Projet $projet)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|string',
            'progress' => 'nullable|integer|min:0|max:100',
            'date_start' => 'nullable|date',
            'date_end' => 'nullable|date',
        ]);

        // upload nouvelle image
        if ($request->hasFile('image')) {

            // supprimer ancienne image
            if ($projet->image && Storage::disk('public')->exists($projet->image)) {
                Storage::disk('public')->delete($projet->image);
            }

            $path = ImageOptimizer::store($request->file('image'), 'projets');

            $projet->image = $path;
        }

        // mise à jour des champs
        // le slug n'est pas modifié : l'adresse publique du projet reste stable
        $projet->title = $request->title;
        $projet->description = $request->description;
        $projet->status = $request->status;
        $projet->progress = $request->progress ?? 0;
        $projet->date_start = $request->date_start;
        $projet->date_end = $request->date_end;
        $projet->save();

        Alert::success('Opération réussie', 'Le projet a été modifié avec succès');
        return back();
    }
}
