<?php

namespace App\Http\Controllers;

use App\Support\ImageOptimizer;
use App\Models\Apropos;
use App\Models\AproposItem;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class AproposController extends Controller
{
    public function index(): View
    {
        $data_apropos = Apropos::with('items')->get();
        return view('backend.pages.apropos.index', compact('data_apropos'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stat_1_value' => 'nullable|string',
            'stat_1_label' => 'nullable|string',
            'stat_2_value' => 'nullable|string',
            'stat_2_label' => 'nullable|string',
        ]);

        $data = $request->only([
            'title',
            'description',
            'stat_1_value',
            'stat_1_label',
            'stat_2_value',
            'stat_2_label',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = ImageOptimizer::store($request->file('image'), 'apropos');
        }

        Apropos::create($data);

        Alert::success('Opération réussie', 'La section À propos a été créée avec succès');
        return back();
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stat_1_value' => 'nullable|string',
            'stat_1_label' => 'nullable|string',
            'stat_2_value' => 'nullable|string',
            'stat_2_label' => 'nullable|string',
        ]);

        $apropos = Apropos::findOrFail($id);

        $data = $request->only([
            'title',
            'description',
            'stat_1_value',
            'stat_1_label',
            'stat_2_value',
            'stat_2_label',
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($apropos->image && Storage::exists('public/' . $apropos->image)) {
                Storage::delete('public/' . $apropos->image);
            }
            $data['image'] = ImageOptimizer::store($request->file('image'), 'apropos');
        }

        $apropos->update($data);

        Alert::success('Opération réussie', 'La section À propos a été mise à jour avec succès');
        return back();
    }

    public function destroy($id): JsonResponse
    {
        $apropos = Apropos::findOrFail($id);

        // Supprimer l'image si elle existe
        if ($apropos->image && Storage::exists('public/' . $apropos->image)) {
            Storage::delete('public/' . $apropos->image);
        }

        // Supprimer tous les items liés
        $apropos->items()->forceDelete();

        // Supprimer le record apropos
        $apropos->forceDelete();

        return response()->json([
            'status' => 200,
        ]);
    }

    // Gestion des items
    public function storeItem(Request $request, $apropos_id): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $apropos = Apropos::findOrFail($apropos_id);

        $apropos->items()->create([
            'title' => $request->title,
            'description' => $request->description,
            'icon' => $request->icon,
            'color' => $request->color,
            'order' => $request->order ?? 0,
        ]);

        Alert::success('Opération réussie', 'L\'item a été ajouté avec succès');
        return back();
    }

    public function updateItem(Request $request, $apropos_id, $item_id): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $item = AproposItem::where('apropos_id', $apropos_id)->findOrFail($item_id);

        $item->update([
            'title' => $request->title,
            'description' => $request->description,
            'icon' => $request->icon,
            'color' => $request->color,
            'order' => $request->order ?? 0,
        ]);

        Alert::success('Opération réussie', 'L\'item a été mis à jour avec succès');
        return back();
    }

    public function destroyItem($apropos_id, $item_id): JsonResponse
    {
        $item = AproposItem::where('apropos_id', $apropos_id)->findOrFail($item_id);
        $item->forceDelete();

        return response()->json([
            'status' => 200,
        ]);
    }
}