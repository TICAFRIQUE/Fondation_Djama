<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PaymentMethodController extends Controller
{
    // Moyens de don : RIB, Wave, Orange Money... affichés dans la fenêtre et la page « Faire un don »
    public function index()
    {
        $moyens = PaymentMethod::orderBy('order')->get();
        return view('backend.pages.moyens-don.index', compact('moyens'));
    }

    public function store(Request $request)
    {
        PaymentMethod::create($this->validated($request));

        Alert::success('Opération réussie', 'Le moyen de don a été créé avec succès');
        return back();
    }

    public function update(Request $request, PaymentMethod $moyen)
    {
        $moyen->update($this->validated($request));

        Alert::success('Opération réussie', 'Le moyen de don a été modifié avec succès');
        return back();
    }

    public function destroy(PaymentMethod $moyen): JsonResponse
    {
        $moyen->delete();

        return response()->json(['status' => 200]);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'label' => 'required|string|max:255',
            'icon' => ['nullable', 'string', 'max:50', 'regex:/^bi-[a-z0-9-]+$/'],
            'value' => 'required|string|max:255',
            'note' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        $data['order'] = $data['order'] ?? 0;

        return $data;
    }
}
