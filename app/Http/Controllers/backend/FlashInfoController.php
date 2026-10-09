<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\FlashInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class FlashInfoController extends Controller
{
    // Infos flash : bandeau d'annonces affiché en haut de toutes les pages du site
    public function index()
    {
        $flashInfos = FlashInfo::orderBy('order')->orderBy('id')->get();
        return view('backend.pages.flash-infos.index', compact('flashInfos'));
    }

    public function store(Request $request)
    {
        FlashInfo::create($this->validated($request));

        Alert::success('Opération réussie', "L'info flash a été créée avec succès");
        return back();
    }

    public function update(Request $request, FlashInfo $flashInfo)
    {
        $flashInfo->update($this->validated($request));

        Alert::success('Opération réussie', "L'info flash a été modifiée avec succès");
        return back();
    }

    public function destroy(FlashInfo $flashInfo): JsonResponse
    {
        $flashInfo->delete();

        return response()->json(['status' => 200]);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'message' => 'required|string|max:500',
            'link_text' => 'nullable|string|max:255',
            'link_url' => 'nullable|string|max:500',
            'type' => ['required', Rule::in(array_keys(FlashInfo::TYPES))],
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        $data['order'] = $data['order'] ?? 0;

        return $data;
    }
}
