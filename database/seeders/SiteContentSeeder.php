<?php

namespace Database\Seeders;

use App\Models\Apropos;
use App\Models\Galerie;
use App\Models\PaymentMethod;
use App\Models\SiteSection;
use App\Models\Testimonial;
use App\Support\ImageOptimizer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Contenus par défaut du site public :  php artisan db:seed --class=SiteContentSeeder
 *
 * Ce sont les contenus qui étaient écrits en dur dans les pages (coordonnées, à propos,
 * galerie de l'accueil, témoignages, moyens de don). Ils vivent maintenant en base et se
 * modifient depuis l'admin.
 *
 * Chaque rubrique n'est remplie que si elle est vide : rien de ce qui existe n'est modifié,
 * le seeder peut donc être relancé sans risque, y compris en production.
 */
class SiteContentSeeder extends Seeder
{
    // [nom affiché, qualité / lieu, témoignage]
    public const TEMOIGNAGES = [
        ['Aminata F.', 'Élève, 15 ans — Bondoukou', "Grâce à la Fondation Djama, j'ai pu retourner à l'école. Aujourd'hui je suis en troisième et je rêve de devenir médecin."],
        ['Mariam K.', 'Bénéficiaire micro-projet — Abengourou', "Le micro-crédit m'a permis d'ouvrir mon commerce de couture. Aujourd'hui j'emploie deux autres femmes du village."],
        ['Yah D.', "Cours d'alphabétisation — Gontougo", 'Nous avons appris à lire à 45 ans. Mon mari et moi pouvons maintenant lire les ordonnances médicales de nos enfants.'],
    ];

    // [libellé, icône, numéro]
    public const MOYENS_DE_DON = [
        ['RIB — Banque Atlantique', 'bi-bank2', '0183 6564 0003'],
        ['Numéro Wave', 'bi-phone', '+225 07 04 43 98 56'],
    ];

    // [photo, légende, à la une sur l'accueil]
    private const GALERIE = [
        ['13.jpeg', 'Élèves en classe — Bondoukou', true],
        ['18.jpeg', 'Remise de fournitures 2024', true],
        ['sous_prefecture_de_kouassia_niaguini.jpeg', 'Sous-préfecture de Kouassia Niaguini', true],
        ['formateurs.jpeg', 'Les enseignants', true],
    ];

    public function run(): void
    {
        // DB::table : le modèle Parametre génère son identifiant avec une requête propre à MySQL
        if (DB::table('parametres')->doesntExist()) {
            DB::table('parametres')->insert([
                'nom_projet' => 'Fondation Djama Éducation',
                'slogan' => "Offrir l'éducation, construire l'avenir",
                'description_projet' => "Fondée en 2013, la Fondation Djama œuvre pour l'éducation, l'autonomie et la santé des populations vulnérables de Côte d'Ivoire.",
                'siege_social' => 'Abidjan Cocody',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (Apropos::doesntExist()) {
            Apropos::create([
                'title' => 'Une fondation au service des plus vulnérables',
                'description' => "Depuis 2013, la Fondation Djama Éducation œuvre pour la scolarisation des jeunes filles, l'autonomie économique et le développement des communautés les plus vulnérables de l'Est de la Côte d'Ivoire.",
                'image' => $this->image('Une_fondation_au_service.jpg', 'apropos'),
                'stat_1_value' => '+500',
                'stat_1_label' => 'élèves soutenues',
                'stat_2_value' => '12',
                'stat_2_label' => "ans d'action",
            ]);
        }

        if (Galerie::doesntExist()) {
            foreach (self::GALERIE as $position => [$file, $title, $featured]) {
                Galerie::create(['title' => $title, 'path' => $this->image($file, 'galerie'), 'type' => 'image', 'position' => $position + 1, 'is_featured' => $featured]);
            }
        }

        if (Testimonial::doesntExist()) {
            foreach (self::TEMOIGNAGES as $order => [$name, $role, $content]) {
                Testimonial::create(['name' => $name, 'role' => $role, 'content' => $content, 'order' => $order + 1, 'is_active' => true]);
            }
        }

        if (PaymentMethod::doesntExist()) {
            foreach (self::MOYENS_DE_DON as $order => [$label, $icon, $value]) {
                PaymentMethod::create(['label' => $label, 'icon' => $icon, 'value' => $value, 'order' => $order + 1, 'is_active' => true]);
            }
        }

        // Titres et textes des sections de l'accueil (config/site.php), modifiables dans Admin > Sections
        SiteSection::syncDefaults();
    }

    // Copie une photo d'origine du site (public/assets/images) vers le disque public, allégée, et retourne son chemin.
    // Chaque rubrique reçoit sa propre copie : supprimer ou remplacer un contenu en admin efface son image,
    // ce qui ne doit pas casser un autre contenu utilisant la même photo.
    protected function image(string $file, string $folder): string
    {
        $path = 'defaut/' . $folder . '/' . $file;
        $disk = Storage::disk('public');

        if (! $disk->exists($path)) {
            $disk->put($path, File::get(public_path('assets/images/' . $file)));
            ImageOptimizer::optimize($disk->path($path), 1600);
        }

        return $path;
    }
}
