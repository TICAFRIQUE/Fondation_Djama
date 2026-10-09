<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Engagement;
use App\Models\FlashInfo;
use App\Models\News;
use App\Models\Projet;
use App\Models\Realisation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //index for dashboard
    public function index(Request $request)
    {
        // Chiffres clés du site, avec le lien vers l'écran de gestion correspondant
        $stats = [
            ['label' => 'Messages reçus', 'value' => ContactMessage::count(), 'icon' => 'ri-mail-line', 'route' => 'messages.index'],
            ['label' => 'Engagements reçus', 'value' => Engagement::count(), 'icon' => 'ri-hand-heart-line', 'route' => 'engagements.index'],
            ['label' => 'Infos flash en ligne', 'value' => FlashInfo::current()->count(), 'icon' => 'ri-megaphone-line', 'route' => 'flash-infos.index'],
            ['label' => 'Actualités', 'value' => News::count(), 'icon' => 'ri-newspaper-line', 'route' => 'news.index'],
            ['label' => 'Réalisations', 'value' => Realisation::count(), 'icon' => 'ri-award-line', 'route' => 'realisations.index'],
            ['label' => 'Projets', 'value' => Projet::count(), 'icon' => 'ri-folder-chart-line', 'route' => 'projets.index'],
        ];

        $lastMessages = ContactMessage::latest()->take(5)->get();

        return view('backend.pages.index', compact('stats', 'lastMessages'));
    }
}
