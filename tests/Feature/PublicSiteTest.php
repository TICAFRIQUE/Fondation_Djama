<?php

namespace Tests\Feature;

use App\Models\Agir;
use App\Models\Apropos;
use App\Models\ContactMessage;
use App\Models\Engagement;
use App\Models\FlashInfo;
use App\Models\Galerie;
use App\Models\Impact;
use App\Models\News;
use App\Models\Page;
use App\Models\PaymentMethod;
use App\Models\Programme;
use App\Models\Projet;
use App\Models\Realisation;
use App\Models\SiteSection;
use App\Models\Slider;
use App\Models\Testimonial;
use Database\Seeders\SiteContentSeeder;
use Database\Seeders\SiteExampleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    // Jeu de contenus minimal : un élément dans chaque rubrique gérée en admin
    private function seedContent(): void
    {
        Slider::create(['title' => 'Offrir une chance', 'highlight' => 'à chaque fille', 'btn1_text' => 'Agir', 'btn1_link' => 'agir', 'is_active' => true]);
        Slider::create(['title' => 'Deuxième diapositive', 'is_active' => true, 'order' => 2]);
        Impact::create(['value' => '+500', 'label' => 'élèves soutenues', 'is_active' => true]);
        Programme::create(['title' => 'Éducation', 'slug' => 'education', 'description' => 'Scolarisation des jeunes filles.', 'is_active' => true]);
        News::create(['title' => 'Remise de kits scolaires', 'slug' => 'remise-de-kits', 'content' => "Première ligne.\nDeuxième ligne.", 'category' => 'Éducation', 'published_at' => '2026-09-01']);
        Realisation::create(['title' => 'Construction de salles', 'description' => 'Trois salles de classe.', 'image' => 'realisations/x.jpg', 'date_start' => '2024-01-10', 'date_end' => '2025-03-01']);
        Projet::create(['title' => 'Cantine scolaire', 'slug' => 'cantine-scolaire', 'description' => 'Un repas par jour.', 'status' => 'en_cours', 'progress' => 40]);
        Agir::create(['title' => 'Faire un don', 'description' => 'Soutenez nos actions.', 'icon' => '💰', 'type' => 'donation', 'is_active' => true]);
        Agir::create(['title' => 'Devenir bénévole', 'description' => 'Donnez de votre temps.', 'icon' => '🤝', 'type' => 'volunteer', 'is_active' => true, 'order' => 2]);
        Apropos::create(['title' => 'Une fondation engagée', 'description' => 'Notre histoire.', 'stat_1_value' => '+500', 'stat_1_label' => 'élèves']);
        Galerie::create(['title' => 'Photo de terrain', 'path' => 'galerie/photo.jpg', 'type' => 'image', 'is_featured' => true]);
    }

    public function test_home_page_renders_with_an_empty_database(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Fondation Djama Éducation')
            ->assertSee('<link rel="canonical" href="' . url('/') . '">', false)
            ->assertSee('application/ld+json', false)
            ->assertSee('Aminata F.') // témoignages repris par la migration
            ->assertDontSee('id="flashBar"', false)
            ->assertDontSee('id="actualites"', false) // section sans contenu : masquée
            ->assertDontSee('id="agir"', false)
            ->assertDontSee('id="apropos"', false) // plus aucun contenu de repli écrit en dur dans les vues
            ->assertDontSee('id="galerie"', false);

        $this->assertSame(1, substr_count($response->getContent(), '<h1'), "La page d'accueil doit avoir un seul h1");
    }

    public function test_default_content_seeder_restores_what_was_hard_coded_and_can_be_rerun(): void
    {
        Storage::fake('public');

        $this->seed(SiteContentSeeder::class);
        $this->seed(SiteContentSeeder::class); // relancé : rien n'est dupliqué

        $this->assertSame(1, Apropos::count());
        $this->assertSame(4, Galerie::where('is_featured', true)->count());
        $this->assertSame(3, Testimonial::count());
        $this->assertSame(2, PaymentMethod::count());
        $this->assertSame(1, DB::table('parametres')->count());
        $this->assertSame(count(config('site.sections')), SiteSection::count());
        $this->assertSame(0, Slider::count(), "Les contenus d'exemple ne font pas partie des contenus par défaut");
        Storage::disk('public')->assertExists(Galerie::first()->path);

        $this->get('/')
            ->assertOk()
            ->assertSee('Une fondation au service des plus vulnérables')
            ->assertSee('Remise de fournitures 2024')
            ->assertSee('Abidjan Cocody')
            ->assertSeeInOrder(['id="apropos"', 'id="galerie"', 'id="temoignages"', 'id="contact"'], false);
    }

    public function test_example_seeder_fills_every_empty_section_without_touching_existing_content(): void
    {
        Storage::fake('public');
        Projet::create(['title' => 'Projet réel', 'slug' => 'projet-reel', 'status' => 'en_cours', 'progress' => 10]);

        $this->seed([SiteContentSeeder::class, SiteExampleSeeder::class]);
        $this->seed(SiteExampleSeeder::class);

        $this->assertSame(1, Projet::count(), 'Une rubrique déjà remplie est laissée telle quelle');
        $this->assertSame(3, Slider::count());
        $this->assertSame(4, Programme::count());
        $this->assertSame(4, News::count());
        $this->assertSame(6, Realisation::count());
        $this->assertSame(8, Galerie::count());
        $this->assertSame(3, Apropos::first()->items()->count());

        $response = $this->get('/');
        $response->assertOk()
            ->assertSee('id="flashBar"', false)
            ->assertSee('impact-compact', false)
            ->assertSeeInOrder(['id="apropos"', 'id="programmes"', 'id="actualites"', 'id="realisations"', 'id="projets"', 'id="galerie"', 'id="temoignages"', 'id="agir"', 'id="contact"'], false);
        $this->assertSame(1, substr_count($response->getContent(), '<h1'));

        foreach (['/a-propos', '/actualites', '/realisations', '/projets', '/galerie', '/faire-un-don', '/programmes/education', '/actualites/remise-de-kits-scolaires-aux-eleves'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_home_page_shows_managed_content_with_a_single_h1(): void
    {
        $this->seedContent();

        $response = $this->get('/');

        $response->assertOk()
            ->assertSeeInOrder(['id="apropos"', 'id="programmes"', 'id="actualites"', 'id="realisations"', 'id="projets"', 'id="galerie"', 'id="temoignages"', 'id="agir"', 'id="contact"'], false)
            ->assertSee('Remise de kits scolaires')
            ->assertSee('Construction de salles')
            ->assertSee('Cantine scolaire')
            ->assertSee('0183 6564 0003') // moyens de don dans la fenêtre « Faire un don »
            ->assertSee('data-copy="018365640003"', false)
            ->assertSee('href="#actualites"', false) // le menu défile vers la section affichée
            ->assertSee('href="' . route('index') . '#agir"', false); // lien de slider saisi comme ancre

        $this->assertSame(1, substr_count($response->getContent(), '<h1'));
    }

    public function test_admin_can_hide_and_reorder_home_sections(): void
    {
        $this->seedContent();
        SiteSection::syncDefaults();
        SiteSection::where('key', 'temoignages')->update(['is_active' => false]);
        SiteSection::where('key', 'contact')->update(['order' => 0, 'title' => 'Écrivez-*nous*']);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('id="temoignages"', false)
            ->assertSee('Écrivez-<span>nous</span>', false)
            ->assertSeeInOrder(['id="contact"', 'id="apropos"'], false);
    }

    public function test_menu_links_to_dedicated_page_when_section_is_hidden(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('href="' . route('news.all') . '"', false)
            ->assertSee('href="' . route('don') . '"', false);
    }

    public function test_flash_info_is_shown_only_while_active_and_in_its_period(): void
    {
        FlashInfo::create(['message' => 'Rentrée solidaire en cours', 'type' => 'important', 'link_url' => '/faire-un-don', 'is_active' => true]);
        FlashInfo::create(['message' => 'Annonce désactivée', 'type' => 'info', 'is_active' => false]);
        FlashInfo::create(['message' => 'Annonce expirée', 'type' => 'info', 'is_active' => true, 'ends_at' => now()->subDay()]);
        FlashInfo::create(['message' => 'Annonce programmée', 'type' => 'info', 'is_active' => true, 'starts_at' => now()->addDay()]);

        foreach (['/', '/actualites', '/contact'] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('id="flashBar"', false)
                ->assertSee('flash-important', false)
                ->assertSee('Rentrée solidaire en cours')
                ->assertSee('href="/faire-un-don"', false)
                ->assertDontSee('Annonce désactivée')
                ->assertDontSee('Annonce expirée')
                ->assertDontSee('Annonce programmée');
        }
    }

    public function test_inner_pages_render_with_their_own_title(): void
    {
        $this->seedContent();

        $pages = [
            '/a-propos' => 'À propos | Fondation Djama Éducation',
            '/actualites' => 'Toutes nos actualités | Fondation Djama Éducation',
            '/realisations' => 'Toutes nos actions | Fondation Djama Éducation',
            '/projets' => 'Tous nos projets | Fondation Djama Éducation',
            '/galerie' => 'Galerie photos et vidéos | Fondation Djama Éducation',
            '/faire-un-don' => 'Faire un don et soutenir la fondation | Fondation Djama Éducation',
            '/contact' => 'Contact | Fondation Djama Éducation',
        ];

        foreach ($pages as $url => $title) {
            $response = $this->get($url);

            $response->assertOk()
                ->assertSee("<title>$title</title>", false)
                ->assertSee('<link rel="canonical" href="' . url($url) . '">', false)
                ->assertSee('BreadcrumbList', false);

            $this->assertSame(1, substr_count($response->getContent(), '<h1'), "Un seul h1 attendu sur $url");
        }
    }

    public function test_detail_pages_render_for_each_content_type(): void
    {
        $this->seedContent();
        $realisation = Realisation::first();

        $this->get('/actualites/remise-de-kits')
            ->assertOk()
            ->assertSee('<title>Remise de kits scolaires | Fondation Djama Éducation</title>', false)
            ->assertSee('<meta property="og:type" content="article">', false)
            ->assertSee('NewsArticle', false)
            ->assertSee('1 septembre 2026') // date en français
            ->assertSee("Première ligne.<br />\nDeuxième ligne.", false);

        // les dates des réalisations faisaient planter cette page (format() sur une chaîne)
        $this->get('/realisations/' . $realisation->slug)->assertOk()->assertSee('janvier 2024');
        $this->assertSame('construction-de-salles', $realisation->slug);

        $this->get('/projets/cantine-scolaire')->assertOk()->assertSee('En cours')->assertSee('40 %');
        $this->get('/programmes/education')->assertOk()->assertSee('Scolarisation des jeunes filles.');
    }

    public function test_inactive_programme_and_unknown_content_return_404(): void
    {
        Programme::create(['title' => 'Masqué', 'slug' => 'masque', 'description' => 'x', 'is_active' => false]);

        $this->get('/programmes/masque')->assertNotFound();
        $this->get('/actualites/inconnu')->assertNotFound();
    }

    public function test_old_urls_redirect_permanently(): void
    {
        $this->get('/projets-list')->assertStatus(301)->assertRedirect('/projets');
        $this->get('/news/remise-de-kits')->assertStatus(301)->assertRedirect(route('news.show', 'remise-de-kits'));
        $this->get('/donation')->assertRedirect(route('don'));
    }

    public function test_free_pages_are_public_only_once_published(): void
    {
        // les deux pages légales sont créées en brouillon par la migration
        $this->get('/page/mentions-legales')->assertNotFound();
        $this->get('/')->assertDontSee('Mentions légales');

        Page::where('slug', 'mentions-legales')->update(['is_active' => true, 'content' => '<h2>Éditeur</h2><p>Fondation Djama</p><script>alert(1)</script>']);

        $this->get('/page/mentions-legales')
            ->assertOk()
            ->assertSee('<h2>Éditeur</h2>', false)
            ->assertDontSee('<script>alert(1)</script>', false);
        $this->get('/')->assertSee('Mentions légales');
    }

    public function test_unknown_url_returns_a_real_404_page(): void
    {
        $this->get('/cette-page-nexiste-pas')
            ->assertNotFound()
            ->assertSee('Cette page est introuvable')
            ->assertSee('noindex', false);
    }

    public function test_sitemap_and_robots_are_generated(): void
    {
        $this->seedContent();
        Page::where('slug', 'mentions-legales')->update(['is_active' => true]);

        $sitemap = $this->get('/sitemap.xml');
        $sitemap->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $this->assertStringStartsWith('<?xml', $sitemap->getContent());

        foreach ([route('index'), route('apropos'), route('don'), route('news.show', 'remise-de-kits'), route('projets.show', 'cantine-scolaire'), route('programmes.show', 'education'), route('page.show', 'mentions-legales')] as $url) {
            $sitemap->assertSee("<loc>$url</loc>", false);
        }
        $sitemap->assertDontSee('politique-de-confidentialite'); // brouillon

        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Disallow: /admin')
            ->assertSee('Sitemap: ' . route('sitemap'));
    }

    public function test_settings_from_admin_feed_seo_tags_and_footer(): void
    {
        DB::table('parametres')->insert([
            'nom_projet' => 'Fondation Test',
            'slogan' => 'Agir ensemble',
            'meta_title' => 'Titre SEO personnalisé',
            'meta_description' => 'Description SEO personnalisée.',
            'contact_principal' => '+225 01 02 03 04 05',
            'email_principal' => 'contact@fondation.test',
            'lien_facebook' => 'https://facebook.com/fondation',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('<title>Titre SEO personnalisé</title>', false)
            ->assertSee('<meta name="description" content="Description SEO personnalisée.">', false)
            ->assertSee('href="tel:+2250102030405"', false)
            ->assertSee('mailto:contact@fondation.test', false)
            ->assertSee('https://facebook.com/fondation', false)
            ->assertSee('« Agir ensemble »');

        $this->get('/contact')->assertSee('<title>Contact | Fondation Test</title>', false);
    }

    public function test_contact_form_saves_a_valid_message(): void
    {
        $this->from('/contact')->post('/contact/store', [
            'name' => 'Awa Koné',
            'email' => 'awa@example.com',
            'subject' => 'Autre demande',
            'message' => 'Bonjour, je souhaite vous rencontrer.',
        ])->assertRedirect('/contact#contact-form')->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', ['email' => 'awa@example.com', 'subject' => 'Autre demande']);
    }

    public function test_contact_form_rejects_invalid_input_and_ignores_bots(): void
    {
        $this->from('/contact')->post('/contact/store', ['name' => '', 'email' => 'pas-un-email', 'subject' => 'x', 'message' => ''])
            ->assertRedirect('/contact')
            ->assertSessionHasErrorsIn('contact', ['name', 'email', 'message']);

        // champ piège rempli = robot : réponse identique mais rien n'est enregistré
        $this->from('/contact')->post('/contact/store', [
            'name' => 'Bot', 'email' => 'bot@example.com', 'subject' => 'Spam', 'message' => 'Spam', 'website' => 'http://spam.test',
        ])->assertSessionHas('success');

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_engagement_form_saves_a_pledge_and_validates_its_type(): void
    {
        $this->from('/faire-un-don')->post('/engagement/store', [
            'type' => 'donation', 'name' => 'Awa Koné', 'email' => 'awa@example.com', 'amount' => 25000,
        ])->assertRedirect('/faire-un-don#engagement-form')->assertSessionHas('success');

        $this->assertDatabaseHas('engagements', ['email' => 'awa@example.com', 'type' => 'donation', 'amount' => 25000]);

        $this->from('/faire-un-don')->post('/engagement/store', ['type' => 'pirate', 'name' => 'X', 'email' => 'x@example.com'])
            ->assertSessionHasErrorsIn('engagement', ['type']);

        $this->assertSame(1, Engagement::count());
    }

    public function test_donation_page_lists_active_payment_methods_and_preselects_the_type(): void
    {
        PaymentMethod::create(['label' => 'Orange Money', 'icon' => 'bi-phone', 'value' => '07 00 00 00 00', 'is_active' => true, 'order' => 3]);
        PaymentMethod::create(['label' => 'Ancien compte', 'value' => '9999', 'is_active' => false]);

        $this->get('/faire-un-don?type=volunteer')
            ->assertOk()
            ->assertSee('Numéro Wave')
            ->assertSee('Orange Money')
            ->assertDontSee('Ancien compte')
            ->assertSee('<option value="volunteer" selected>', false);
    }
}
