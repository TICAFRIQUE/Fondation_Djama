<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{

    // STORE MESSAGE (formulaire site)
    public function store(Request $request)
    {
        $success = 'Merci, votre message a bien été envoyé. Notre équipe vous répondra rapidement.';

        // Champ piège invisible : seul un robot le remplit, on l'ignore sans l'en informer
        if ($request->filled('website')) {
            return redirect()->to(url()->previous() . '#contact-form')->with('success', $success);
        }

        $data = $request->validateWithBag('contact', [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ], [
            'required' => 'Ce champ est obligatoire.',
            'email' => 'Veuillez saisir une adresse e-mail valide.',
            'max' => 'Ce champ est trop long.',
        ]);

        ContactMessage::create($data);

        return redirect()->to(url()->previous() . '#contact-form')->with('success', $success);
    }

    // BACKOFFICE LISTE
    public function index()
    {
        $messages = ContactMessage::latest()->get();

        return view('backend.pages.messages.index', compact('messages'));
    }

    // DELETE
    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return back()->with('success', 'Message supprimé');
    }
}
