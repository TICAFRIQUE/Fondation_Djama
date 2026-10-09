<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Page;
use App\Models\Programme;
use App\Models\Projet;
use App\Models\Realisation;

class SeoController extends Controller
{
    // Plan du site pour les moteurs de recherche, toujours à jour avec les contenus de l'admin
    public function sitemap()
    {
        $urls = [
            ['loc' => route('index'), 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => route('apropos'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('news.all'), 'priority' => '0.8', 'changefreq' => 'weekly'],
            ['loc' => route('realisations.all'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('projets.all'), 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => route('galerie'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => route('don'), 'priority' => '0.9', 'changefreq' => 'monthly'],
            ['loc' => route('contact'), 'priority' => '0.6', 'changefreq' => 'yearly'],
        ];

        $collections = [
            'programmes.show' => Programme::where('is_active', true)->get(['slug', 'updated_at']),
            'news.show' => News::get(['slug', 'updated_at']),
            'realisations.show' => Realisation::get(['slug', 'updated_at']),
            'projets.show' => Projet::get(['slug', 'updated_at']),
            'page.show' => Page::where('is_active', true)->get(['slug', 'updated_at']),
        ];

        foreach ($collections as $routeName => $items) {
            foreach ($items as $item) {
                $urls[] = [
                    'loc' => route($routeName, $item->slug),
                    'lastmod' => $item->updated_at?->toAtomString(),
                    'priority' => $routeName === 'page.show' ? '0.3' : '0.7',
                ];
            }
        }

        return response()
            ->view('frontend.sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots()
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Allow: /',
            '',
            'Sitemap: ' . route('sitemap'),
        ];

        return response(implode("\n", $lines) . "\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
