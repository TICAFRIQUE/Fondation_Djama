<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Support\ImageOptimizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class TestimonialController extends Controller
{
    public function index()
    {
        $temoignages = Testimonial::orderBy('order')->get();
        return view('backend.pages.temoignages.index', compact('temoignages'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('photo')) {
            $data['photo'] = ImageOptimizer::store($request->file('photo'), 'temoignages', 400);
        }

        Testimonial::create($data);

        Alert::success('Opération réussie', 'Le témoignage a été créé avec succès');
        return back();
    }

    public function update(Request $request, Testimonial $temoignage)
    {
        $data = $this->validated($request);

        if ($request->hasFile('photo')) {
            $this->deletePhoto($temoignage);
            $data['photo'] = ImageOptimizer::store($request->file('photo'), 'temoignages', 400);
        }

        $temoignage->update($data);

        Alert::success('Opération réussie', 'Le témoignage a été modifié avec succès');
        return back();
    }

    public function destroy(Testimonial $temoignage): JsonResponse
    {
        $this->deletePhoto($temoignage);
        $temoignage->delete();

        return response()->json(['status' => 200]);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'content' => 'required|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        $data['order'] = $data['order'] ?? 0;
        unset($data['photo']);

        return $data;
    }

    private function deletePhoto(Testimonial $temoignage): void
    {
        if ($temoignage->photo && Storage::disk('public')->exists($temoignage->photo)) {
            Storage::disk('public')->delete($temoignage->photo);
        }
    }
}
