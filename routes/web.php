<?php

use App\Http\Controllers\AgirController;
use App\Http\Controllers\AproposController;
use App\Http\Controllers\backend\AdminController;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\FlashInfoController;
use App\Http\Controllers\backend\ModuleController;
use App\Http\Controllers\backend\PageController;
use App\Http\Controllers\backend\ParametreController;
use App\Http\Controllers\backend\PaymentMethodController;
use App\Http\Controllers\backend\PermissionController;
use App\Http\Controllers\backend\ProgrammeController;
use App\Http\Controllers\backend\RoleController;
use App\Http\Controllers\backend\SiteSectionController;
use App\Http\Controllers\backend\TestimonialController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\EngagementController;
use App\Http\Controllers\frontend\indexController;
use App\Http\Controllers\frontend\SeoController;
use App\Http\Controllers\GalerieController;
use App\Http\Controllers\ImpactController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\RealisationController;
use App\Http\Controllers\SliderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Publiques (Accessibles par tous les visiteurs)
|--------------------------------------------------------------------------
*/

Route::controller(indexController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/a-propos', 'apropos')->name('apropos');
    Route::get('/galerie', 'galerie')->name('galerie');
    Route::get('/faire-un-don', 'don')->name('don');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/page/{slug}', 'page')->name('page.show');

    // Détails (Common-show)
    Route::get('/programmes/{slug}', 'showProgramme')->name('programmes.show');
    Route::get('/realisations/{slug}', 'showRealisation')->name('realisations.show');
    Route::get('/projets/{slug}', 'showProjet')->name('projets.show');
    Route::get('/actualites/{slug}', 'showNews')->name('news.show');

    // Listes complètes (List-all)
    Route::get('/realisations', 'allRealisations')->name('realisations.all');
    Route::get('/projets', 'allProjets')->name('projets.all');
    Route::get('/actualites', 'allNews')->name('news.all');
});

// Référencement : plan du site et consignes pour les moteurs de recherche
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

// Anciennes adresses : redirections permanentes pour ne pas perdre les liens existants
Route::permanentRedirect('/projets-list', '/projets');
Route::get('/news/{slug}', fn (string $slug) => redirect()->route('news.show', $slug, 301));

// Routes d'actions (Agir & Soutenir) - Redirects vers la page « Faire un don »
Route::name('agir.')->group(function () {
    Route::get('/donation', fn() => redirect()->route('don'))->name('donation');
    Route::get('/sponsorship', fn() => redirect()->route('don', ['type' => 'sponsorship']))->name('sponsorship');
    Route::get('/volunteer', fn() => redirect()->route('don', ['type' => 'volunteer']))->name('volunteer');
});

// Formulaires publics, limités à 6 envois par minute et par visiteur contre le spam
Route::middleware('throttle:6,1')->group(function () {
    // Route d'envoi du formulaire de contact (Doit être hors du middleware admin)
    Route::post('/contact/store', [ContactMessageController::class, 'store'])->name('contact.store');

    // Route d'envoi du formulaire d'engagement
    Route::post('/engagement/store', [EngagementController::class, 'store'])->name('engagement.store');
});


/*
|--------------------------------------------------------------------------
| Routes Authentification Admin
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->controller(AdminController::class)->group(function () {
    Route::get('/login', 'login')->name('admin.login'); 
    Route::post('/login', 'login'); 
    Route::post('/logout', 'logout')->name('admin.logout')->middleware('admin');
});


/*
|--------------------------------------------------------------------------
| Routes Administration (Protégées par le middleware 'admin')
|--------------------------------------------------------------------------
*/

Route::middleware(['admin'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

    // Paramètres application
    Route::prefix('parametre')->controller(ParametreController::class)->group(function () {
        Route::get('', 'index')->name('parametre.index');
        Route::post('store', 'store')->name('parametre.store');
        Route::get('maintenance-up', 'maintenanceUp')->name('parametre.maintenance-up');
        Route::get('maintenance-down', 'maintenanceDown')->name('parametre.maintenance-down');
        Route::get('optimize-clear', 'optimizeClear')->name('parametre.optimize-clear');
        Route::get('download-backup/{file}', 'downloadBackup')->name('setting.download-backup');
    });

    // Gestion des Administrateurs
    Route::prefix('register')->controller(AdminController::class)->group(function () {
        Route::get('', 'index')->name('admin-register.index');
        Route::post('store', 'store')->name('admin-register.store');
        Route::post('update/{id}', 'update')->name('admin-register.update');
        Route::delete('delete/{id}', 'delete')->name('admin-register.delete');
        Route::get('profil/{id}', 'profil')->name('admin-register.profil');
        Route::post('change-password', 'changePassword')->name('admin-register.new-password');
    });

    // Roles & Permissions
    Route::prefix('role')->controller(RoleController::class)->group(function () {
        Route::get('', 'index')->name('role.index');
        Route::post('store', 'store')->name('role.store');
        Route::post('update/{id}', 'update')->name('role.update');
        Route::delete('delete/{id}', 'delete')->name('role.delete');
    });

    Route::prefix('permission')->controller(PermissionController::class)->group(function () {
        Route::get('', 'index')->name('permission.index');
        Route::get('create', 'create')->name('permission.create');
        Route::post('store', 'store')->name('permission.store');
        Route::get('edit/{id}', 'edit')->name('permission.edit');
        Route::put('update/{id}', 'update')->name('permission.update');
        Route::delete('delete/{id}', 'delete')->name('permission.delete');
    });

    // Modules
    Route::prefix('module')->controller(ModuleController::class)->group(function () {
        Route::get('', 'index')->name('module.index');
        Route::post('store', 'store')->name('module.store');
        Route::post('update/{id}', 'update')->name('module.update');
        Route::delete('delete/{id}', 'delete')->name('module.delete');
    });

    // Galerie
    Route::prefix('galerie')->controller(GalerieController::class)->group(function () {
        Route::get('/', 'index')->name('galerie.index');
        Route::post('/store', 'store')->name('galerie.store');
        Route::put('/update/{galerie}', 'update')->name('galerie.update');
        Route::delete('/delete/{galerie}', 'destroy')->name('galerie.destroy');
    });

    // Apropos
    Route::prefix('apropos')->controller(AproposController::class)->group(function () {
        Route::get('/', 'index')->name('apropos.index');
        Route::post('/store', 'store')->name('apropos.store');
        Route::put('/update/{id}', 'update')->name('apropos.update');
        Route::delete('/delete/{id}', 'destroy')->name('apropos.destroy');
        // Routes pour les items
        Route::post('/{apropos_id}/items/store', 'storeItem')->name('apropos.items.store');
        Route::put('/{apropos_id}/items/{item_id}', 'updateItem')->name('apropos.items.update');
        Route::delete('/{apropos_id}/items/{item_id}', 'destroyItem')->name('apropos.items.destroy');
    });

    // Impacts
    Route::prefix('impacts')->controller(ImpactController::class)->group(function () {
        Route::get('/', 'index')->name('impacts.index');
        Route::post('/store', 'store')->name('impacts.store');
        Route::put('/update/{impact}', 'update')->name('impacts.update');
        Route::delete('/delete/{impact}', 'destroy')->name('impacts.destroy');
    });

    // Sliders
    Route::prefix('sliders')->controller(SliderController::class)->group(function () {
        Route::get('/', 'index')->name('sliders.index');
        Route::post('/store', 'store')->name('sliders.store');
        Route::put('/update/{slider}', 'update')->name('sliders.update');
        Route::delete('/delete/{slider}', 'destroy')->name('sliders.destroy');
    });

    // Réalisations
    Route::prefix('realisations')->controller(RealisationController::class)->group(function () {
        Route::get('/', 'index')->name('realisations.index');
        Route::post('/store', 'store')->name('realisations.store');
        Route::put('/update/{realisation}', 'update')->name('realisations.update');
        Route::delete('/delete/{realisation}', 'destroy')->name('realisations.destroy');
        Route::post('/reorder', 'reorder')->name('realisations.reorder');
    });

    // Projets
    Route::prefix('projets')->controller(ProjetController::class)->group(function () {
        Route::get('/', 'index')->name('projets.index');
        Route::post('/store', 'store')->name('projets.store');
        Route::put('/update/{projet}', 'update')->name('projets.update');
        Route::delete('/delete/{projet}', 'destroy')->name('projets.destroy');
    });

    // Gestion des Messages reçus (Admin seulement)
    Route::prefix('messages')->controller(ContactMessageController::class)->group(function () {
        Route::get('/', 'index')->name('messages.index');
        Route::delete('/delete/{contactMessage}', 'destroy')->name('messages.destroy');
    });

    // News (Actualités)
    Route::prefix('news')->controller(NewsController::class)->group(function () {
        Route::get('/', 'index')->name('news.index');
        Route::post('/store', 'store')->name('news.store');
        Route::put('/update/{news}', 'update')->name('news.update');
        Route::delete('/delete/{news}', 'destroy')->name('news.destroy');
    });

    // Programmes
    Route::prefix('programmes')->controller(ProgrammeController::class)->group(function () {
        Route::get('/', 'index')->name('programmes.index');
        Route::post('/store', 'store')->name('programmes.store');
        Route::put('/update/{programme}', 'update')->name('programmes.update');
        Route::delete('/delete/{programme}', 'destroy')->name('programmes.destroy');
    });

    // Agirs (Actions)
    Route::prefix('agirs')->controller(AgirController::class)->group(function () {
        Route::get('/', 'index')->name('agirs.index');
        Route::post('/store', 'store')->name('agirs.store');
        Route::put('/update/{agir}', 'update')->name('agirs.update');
        Route::delete('/delete/{agir}', 'destroy')->name('agirs.destroy');
    });

    // Engagements (Engagements reçus)
    Route::prefix('engagements')->controller(EngagementController::class)->group(function () {
        Route::get('/', 'index')->name('engagements.index');
        Route::delete('/delete/{engagement}', 'destroy')->name('engagements.destroy');
    });

    // Sections de la page d'accueil (textes, ordre, activation)
    Route::prefix('sections')->controller(SiteSectionController::class)->group(function () {
        Route::get('/', 'index')->name('sections.index');
        Route::put('/update/{section}', 'update')->name('sections.update');
    });

    // Infos flash (bandeau d'annonces)
    Route::prefix('flash-infos')->controller(FlashInfoController::class)->group(function () {
        Route::get('/', 'index')->name('flash-infos.index');
        Route::post('/store', 'store')->name('flash-infos.store');
        Route::put('/update/{flashInfo}', 'update')->name('flash-infos.update');
        Route::delete('/delete/{flashInfo}', 'destroy')->name('flash-infos.destroy');
    });

    // Témoignages
    Route::prefix('temoignages')->controller(TestimonialController::class)->group(function () {
        Route::get('/', 'index')->name('temoignages.index');
        Route::post('/store', 'store')->name('temoignages.store');
        Route::put('/update/{temoignage}', 'update')->name('temoignages.update');
        Route::delete('/delete/{temoignage}', 'destroy')->name('temoignages.destroy');
    });

    // Moyens de don (RIB, Wave...)
    Route::prefix('moyens-don')->controller(PaymentMethodController::class)->group(function () {
        Route::get('/', 'index')->name('moyens-don.index');
        Route::post('/store', 'store')->name('moyens-don.store');
        Route::put('/update/{moyen}', 'update')->name('moyens-don.update');
        Route::delete('/delete/{moyen}', 'destroy')->name('moyens-don.destroy');
    });

    // Pages libres (mentions légales, confidentialité...)
    Route::prefix('pages')->controller(PageController::class)->group(function () {
        Route::get('/', 'index')->name('pages.index');
        Route::post('/store', 'store')->name('pages.store');
        Route::put('/update/{page}', 'update')->name('pages.update');
        Route::delete('/delete/{page}', 'destroy')->name('pages.destroy');
    });

});

/*
|--------------------------------------------------------------------------
| Fallback (Page 404)
|--------------------------------------------------------------------------
*/

// Vrai code 404 (et non une page « introuvable » servie en 200, pénalisée par les moteurs de recherche)
Route::fallback(fn () => abort(404));