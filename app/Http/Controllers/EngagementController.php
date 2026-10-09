<?php

namespace App\Http\Controllers;

use App\Models\Engagement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EngagementController extends Controller
{
    public function index()
    {
        $engagements = Engagement::latest()->get();

        return view('backend.pages.engagements.index', compact('engagements'));
    }

    // STORE ENGAGEMENT (formulaire de la page « Faire un don »)
    public function store(Request $request)
    {
        $success = 'Merci pour votre engagement ! Nous vous contacterons très bientôt.';

        // Champ piège invisible : seul un robot le remplit, on l'ignore sans l'en informer
        if ($request->filled('website')) {
            return $this->thanks($request, $success);
        }

        $data = $request->validateWithBag('engagement', [
            'type' => ['required', Rule::in(array_keys(config('site.engagement_types')))],
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'amount' => 'nullable|numeric|min:0|max:99999999',
            'message' => 'nullable|string|max:5000',
        ], [
            'required' => 'Ce champ est obligatoire.',
            'email' => 'Veuillez saisir une adresse e-mail valide.',
            'numeric' => 'Veuillez saisir un montant en chiffres.',
            'max' => 'Cette valeur est trop longue.',
            'in' => 'Veuillez choisir une option de la liste.',
        ]);

        Engagement::create($data);

        return $this->thanks($request, $success);
    }

    public function destroy(Engagement $engagement)
    {
        $engagement->delete();
        return back();
    }

    private function thanks(Request $request, string $message)
    {
        // Si c'est une requête AJAX, retourner JSON
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $message
            ]);
        }

        return redirect()->to(url()->previous() . '#engagement-form')->with('success', $message);
    }
}
