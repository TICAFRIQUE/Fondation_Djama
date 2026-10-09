<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parametres', function (Blueprint $table) {
            $table->string('slogan')->nullable();
            $table->longText('lien_youtube')->nullable();
            // SEO
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 300)->nullable();
            $table->string('meta_keywords', 500)->nullable();
        });

        Schema::table('galeries', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false); // affiché sur l'accueil
        });
    }

    public function down(): void
    {
        Schema::table('parametres', function (Blueprint $table) {
            $table->dropColumn(['slogan', 'lien_youtube', 'meta_title', 'meta_description', 'meta_keywords']);
        });

        Schema::table('galeries', function (Blueprint $table) {
            $table->dropColumn('is_featured');
        });
    }
};
