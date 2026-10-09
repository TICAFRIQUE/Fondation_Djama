<?php

namespace Database\Seeders;

use App\Models\Agir;
use App\Models\Apropos;
use App\Models\FlashInfo;
use App\Models\Galerie;
use App\Models\Impact;
use App\Models\News;
use App\Models\Programme;
use App\Models\Projet;
use App\Models\Realisation;
use App\Models\Slider;
use Illuminate\Support\Str;

/**
 * Contenus d'EXEMPLE pour les rubriques encore vides :  php artisan db:seed --class=SiteExampleSeeder
 *
 * Sert à voir le site complet (sliders, chiffres, programmes, actualités, actions, projets,
 * façons d'aider, infos flash). Les textes sont rédigés à partir des photos du site et
 * doivent être remplacés par les vrais contenus de la fondation depuis l'admin.
 *
 * Chaque rubrique n'est remplie que si elle est vide. À ne pas lancer sur un site en ligne
 * dont une rubrique est volontairement vide : elle recevrait ces exemples.
 */
class SiteExampleSeeder extends SiteContentSeeder
{
    public function run(): void
    {
        if (Slider::doesntExist()) {
            Slider::create([
                'badge' => "🎓 Depuis 2013 en Côte d'Ivoire",
                'title' => "Offrir l'éducation,",
                'highlight' => "construire l'avenir",
                'description' => "Nous accompagnons les jeunes filles et les familles les plus vulnérables vers l'école, l'autonomie et la santé.",
                'btn1_text' => 'Faire un don', 'btn1_link' => 'agir',
                'btn2_text' => 'Découvrir nos actions', 'btn2_link' => 'realisations',
                'image' => $this->image('13.jpeg', 'sliders'), 'order' => 1, 'is_active' => true,
                'stats' => [['value' => '+500', 'label' => 'élèves soutenues'], ['value' => '12', 'label' => "ans d'action"], ['value' => '4', 'label' => 'programmes']],
            ]);
            Slider::create([
                'badge' => '📚 Rentrée solidaire',
                'title' => 'Un kit scolaire,',
                'highlight' => "une année d'école",
                'description' => 'À chaque rentrée, nous remettons cahiers, stylos et fournitures aux élèves des écoles que nous accompagnons.',
                'btn1_text' => 'Soutenir la rentrée', 'btn1_link' => '/faire-un-don',
                'btn2_text' => 'Nos projets', 'btn2_link' => 'projets',
                'image' => $this->image('16.jpeg', 'sliders'), 'order' => 2, 'is_active' => true,
            ]);
            Slider::create([
                'badge' => '🤝 Ensemble',
                'title' => 'Aux côtés des écoles',
                'highlight' => 'et des enseignants',
                'description' => "Élèves, parents, enseignants et autorités locales : c'est ensemble que nous faisons avancer l'éducation.",
                'btn1_text' => 'Devenir partenaire', 'btn1_link' => '/faire-un-don?type=partner',
                'btn2_text' => 'Qui sommes-nous', 'btn2_link' => '/a-propos',
                'image' => $this->image('elites.jpeg', 'sliders'), 'order' => 3, 'is_active' => true,
            ]);
        }

        if (Impact::doesntExist()) {
            $impacts = [['+500', 'élèves soutenues'], ['12', "ans d'action sur le terrain"], ['4', "domaines d'intervention"]];
            foreach ($impacts as $order => [$value, $label]) {
                Impact::create(['value' => $value, 'label' => $label, 'order' => $order + 1, 'is_active' => true]);
            }
        }

        // Les trois encadrés de la section « À propos »
        $apropos = Apropos::first();
        if ($apropos && $apropos->items()->doesntExist()) {
            $apropos->items()->createMany([
                ['title' => 'Notre mission', 'description' => "Permettre à chaque enfant, et d'abord à chaque fille, d'aller à l'école et d'y rester.", 'icon' => '🎯', 'color' => '#E3F2FD', 'order' => 1],
                ['title' => 'Notre vision', 'description' => 'Des communautés autonomes où chaque jeune peut construire son avenir.', 'icon' => '🌍', 'color' => '#FFF3E0', 'order' => 2],
                ['title' => 'Nos valeurs', 'description' => 'Solidarité, transparence et engagement de proximité.', 'icon' => '🤝', 'color' => '#E8F5E9', 'order' => 3],
            ]);
        }

        if (Programme::doesntExist()) {
            $programmes = [
                ['Éducation', "Kits scolaires, soutien aux élèves et sensibilisation des familles à la scolarisation des jeunes filles.\n\nNous intervenons dans les écoles des villages pour que chaque enfant commence l'année avec le nécessaire.", '#E3F2FD', '#1F4E79', 'Nos_programmes.jpg'],
                ['Économie', "Micro-projets et accompagnement des femmes qui lancent une activité génératrice de revenus.\n\nL'autonomie économique des mères est l'une des meilleures garanties de la scolarité des enfants.", '#FFF3E0', '#B8540A', '15.jpeg'],
                ['Santé', "Sensibilisation à l'hygiène et à la santé auprès des élèves et de leurs familles.\n\nUn enfant en bonne santé est un enfant qui peut apprendre.", '#E8F5E9', '#2E7D32', '20.jpeg'],
                ['Social', "Cours d'alphabétisation pour les adultes et soutien aux familles en difficulté.\n\nParce que l'éducation concerne toute la communauté.", '#FDECEA', '#C62828', '14.jpeg'],
            ];
            foreach ($programmes as $order => [$title, $description, $bg, $text, $image]) {
                Programme::create(['title' => $title, 'slug' => Str::slug($title), 'description' => $description, 'color_bg' => $bg, 'color_text' => $text, 'image' => $this->image($image, 'programmes'), 'order' => $order + 1, 'is_active' => true]);
            }
        }

        if (News::doesntExist()) {
            $articles = [
                ['Remise de kits scolaires aux élèves', 'Éducation', '4.jpeg', 6, "La fondation a remis des kits scolaires aux élèves : cahiers, stylos et fournitures pour bien commencer l'année.\n\nUn moment de joie partagé avec les enfants, leurs parents et leurs enseignants."],
                ['Une cérémonie en présence des autorités locales', 'Éducation', '10.jpeg', 20, "Parents, élèves, enseignants et autorités locales se sont retrouvés pour la cérémonie de remise.\n\nLeur présence témoigne de l'engagement de toute la communauté pour l'école."],
                ['Merci à nos donateurs et partenaires', 'Social', '2.jpeg', 41, "C'est grâce à votre générosité que chaque kit a pu être remis en main propre.\n\nChaque don, quel que soit son montant, se transforme en fournitures pour un élève."],
                ["À l'école de Nagafou 2", 'Éducation', '12.jpeg', 63, "La fondation poursuit son action dans les écoles de la région de Bondoukou.\n\nProchaine étape : préparer la rentrée avec les équipes enseignantes."],
            ];
            foreach ($articles as [$title, $category, $image, $daysAgo, $content]) {
                News::create(['title' => $title, 'slug' => Str::slug($title), 'content' => $content, 'category' => $category, 'image' => $this->image($image, 'news'), 'published_at' => now()->subDays($daysAgo), 'reading_time' => 2]);
            }
        }

        if (Realisation::doesntExist()) {
            $year = now()->year - 1;
            $realisations = [
                ['Distribution de kits scolaires', '18.jpeg', 'Remise de fournitures aux élèves, en présence des familles.'],
                ['Soutien aux élèves du primaire', '6.jpeg', "Accompagnement des classes de primaire tout au long de l'année."],
                ['Des élèves équipés pour la rentrée', '1.jpeg', 'Chaque enfant repart avec le nécessaire pour étudier.'],
                ['Une communauté mobilisée', '11.jpeg', 'Parents, enseignants et autorités réunis autour des élèves.'],
                ['Appui aux équipes enseignantes', 'formateurs.jpeg', 'Un travail mené main dans la main avec les enseignants.'],
                ['Encouragement des meilleurs élèves', '3.jpeg', 'Valoriser le travail et donner envie de poursuivre.'],
            ];
            foreach ($realisations as $order => [$title, $image, $description]) {
                Realisation::create([
                    'title' => $title,
                    'description' => $description . "\n\nUne action menée avec les communautés locales, au bénéfice direct des élèves et de leurs familles.",
                    'image' => $this->image($image, 'realisations'),
                    'date_start' => "$year-09-01",
                    'date_end' => "$year-10-31",
                    'order' => $order + 1,
                ]);
            }
        }

        if (Projet::doesntExist()) {
            $projets = [
                ['Des kits scolaires pour la prochaine rentrée', 'en_cours', 60, '19.jpeg'],
                ['Équiper les salles de classe', 'financement', 25, 'elites.jpeg'],
                ['Bourses pour les jeunes filles', 'en_cours', 40, '8.jpeg'],
                ["Cours d'alphabétisation pour les parents", 'bientot', 0, '17.jpeg'],
            ];
            foreach ($projets as $order => [$title, $status, $progress, $image]) {
                Projet::create([
                    'title' => $title,
                    'slug' => Str::slug($title),
                    'description' => "Ce projet répond à un besoin exprimé par les familles et les équipes enseignantes.\n\nVotre soutien permet d'en accélérer la réalisation.",
                    'image' => $this->image($image, 'projets'),
                    'status' => $status,
                    'progress' => $progress,
                    'date_start' => now()->startOfYear(),
                    'date_end' => now()->endOfYear(),
                    'order' => $order + 1,
                ]);
            }
        }

        if (Agir::doesntExist()) {
            $agirs = [
                ['Faire un don', 'Un don, même modeste, finance des fournitures pour un élève.', '💝', '#FFF3E0', 'donation'],
                ['Parrainer une élève', "Accompagnez la scolarité d'une jeune fille tout au long de l'année.", '🎓', '#E3F2FD', 'sponsorship'],
                ['Devenir bénévole', 'Offrez de votre temps et vos compétences à nos équipes de terrain.', '🤝', '#E8F5E9', 'volunteer'],
                ['Devenir partenaire', 'Entreprises et institutions : construisons ensemble des projets durables.', '🏢', '#F3E5F5', 'partner'],
            ];
            foreach ($agirs as $order => [$title, $description, $icon, $color, $type]) {
                Agir::create(['title' => $title, 'description' => $description, 'icon' => $icon, 'color' => $color, 'type' => $type, 'order' => $order + 1, 'is_active' => true]);
            }
        }

        // Quelques photos de plus pour la page Galerie (les 4 premières viennent de SiteContentSeeder)
        if (Galerie::count() <= 4) {
            $photos = [['16.jpeg', 'Les élèves réunis pour la remise'], ['10.jpeg', 'Cérémonie avec les familles'], ['12.jpeg', "L'école de Nagafou 2"], ['elites.jpeg', 'En salle de classe']];
            foreach ($photos as $position => [$file, $title]) {
                Galerie::create(['title' => $title, 'path' => $this->image($file, 'galerie'), 'type' => 'image', 'position' => $position + 5, 'is_featured' => false]);
            }
        }

        if (FlashInfo::doesntExist()) {
            FlashInfo::create(['message' => 'Soutenez la scolarisation des jeunes filles : chaque don compte.', 'link_text' => 'Faire un don', 'link_url' => '/faire-un-don', 'type' => 'important', 'order' => 1, 'is_active' => true]);
            FlashInfo::create(['message' => 'Bienvenue sur le nouveau site de la Fondation Djama Éducation.', 'type' => 'info', 'order' => 2, 'is_active' => true]);
        }
    }
}
