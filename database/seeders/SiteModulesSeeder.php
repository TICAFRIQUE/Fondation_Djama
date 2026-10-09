<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;

/**
 * Modules et permissions des écrans d'admin du site.
 * Sans la permission « voir-<module> », l'écran n'apparaît pas dans le menu.
 * Ne crée que ce qui manque : peut être relancé sans risque.
 */
class SiteModulesSeeder extends Seeder
{
    // Écrans ajoutés avec la gestion dynamique du site
    public const NEW_MODULES = ['sections', 'infos-flash', 'temoignages', 'moyens-don', 'pages'];

    // Écrans historiques du menu
    public const MENU_MODULES = [
        'tableau de bord', 'apropos', 'galerie', 'impacts', 'messages', 'actualites', 'programmes',
        'projets', 'realisations', 'sliders', 'agirs', 'parametre',
    ];

    public function run(): void
    {
        $now = now();

        foreach ([...self::NEW_MODULES, ...self::MENU_MODULES] as $name) {
            $moduleId = DB::table('modules')->where('name', $name)->value('id')
                ?? DB::table('modules')->insertGetId([
                    'name' => $name, 'slug' => Str::slug($name), 'created_at' => $now, 'updated_at' => $now,
                ]);

            // mêmes noms que ceux générés par Paramètres > Modules
            foreach (['creer', 'voir', 'modifier', 'supprimer'] as $action) {
                $permission = ['name' => "$action-$name", 'guard_name' => 'web'];

                if (! DB::table('permissions')->where($permission)->exists()) {
                    DB::table('permissions')->insert($permission + [
                        'module_id' => $moduleId, 'created_at' => $now, 'updated_at' => $now,
                    ]);
                }
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
