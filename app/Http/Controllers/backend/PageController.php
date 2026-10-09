<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Support\Site;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PageController extends Controller
{
    // Pages libres du site : mentions légales, politique de confidentialité, etc.
    public function index()
    {
        $pages = Page::orderBy('order')->get();
        return view('backend.pages.pages.index', compact('pages'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Site::uniqueSlug(Page::class, $request->title);

        Page::create($data);

        Alert::success('Opération réussie', 'La page a été créée avec succès');
        return back();
    }

    public function update(Request $request, Page $page)
    {
        // le slug n'est pas modifié : l'adresse publique de la page reste stable
        $page->update($this->validated($request));

        Alert::success('Opération réussie', 'La page a été modifiée avec succès');
        return back();
    }

    public function destroy(Page $page): JsonResponse
    {
        $page->delete();

        return response()->json(['status' => 200]);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'meta_description' => 'nullable|string|max:300',
            'content' => 'nullable|string',
            'show_in_footer' => 'required|boolean',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        $data['order'] = $data['order'] ?? 0;

        return $data;
    }
}
