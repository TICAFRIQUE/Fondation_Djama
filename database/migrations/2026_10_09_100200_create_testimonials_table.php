<?php

use Database\Seeders\SiteContentSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role')->nullable();
            $table->text('content');
            $table->string('photo')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Reprise des témoignages qui étaient écrits en dur dans la page d'accueil, pour que le site
        // en ligne ne les perde pas à la mise à jour. Les données vivent dans SiteContentSeeder.
        $now = now();
        foreach (SiteContentSeeder::TEMOIGNAGES as $order => [$name, $role, $content]) {
            DB::table('testimonials')->insert([
                'name' => $name, 'role' => $role, 'content' => $content,
                'order' => $order + 1, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
