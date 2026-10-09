<?php

namespace App\Http\Controllers;

use App\Support\ImageOptimizer;
use App\Models\Realisation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class RealisationController extends Controller
{
    public function index()
    {
        $realisations = Realisation::orderBy('order')->get();
        return view('backend.pages.realisations.index', compact('realisations'));
    }

   public function store(Request $request)
{
    // 1. Validation (avec vérification du poids et de l'extension)
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // 2Mo max
        'date_start' => 'required|date',
        'date_end' => 'required|date|after_or_equal:date_start',
    ]);

    // 2. Upload de l'image de manière sécurisée
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = ImageOptimizer::store($request->file('image'), 'realisations');
    }

    // 3. Création dans la base de données
    Realisation::create([
        'title' => $request->title,
        'description' => $request->description,
        'image' => $imagePath,
        'date_start' => $request->date_start,
        'date_end' => $request->date_end,
        'order' => (Realisation::max('order') ?? 0) + 1
       
    ]);

    Alert::success('Opération réussie', 'La réalisation a été créée avec succès');
    return back();
}
    public function update(Request $request, Realisation $realisation)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|image',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after_or_equal:date_start',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($realisation->image);
            $realisation->image = ImageOptimizer::store($request->file('image'), 'realisations');
        }

        $realisation->update([
            'title' => $request->title,
            'description' => $request->description,
            'date_start' => $request->date_start,
            'date_end' => $request->date_end,
        ]);

        Alert::success('Opération réussie', 'La réalisation a été modifiée avec succès');
        return back();
    }

    public function destroy(Realisation $realisation): JsonResponse
    {
        if ($realisation->image && Storage::disk('public')->exists($realisation->image)) {
            Storage::disk('public')->delete($realisation->image);
        }
        $realisation->delete();

        return response()->json([
            'status' => 200,
        ]);
    }

    public function reorder(Request $request)
    {
        foreach ($request->order as $index => $id) {
            Realisation::where('id', $id)->update(['order' => $index]);
        }

        return response()->json(['status' => 'ok']);
    }
}
