<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(UsersTableSeeder::class);
        $this->call(ModulesTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(AgirsPermissionsSeeder::class);
        $this->call(ModelHasRolesTableSeeder::class);

        // Écrans d'admin du site (permissions), contenus par défaut du site public,
        // puis contenus d'exemple pour les rubriques encore vides
        $this->call(SiteModulesSeeder::class);
        $this->call(SiteContentSeeder::class);
        $this->call(SiteExampleSeeder::class);
    }
}
