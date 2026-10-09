<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Programme;
use App\Support\ImageOptimizer;
use App\Support\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class ProgrammeController extends Controller
{
    public function index()
    {
        $programmes = Programme::orderBy('order')->get();
        return view('backend.pages.programmes.index', compact('programmes'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image'] = ImageOptimizer::store($request->file('image'), 'programmes');
        }

        $data['slug'] = Site::uniqueSlug(Programme::class, $request->title);
        $data['order'] = $request->order ?? (Programme::max('order') ?? 0) + 1;
        $data['is_active'] = $request->boolean('is_active', true);

        Programme::create($data);

        Alert::success('Opération réussie', 'Le programme a été créé avec succès');
        return back();
    }

    public function update(Request $request, Programme $programme)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            if ($programme->image && Storage::disk('public')->exists($programme->image)) {
                Storage::disk('public')->delete($programme->image);
            }
            $data['image'] = ImageOptimizer::store($request->file('image'), 'programmes');
        }

        // le slug n'est pas modifié : l'adresse publique du programme reste stable
        $data['order'] = $request->order ?? $programme->order;
        $data['is_active'] = $request->boolean('is_active', $programme->is_active);

        $programme->update($data);

        Alert::success('Opération réussie', 'Le programme a été modifié avec succès');
        return back();
    }

    public function destroy(Programme $programme): JsonResponse
    {
        if ($programme->image && Storage::disk('public')->exists($programme->image)) {
            Storage::disk('public')->delete($programme->image);
        }

        $programme->delete();

        return response()->json(['status' => 200]);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:4096',
            'color_bg' => 'nullable|string|max:20',
            'color_text' => 'nullable|string|max:20',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);
    }
}
