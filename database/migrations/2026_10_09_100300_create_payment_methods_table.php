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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('label'); // RIB — Banque Atlantique
            $table->string('icon')->nullable(); // classe bootstrap-icons
            $table->string('value'); // numéro affiché
            $table->string('note')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Reprise des numéros de paiement qui étaient écrits en dur dans la page d'accueil, pour que le
        // site en ligne ne les perde pas à la mise à jour. Les données vivent dans SiteContentSeeder.
        $now = now();
        foreach (SiteContentSeeder::MOYENS_DE_DON as $order => [$label, $icon, $value]) {
            DB::table('payment_methods')->insert([
                'label' => $label, 'icon' => $icon, 'value' => $value,
                'order' => $order + 1, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
