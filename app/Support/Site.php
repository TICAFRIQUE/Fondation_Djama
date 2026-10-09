<?php

namespace App\Support;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

/**
 * Petits utilitaires d'affichage du site public.
 */
class Site
{
    // Titre de section : « Des vies *transformées* » => le texte entre étoiles est mis en couleur
    public static function title(?string $text): HtmlString
    {
        $safe = e($text ?? '');

        return new HtmlString(preg_replace('/\*(.+?)\*/u', '<span>$1</span>', $safe));
    }

    // Même titre sans les étoiles (balises title, alt, meta...)
    public static function plain(?string $text): string
    {
        return str_replace('*', '', $text ?? '');
    }

    // Lien saisi en admin : URL complète, chemin (/galerie) ou ancre de l'accueil (agir)
    public static function link(?string $value): string
    {
        $value = trim($value ?? '');

        if ($value === '' || $value === '#') {
            return route('index');
        }
        if (preg_match('#^(https?://|mailto:|tel:|/)#i', $value)) {
            return $value;
        }

        return route('index') . '#' . ltrim($value, '#');
    }

    // Slug lisible et unique pour les URL publiques (« mon-titre », puis « mon-titre-2 »...)
    public static function uniqueSlug(string $modelClass, string $title, mixed $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'contenu';
        $slug = $base;
        $suffix = 2;

        while ($modelClass::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = $base . '-' . $suffix++;
        }

        return $slug;
    }

    // URL publique d'un fichier envoyé depuis l'admin, sinon l'image de repli
    public static function image(?string $path, ?string $fallback = null): ?string
    {
        return $path ? asset('storage/' . $path) : $fallback;
    }

    // Résumé en texte brut pour les cartes et les balises meta
    public static function excerpt(?string $text, int $limit = 160): string
    {
        $plain = html_entity_decode(strip_tags($text ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return Str::limit(trim(preg_replace('/\s+/u', ' ', $plain)), $limit);
    }

    // Contenu long : le HTML saisi en admin est conservé, le texte simple garde ses retours à la ligne
    public static function rich(?string $text): HtmlString
    {
        $text = $text ?? '';

        if ($text !== strip_tags($text)) {
            return new HtmlString(preg_replace('#<script\b[^>]*>.*?</script>#is', '', $text));
        }

        return new HtmlString(nl2br(e($text)));
    }
}
