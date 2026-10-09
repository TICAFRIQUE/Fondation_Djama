<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('meta_description', 300)->nullable();
            $table->longText('content')->nullable();
            $table->boolean('show_in_footer')->default(true);
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // Pages légales créées en brouillon : à rédiger puis publier depuis l'admin
        $now = now();
        DB::table('pages')->insert([
            [
                'title' => 'Mentions légales', 'slug' => 'mentions-legales',
                'order' => 1, 'is_active' => false, 'show_in_footer' => true, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'title' => 'Politique de confidentialité', 'slug' => 'politique-de-confidentialite',
                'order' => 2, 'is_active' => false, 'show_in_footer' => true, 'created_at' => $now, 'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
