<?php

namespace App\Support;

use App\Models\FlashInfo;
use App\Models\Page;
use App\Models\Parametre;
use App\Models\PaymentMethod;
use App\Models\SiteSection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;

/**
 * Données communes à toutes les pages publiques (paramètres, infos flash, pied de page...).
 * Chargées une seule fois par requête et partagées aux vues sous le nom $site.
 */
class SiteContext
{
    protected array $loaded = [];

    protected function once(string $key, callable $resolver, mixed $default = null): mixed
    {
        if (! array_key_exists($key, $this->loaded)) {
            try {
                $this->loaded[$key] = $resolver();
            } catch (QueryException $e) {
                // Table pas encore migrée : le site reste affichable avec les valeurs par défaut
                $this->loaded[$key] = $default;
            }
        }

        return $this->loaded[$key];
    }

    public function settings(): ?Parametre
    {
        return $this->once('settings', fn () => View::shared('data_parametre') ?? Parametre::with('media')->first());
    }

    public function setting(string $key, mixed $default = null): mixed
    {
        $value = $this->settings()?->{$key};

        return filled($value) ? $value : $default;
    }

    public function name(): string
    {
        return $this->setting('nom_projet', config('site.name'));
    }

    public function slogan(): string
    {
        return $this->setting('slogan', config('site.slogan'));
    }

    public function description(): string
    {
        return $this->setting('description_projet', config('site.description'));
    }

    public function metaTitle(): string
    {
        return $this->setting('meta_title', $this->name() . ' — ' . $this->slogan());
    }

    public function metaDescription(): string
    {
        return $this->setting('meta_description', $this->description());
    }

    public function logo(string $collection = 'logo_header'): string
    {
        $url = $this->settings()?->getFirstMediaUrl($collection);

        return $url ?: asset('site/img/logo.webp');
    }

    // Image de partage par défaut (réseaux sociaux)
    public function shareImage(): string
    {
        $url = $this->settings()?->getFirstMediaUrl('cover');

        return $url ? url($url) : asset('site/img/og-default.jpg');
    }

    public function phones(): array
    {
        return array_values(array_filter([
            $this->setting('contact_principal'),
            $this->setting('contact_secondaire'),
        ]));
    }

    public function emails(): array
    {
        return array_values(array_filter([
            $this->setting('email_principal'),
            $this->setting('email_secondaire'),
        ]));
    }

    public function address(): ?string
    {
        return $this->setting('siege_social') ?? $this->setting('localisation');
    }

    public function whatsappUrl(): ?string
    {
        $number = preg_replace('/\D+/', '', (string) $this->setting('contact_whatsapp'));

        return $number ? 'https://wa.me/' . $number : null;
    }

    // Réseaux sociaux renseignés dans les paramètres
    public function socials(): array
    {
        $networks = [
            'lien_facebook' => ['Facebook', 'bi-facebook'],
            'lien_instagram' => ['Instagram', 'bi-instagram'],
            'lien_twitter' => ['X (Twitter)', 'bi-twitter-x'],
            'lien_linkedin' => ['LinkedIn', 'bi-linkedin'],
            'lien_tiktok' => ['TikTok', 'bi-tiktok'],
            'lien_youtube' => ['YouTube', 'bi-youtube'],
        ];

        $socials = [];
        foreach ($networks as $field => [$label, $icon]) {
            if ($url = $this->setting($field)) {
                $socials[] = ['label' => $label, 'icon' => $icon, 'url' => $url];
            }
        }

        return $socials;
    }

    public function sections(): Collection
    {
        return $this->once('sections', fn () => SiteSection::resolved(), collect());
    }

    public function section(string $key): SiteSection
    {
        return $this->sections()->get($key)
            ?? new SiteSection(['key' => $key, 'is_active' => true] + config("site.sections.$key", []));
    }

    public function flashInfos(): Collection
    {
        return $this->once('flash', fn () => FlashInfo::current()->get(), collect());
    }

    public function footerPages(): Collection
    {
        return $this->once('pages', fn () => Page::where('is_active', true)
            ->where('show_in_footer', true)
            ->orderBy('order')
            ->get(['title', 'slug']), collect());
    }

    public function paymentMethods(): Collection
    {
        return $this->once('payments', fn () => PaymentMethod::where('is_active', true)
            ->orderBy('order')
            ->get(), collect());
    }
}
