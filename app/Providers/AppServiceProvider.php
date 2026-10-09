<?php

namespace App\Providers;

use Throwable;
use App\Models\Parametre;
use App\Support\SiteContext;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Données communes du site public, chargées une seule fois par requête
        $this->app->scoped(SiteContext::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //



        Schema::defaultStringLength(191);


        $this->app->booted(function () {
            try {
                if (Schema::hasTable('permissions') && Schema::hasTable('roles')) {
                    $permissions = Permission::pluck('id')->toArray();

                    $developpeurRole = Role::where('name', 'developpeur')->first();
                    $superadminRole = Role::where('name', 'superadmin')->first();

                    if ($developpeurRole) {
                        $developpeurRole->permissions()->sync($permissions);
                    }

                    if ($superadminRole) {
                        $superadminRole->permissions()->sync($permissions);
                    }
                }
            } catch (\Exception $e) {
                // Optionnel : log de l'erreur si besoin
                return back()->withErrors('Une erreur est survenue lors de la synchronisation des permissions.', 'Message d\'erreur:' . $e->getMessage());
            }
        });



        //recuperer les parametres
        if (Schema::hasTable('parametres')) {
            $data_parametre = Parametre::with('media')->first();
        }

        view()->share([
            'data_parametre' => $data_parametre ?? null,
        ]);

        // Le site est en français : dates « 9 octobre 2026 » plutôt que « October 9, 2026 »
        Carbon::setLocale('fr');

        // $site est disponible dans toutes les vues du site public
        View::composer('frontend.*', fn ($view) => $view->with('site', app(SiteContext::class)));

        // Ses données valent pour une requête : la suivante les relit en base
        Event::listen(RequestHandled::class, fn () => $this->app->forgetInstance(SiteContext::class));
    }
}
