<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Agir;
use App\Models\Apropos;
use App\Models\Galerie;
use App\Models\Impact;
use App\Models\News;
use App\Models\Page;
use App\Models\Programme;
use App\Models\Projet;
use App\Models\Realisation;
use App\Models\Slider;
use App\Models\Testimonial;
use App\Support\SiteContext;
use Illuminate\Http\Request;

class indexController extends Controller
{
    // INDEX - Page d'accueil
    public function index(SiteContext $site)
    {
        $data = [
            'sliders' => Slider::where('is_active', 1)->orderBy('order')->get(),
            'impacts' => Impact::where('is_active', 1)->orderBy('order')->get(),
            'apropos' => Apropos::with('items')->first(),
            'programmes' => Programme::where('is_active', true)->orderBy('order')->get(),
            'news' => $this->newsQuery()->take(3)->get(),
            'realisations' => Realisation::orderBy('order')->take(6)->get(),
            'projets' => Projet::orderBy('order')->get(),
            'galerie' => Galerie::where('type', 'image')
                ->orderByDesc('is_featured')->orderBy('position')->latest()
                ->take(4)->get(),
            'temoignages' => Testimonial::where('is_active', true)->orderBy('order')->get(),
            'agirs' => Agir::where('is_active', 1)->orderBy('order')->get(),
        ];

        // Une section n'est affichée que si elle est activée et qu'elle a du contenu à montrer
        $hasContent = [
            'impact' => $data['impacts']->isNotEmpty(),
            'apropos' => $data['apropos'] !== null,
            'programmes' => $data['programmes']->isNotEmpty(),
            'actualites' => $data['news']->isNotEmpty(),
            'realisations' => $data['realisations']->isNotEmpty(),
            'projets' => $data['projets']->isNotEmpty(),
            'galerie' => $data['galerie']->isNotEmpty(),
            'temoignages' => $data['temoignages']->isNotEmpty(),
            'agir' => $data['agirs']->isNotEmpty(),
        ];

        $data['sections'] = $site->sections()
            ->filter(fn ($section) => $section->is_active && ($hasContent[$section->key] ?? true));

        return view('frontend.index', $data);
    }

    // À PROPOS - Page de présentation
    public function apropos()
    {
        return view('frontend.apropos', [
            'apropos' => Apropos::with('items')->first(),
            'impacts' => Impact::where('is_active', 1)->orderBy('order')->get(),
            'programmes' => Programme::where('is_active', true)->orderBy('order')->get(),
            'temoignages' => Testimonial::where('is_active', true)->orderBy('order')->get(),
        ]);
    }

    // GALERIE - Page galerie
    public function galerie()
    {
        $images = Galerie::orderBy('position')->latest()->get();
        return view('frontend.galerie', compact('images'));
    }

    // AGIR - Page « Faire un don » (moyens de don + formulaire d'engagement)
    public function don(Request $request)
    {
        $types = config('site.engagement_types');
        $type = $request->string('type')->toString();

        return view('frontend.don', [
            'agirs' => Agir::where('is_active', 1)->orderBy('order')->get(),
            'types' => $types,
            'selectedType' => array_key_exists($type, $types) ? $type : 'donation',
        ]);
    }

    // CONTACT
    public function contact()
    {
        return view('frontend.contact');
    }

    // PAGES LIBRES (mentions légales, confidentialité...) gérées depuis l'admin
    public function page($slug)
    {
        $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('frontend.page', compact('page'));
    }

    // ─── AFFICHAGES DES DÉTAILS (COMMON-SHOW) ───

    public function showRealisation($slug)
    {
        $data = Realisation::where('slug', $slug)->firstOrFail();
        $related = Realisation::whereKeyNot($data->id)->orderBy('order')->take(3)->get();

        return view('frontend.common-show', ['data' => $data, 'type' => 'realisation', 'related' => $related]);
    }

    public function showNews($slug)
    {
        $data = News::where('slug', $slug)->firstOrFail();
        $related = $this->newsQuery()->whereKeyNot($data->id)->take(3)->get();

        return view('frontend.common-show', ['data' => $data, 'type' => 'news', 'related' => $related]);
    }

    public function showProjet($slug)
    {
        $data = Projet::where('slug', $slug)->firstOrFail();
        $related = Projet::whereKeyNot($data->id)->orderBy('order')->take(3)->get();

        return view('frontend.common-show', ['data' => $data, 'type' => 'projet', 'related' => $related]);
    }

    public function showProgramme($slug)
    {
        $data = Programme::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $related = Programme::where('is_active', true)->whereKeyNot($data->id)->orderBy('order')->take(3)->get();

        return view('frontend.common-show', ['data' => $data, 'type' => 'programme', 'related' => $related]);
    }

    // ─── AFFICHAGES DES LISTES (LIST-ALL) ───

    public function allNews()
    {
        $items = $this->newsQuery()->paginate(12);
        return view('frontend.list-all', ['items' => $items, 'type' => 'news', 'title' => 'Toutes nos actualités']);
    }

    public function allProjets()
    {
        $items = Projet::orderBy('order')->latest()->paginate(12);
        return view('frontend.list-all', ['items' => $items, 'type' => 'projet', 'title' => 'Tous nos projets']);
    }

    public function allRealisations()
    {
        $items = Realisation::orderBy('order')->paginate(12);
        return view('frontend.list-all', ['items' => $items, 'type' => 'realisation', 'title' => 'Toutes nos actions']);
    }

    // Articles du plus récent au plus ancien (date de publication, sinon date de création)
    private function newsQuery()
    {
        return News::orderByDesc('published_at')->orderByDesc('created_at');
    }
}
