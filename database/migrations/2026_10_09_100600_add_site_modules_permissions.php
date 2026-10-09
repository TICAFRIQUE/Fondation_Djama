<?php

use Database\Seeders\SiteModulesSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    // Nouveaux écrans d'admin : sans leurs permissions, ils n'apparaîtraient pas dans le menu.
    // La liste vit dans SiteModulesSeeder, relancé aussi par « php artisan db:seed ».
    public function up(): void
    {
        (new SiteModulesSeeder)->run();
    }

    public function down(): void
    {
        // Seuls les modules ajoutés par cette évolution sont retirés
        foreach (SiteModulesSeeder::NEW_MODULES as $name) {
            DB::table('permissions')->where('name', 'like', "%-$name")->where('guard_name', 'web')->delete();
            DB::table('modules')->where('name', $name)->delete();
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
