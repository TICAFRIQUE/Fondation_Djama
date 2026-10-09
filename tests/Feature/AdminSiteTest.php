<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Engagement;
use App\Models\FlashInfo;
use App\Models\Galerie;
use App\Models\News;
use App\Models\Page;
use App\Models\PaymentMethod;
use App\Models\Programme;
use App\Models\Projet;
use App\Models\SiteSection;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminSiteTest extends TestCase
{
    use RefreshDatabase;

    // Le modèle User génère son identifiant avec une requête propre à MySQL : on le fixe ici
    private function admin(): User
    {
        return User::withoutEvents(fn () => User::forceCreate([
            'id' => 1,
            'username' => 'Admin',
            'email' => 'admin@fondation.test',
            'password' => 'secret',
            'role' => 'superadmin',
        ]));
    }

    // Administrateur avec le rôle Spatie « superadmin » et toutes les permissions : il voit tout le menu
    private function adminWithPermissions(): User
    {
        $admin = $this->admin();
        $role = Role::create(['name' => 'superadmin', 'guard_name' => 'web']);
        $role->syncPermissions(Permission::all());
        $admin->assignRole($role);

        return $admin;
    }

    public function test_login_page_uses_the_site_identity_and_reports_bad_credentials(): void
    {
        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('Fondation Djama Éducation')
            ->assertSee('adm-login', false)
            ->assertSee('name="email"', false)
            ->assertSee('adm/css/admin.css', false)
            ->assertDontSee('build/js/app.js', false)
            ->assertDontSee('assets/img/favicon', false); // icônes introuvables de l'ancien gabarit

        $this->admin();
        $this->from('/admin/login')->post('/admin/login', ['email' => 'admin@fondation.test', 'password' => 'faux'])
            ->assertRedirect('/admin/login')
            ->assertSessionHas('error')
            ->assertSessionHasInput('email', 'admin@fondation.test');

        $this->post('/admin/login', ['email' => 'admin@fondation.test', 'password' => 'secret'])
            ->assertRedirect(route('dashboard.index'));
        $this->assertAuthenticated();
    }

    public function test_every_admin_screen_loads_the_menu_script_once_and_never_a_second_jquery(): void
    {
        $this->actingAs($this->adminWithPermissions());

        $screens = ['', '/sections', '/flash-infos', '/sliders', '/impacts', '/apropos', '/temoignages', '/programmes', '/news',
            '/realisations', '/projets', '/galerie', '/pages', '/agirs', '/moyens-don', '/engagements', '/messages',
            '/parametre', '/register', '/module', '/role', '/permission'];

        foreach ($screens as $screen) {
            $html = $this->get('/admin' . $screen)->assertOk()->getContent();

            // sans ce script, le bouton du menu ne fonctionnait pas sur Actualités, Projets, Réalisations, Engagements, Messages
            $this->assertSame(1, substr_count($html, 'adm/js/admin.js'), "admin.js doit être chargé une fois sur /admin$screen");
            $this->assertStringNotContainsString('build/js/app.js', $html, "/admin$screen");
            $this->assertStringNotContainsString('jquery-3.6.0', $html, "/admin$screen"); // écrasait jQuery et cassait select2
            $this->assertStringContainsString('id="admSidebar"', $html, "/admin$screen");
            $this->assertStringStartsWith('<!doctype html>', ltrim($html), "Rien ne doit précéder le doctype sur /admin$screen");
        }
    }

    public function test_sidebar_lists_the_screens_the_user_may_see_and_marks_the_current_one(): void
    {
        $this->actingAs($this->adminWithPermissions());

        $this->get('/admin/projets')
            ->assertOk()
            ->assertSeeInOrder(['Tableau de bord', "Page d'accueil", 'Infos flash', 'Contenus', 'Actualités', 'Projets', 'Dons &amp; contacts', 'Messages', 'Configuration'], false)
            ->assertSee('class="adm-nav-link active" href="' . route('projets.index') . '"', false);

        // sans permission, les rubriques de contenu disparaissent du menu
        $limited = User::withoutEvents(fn () => User::forceCreate(['id' => 2, 'username' => 'Limité', 'email' => 'l@fondation.test', 'password' => 'secret', 'role' => 'administrateur']));
        $this->actingAs($limited)->get('/admin/projets')
            ->assertOk()
            ->assertDontSee('Infos flash')
            ->assertDontSee('Configuration');
    }

    public function test_news_can_be_edited_from_the_admin_list(): void
    {
        $this->actingAs($this->admin());
        $news = News::create(['title' => 'Rentrée', 'slug' => 'rentree', 'content' => 'Texte', 'category' => 'Éducation', 'published_at' => '2026-09-01']);

        // l'écran ne proposait que « Supprimer » : la modification n'existait pas
        $this->get('/admin/news')
            ->assertOk()
            ->assertSee(route('news.update', $news->id), false)
            ->assertSee('id="myModalEdit' . $news->id . '"', false)
            ->assertSee('value="2026-09-01"', false);

        $this->put("/admin/news/update/{$news->id}", ['title' => 'Rentrée solidaire', 'content' => 'Nouveau texte', 'category' => 'Social', 'published_at' => '2026-09-15', 'reading_time' => 4])
            ->assertRedirect()->assertSessionHasNoErrors();

        $news->refresh();
        $this->assertSame('Rentrée solidaire', $news->title);
        $this->assertSame('Social', $news->category);
        $this->assertSame(4, $news->reading_time);
    }

    public function test_messages_and_engagements_screens_show_full_details(): void
    {
        $this->actingAs($this->admin());
        $long = str_repeat('Bonjour à toute l\'équipe. ', 10) . 'Fin du message.';
        ContactMessage::create(['name' => 'Awa', 'email' => 'awa@example.com', 'subject' => 'Autre demande', 'message' => $long]);
        Engagement::create(['type' => 'volunteer', 'name' => 'Koffi', 'email' => 'koffi@example.com']);

        // le message complet est lisible (la liste le coupait à 80 caractères sans autre accès)
        $this->get('/admin/messages')->assertOk()->assertSee('Fin du message.')->assertSee('Répondre par email');

        // le script du bouton « Voir » n'était jamais chargé, et le type restait en anglais
        $this->get('/admin/engagements')
            ->assertOk()
            ->assertSee('Bénévole')
            ->assertSee("document.querySelectorAll('.view-btn')", false);
    }

    public function test_guests_cannot_reach_the_new_admin_screens(): void
    {
        foreach (['/admin/sections', '/admin/flash-infos', '/admin/temoignages', '/admin/moyens-don', '/admin/pages'] as $url) {
            $this->get($url)->assertRedirect(route('admin.login'));
        }

        $this->post('/admin/flash-infos/store', ['message' => 'x', 'type' => 'info', 'is_active' => 1])
            ->assertRedirect(route('admin.login'));
        $this->assertSame(0, FlashInfo::count());
    }

    public function test_admin_screens_render(): void
    {
        $this->actingAs($this->admin());
        FlashInfo::create(['message' => 'Annonce', 'type' => 'info', 'is_active' => true]);

        $screens = [
            '/admin' => 'Infos flash en ligne',
            '/admin/sections' => 'ordre, textes et affichage des sections',
            '/admin/flash-infos' => 'Annonce',
            '/admin/temoignages' => 'Aminata F.',
            '/admin/moyens-don' => 'Numéro Wave',
            '/admin/pages' => 'Mentions légales',
            '/admin/galerie' => 'Ajouter un média',
            '/admin/programmes' => 'Liste des programmes',
            '/admin/parametre' => 'Référencement (SEO)',
        ];

        foreach ($screens as $url => $text) {
            $this->get($url)->assertOk()->assertSee($text)->assertSee('noindex, nofollow', false);
        }
    }

    public function test_migration_creates_the_permissions_used_by_the_menu(): void
    {
        foreach (['voir-sections', 'voir-infos-flash', 'voir-temoignages', 'voir-moyens-don', 'voir-pages', 'voir-sliders'] as $name) {
            $this->assertTrue(Permission::where('name', $name)->exists(), "Permission manquante : $name");
        }
    }

    public function test_admin_manages_flash_infos(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/flash-infos/store', [
            'message' => 'Collecte de fournitures',
            'type' => 'urgent',
            'link_url' => 'agir',
            'starts_at' => '2026-10-01T08:00',
            'ends_at' => '2026-12-31T18:00',
            'is_active' => 1,
        ])->assertRedirect()->assertSessionHasNoErrors();

        $flash = FlashInfo::firstOrFail();
        $this->assertSame('urgent', $flash->type);
        $this->assertSame('2026-12-31 18:00', $flash->ends_at->format('Y-m-d H:i'));

        $this->put("/admin/flash-infos/update/{$flash->id}", ['message' => 'Collecte prolongée', 'type' => 'info', 'is_active' => 0])
            ->assertRedirect()->assertSessionHasNoErrors();
        $this->assertSame('Collecte prolongée', $flash->fresh()->message);
        $this->assertFalse($flash->fresh()->is_active);

        // la fin ne peut pas précéder le début
        $this->post('/admin/flash-infos/store', ['message' => 'x', 'type' => 'info', 'is_active' => 1, 'starts_at' => '2026-10-02', 'ends_at' => '2026-10-01'])
            ->assertSessionHasErrors('ends_at');

        $this->deleteJson("/admin/flash-infos/delete/{$flash->id}")->assertOk()->assertJson(['status' => 200]);
        $this->assertSame(0, FlashInfo::count());
    }

    public function test_admin_edits_a_home_section(): void
    {
        $this->actingAs($this->admin());
        $this->get('/admin/sections')->assertOk(); // enregistre les sections par défaut

        $section = SiteSection::where('key', 'agir')->firstOrFail();

        $this->put("/admin/sections/update/{$section->id}", [
            'eyebrow' => 'Soutenir',
            'title' => 'Aidez-nous *maintenant*',
            'order' => 1,
            'is_active' => 0,
        ])->assertRedirect()->assertSessionHasNoErrors();

        $section->refresh();
        $this->assertSame('Aidez-nous *maintenant*', $section->title);
        $this->assertFalse($section->is_active);
    }

    public function test_admin_manages_testimonials_with_an_optimised_photo(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        $this->post('/admin/temoignages/store', [
            'name' => 'Koffi B.',
            'role' => 'Parent — Bondoukou',
            'content' => 'Ma fille a repris le chemin de l\'école.',
            'photo' => UploadedFile::fake()->image('portrait.jpg', 1600, 1200),
            'is_active' => 1,
        ])->assertRedirect()->assertSessionHasNoErrors();

        $testimonial = Testimonial::where('name', 'Koffi B.')->firstOrFail();
        Storage::disk('public')->assertExists($testimonial->photo);
        $this->assertSame(400, getimagesize(Storage::disk('public')->path($testimonial->photo))[0], 'La photo doit être réduite à 400 px de large');
        $this->assertSame('KB', $testimonial->initials);

        $this->get('/')->assertSee('Koffi B.');

        $this->deleteJson("/admin/temoignages/delete/{$testimonial->id}")->assertOk();
        Storage::disk('public')->assertMissing($testimonial->photo);
    }

    public function test_admin_manages_payment_methods(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/moyens-don/store', ['label' => 'Orange Money', 'icon' => 'bi-phone', 'value' => '07 11 22 33 44', 'is_active' => 1])
            ->assertRedirect()->assertSessionHasNoErrors();
        $this->post('/admin/moyens-don/store', ['label' => 'Injection', 'icon' => '"><script>', 'value' => '1', 'is_active' => 1])
            ->assertSessionHasErrors('icon');

        $method = PaymentMethod::where('label', 'Orange Money')->firstOrFail();
        $this->assertSame('0711223344', $method->copy_value);

        $this->get('/faire-un-don')->assertSee('07 11 22 33 44');
    }

    public function test_admin_publishes_a_page_with_a_stable_unique_slug(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/pages/store', ['title' => 'Mentions légales', 'content' => 'Texte', 'show_in_footer' => 1, 'is_active' => 1])
            ->assertRedirect()->assertSessionHasNoErrors();

        // le slug « mentions-legales » existe déjà (brouillon de la migration)
        $page = Page::where('slug', 'mentions-legales-2')->firstOrFail();
        $this->get('/page/mentions-legales-2')->assertOk()->assertSee('Texte');

        $this->put("/admin/pages/update/{$page->id}", ['title' => 'Nouveau titre', 'content' => 'Texte', 'show_in_footer' => 0, 'is_active' => 1])
            ->assertRedirect()->assertSessionHasNoErrors();
        $this->assertSame('mentions-legales-2', $page->fresh()->slug, "L'adresse d'une page ne doit pas changer quand on la renomme");
    }

    public function test_admin_uploads_gallery_media_and_features_it_on_home(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        $this->post('/admin/galerie/store', [
            'title' => 'Remise de prix',
            'media' => UploadedFile::fake()->image('photo.jpg', 3000, 2000),
            'position' => 1,
            'is_featured' => 1,
        ])->assertRedirect()->assertSessionHasNoErrors();

        $media = Galerie::firstOrFail();
        $this->assertTrue($media->is_featured);
        $this->assertSame('image', $media->type);
        $this->assertSame(1920, getimagesize(Storage::disk('public')->path($media->path))[0], 'Les photos sont ramenées à 1920 px de large');

        $this->get('/')->assertSee('Remise de prix')->assertSee('storage/' . $media->path, false);

        // la modification sans nouveau fichier conserve le média
        $this->put("/admin/galerie/update/{$media->id}", ['title' => 'Nouveau titre', 'is_featured' => 0])->assertRedirect()->assertSessionHasNoErrors();
        $this->assertSame($media->path, $media->fresh()->path);
        $this->assertFalse($media->fresh()->is_featured);

        // la modification et la suppression ne touchaient aucun média (paramètre de route mal nommé)
        $this->deleteJson("/admin/galerie/delete/{$media->id}")->assertOk();
        $this->assertSame(0, Galerie::count());
        Storage::disk('public')->assertMissing($media->path);
    }

    public function test_project_update_is_saved_and_keeps_its_url(): void
    {
        $this->actingAs($this->admin());
        $projet = Projet::create(['title' => 'Cantine', 'slug' => 'cantine', 'status' => 'bientot', 'progress' => 0]);

        $this->put("/admin/projets/update/{$projet->id}", ['title' => 'Cantine scolaire', 'status' => 'en_cours', 'progress' => 60])
            ->assertRedirect()->assertSessionHasNoErrors();

        $projet->refresh();
        $this->assertSame('Cantine scolaire', $projet->title); // n'était jamais enregistré auparavant
        $this->assertSame(60, $projet->progress);
        $this->assertSame('cantine', $projet->slug);
    }

    public function test_news_and_programmes_get_readable_slugs_that_survive_edits(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/news/store', ['title' => 'Rentrée 2026', 'category' => 'Éducation'])->assertRedirect()->assertSessionHasNoErrors();
        $this->post('/admin/news/store', ['title' => 'Rentrée 2026', 'category' => 'Éducation'])->assertRedirect()->assertSessionHasNoErrors();
        $this->assertSame(['rentree-2026', 'rentree-2026-2'], News::orderBy('id')->pluck('slug')->all());

        $news = News::first();
        $this->put("/admin/news/update/{$news->id}", ['title' => 'Rentrée solidaire', 'category' => 'Éducation'])->assertRedirect()->assertSessionHasNoErrors();
        $this->assertSame('rentree-2026', $news->fresh()->slug);

        $this->post('/admin/programmes/store', ['title' => 'Santé', 'description' => 'Soins', 'is_active' => 0])->assertRedirect()->assertSessionHasNoErrors();
        $programme = Programme::firstOrFail();
        $this->assertSame('sante', $programme->slug);
        $this->assertFalse((bool) $programme->is_active);
        $this->deleteJson("/admin/programmes/delete/{$programme->id}")->assertOk()->assertJson(['status' => 200]);
    }
}
