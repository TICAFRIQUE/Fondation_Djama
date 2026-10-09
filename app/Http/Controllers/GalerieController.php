<?php

namespace App\Http\Controllers;

use App\Models\Galerie;
use App\Support\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class GalerieController extends Controller
{
    private const VIDEO_EXTENSIONS = ['mp4', 'mov', 'avi', 'webm'];

    public function index()
    {
        $images = Galerie::orderBy('position')->latest()->get();
        return view('backend.pages.galerie.index', compact('images'));
    }

    public function store(Request $request)
    {
        $request->validate($this->rules(required: true));

        $file = $request->file('media');

        Galerie::create([
            'title' => $request->title,
            'path' => ImageOptimizer::store($file, 'galerie'),
            'type' => $this->typeOf($file->getClientOriginalExtension()),
            'position' => $request->position ?? 0,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        Alert::success('Opération réussie', 'Le média a été ajouté avec succès');
        return back();
    }

    public function update(Request $request, Galerie $galerie)
    {
        $request->validate($this->rules(required: false));

        if ($request->hasFile('media')) {
            // supprimer ancien fichier
            if ($galerie->path && Storage::disk('public')->exists($galerie->path)) {
                Storage::disk('public')->delete($galerie->path);
            }

            $file = $request->file('media');
            $galerie->type = $this->typeOf($file->getClientOriginalExtension());
            $galerie->path = ImageOptimizer::store($file, 'galerie');
        }

        $galerie->title = $request->title;
        $galerie->position = $request->position ?? 0;
        $galerie->is_featured = $request->boolean('is_featured');
        $galerie->save();

        Alert::success('Opération réussie', 'Le média a été modifié avec succès');
        return back();
    }

    public function destroy(Galerie $galerie): JsonResponse
    {
        // supprimer fichier
        if ($galerie->path && Storage::disk('public')->exists($galerie->path)) {
            Storage::disk('public')->delete($galerie->path);
        }

        $galerie->delete();

        return response()->json([
            'status' => 200,
        ]);
    }

    private function rules(bool $required): array
    {
        return [
            // 20 Mo : les vidéos dépassent vite les 2 Mo autorisés auparavant
            'media' => ($required ? 'required' : 'nullable') . '|file|mimes:jpg,jpeg,png,webp,mp4,mov,avi,webm|max:20480',
            'title' => 'nullable|string|max:255',
            'position' => 'nullable|integer|min:0',
            'is_featured' => 'nullable|boolean',
        ];
    }

    private function typeOf(string $extension): string
    {
        return in_array(strtolower($extension), self::VIDEO_EXTENSIONS) ? 'video' : 'image';
    }
}
