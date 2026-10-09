<?php

/*
|--------------------------------------------------------------------------
| Valeurs par défaut du site public
|--------------------------------------------------------------------------
| Utilisées tant que l'administrateur n'a rien saisi dans le back-office
| (Paramètres > Informations et Site > Sections de l'accueil).
*/

return [

    'name' => 'Fondation Djama Éducation',
    'slogan' => "Offrir l'éducation, construire l'avenir",
    'description' => "Fondée en 2013, la Fondation Djama œuvre pour l'éducation, l'autonomie et la santé des populations vulnérables de Côte d'Ivoire.",
    'locality' => 'Abidjan',
    'country' => 'CI',

    /*
    | Sections de la page d'accueil, dans leur ordre par défaut.
    | Dans les titres, *un mot entre étoiles* est mis en couleur.
    */
    'sections' => [
        'impact' => [
            'label' => "Chiffres d'impact",
            'hint' => 'Les chiffres se gèrent dans le menu « Impacts ».',
            'eyebrow' => null,
            'title' => null,
            'subtitle' => null,
        ],
        'apropos' => [
            'label' => 'À propos',
            'hint' => 'Le titre, le texte et l\'image se gèrent dans le menu « À propos ».',
            'eyebrow' => 'À propos',
            'title' => null,
            'subtitle' => null,
        ],
        'programmes' => [
            'label' => 'Programmes',
            'hint' => 'Les programmes se gèrent dans le menu « Programmes ».',
            'eyebrow' => 'Nos programmes',
            'title' => 'Des actions concrètes sur le *terrain*',
            'subtitle' => 'Chaque programme est conçu pour répondre aux besoins spécifiques des populations que nous accompagnons.',
        ],
        'actualites' => [
            'label' => 'Actualités',
            'hint' => 'Les articles se gèrent dans le menu « Actualités ».',
            'eyebrow' => 'Actualités',
            'title' => 'Les dernières nouvelles de *la fondation*',
            'subtitle' => null,
        ],
        'realisations' => [
            'label' => 'Actions (réalisations)',
            'hint' => 'Les actions se gèrent dans le menu « Réalisations ».',
            'eyebrow' => 'Nos actions',
            'title' => 'Ce que nous avons *accompli ensemble*',
            'subtitle' => 'Depuis 2013, des actions concrètes et mesurables transforment des centaines de vies.',
        ],
        'projets' => [
            'label' => 'Projets',
            'hint' => 'Les projets se gèrent dans le menu « Projets ».',
            'eyebrow' => 'Projets en cours',
            'title' => 'Initiatives *sur le terrain*',
            'subtitle' => null,
        ],
        'galerie' => [
            'label' => 'Galerie',
            'hint' => 'Cochez « À la une » sur les médias de la galerie pour choisir ceux de l\'accueil.',
            'eyebrow' => 'Notre galerie',
            'title' => 'Chaque visage est une *histoire de courage*, chaque sourire est une *victoire* sur la pauvreté.',
            'subtitle' => "Ces images témoignent de l'impact réel de la Fondation Djama Éducation sur le terrain.",
        ],
        'temoignages' => [
            'label' => 'Témoignages',
            'hint' => 'Les témoignages se gèrent dans le menu « Témoignages ».',
            'eyebrow' => 'Témoignages',
            'title' => 'Des vies *transformées*',
            'subtitle' => null,
        ],
        'agir' => [
            'label' => 'Agir & Soutenir',
            'hint' => 'Les cartes se gèrent dans le menu « Actions (Agir) », les numéros dans « Moyens de don ».',
            'eyebrow' => 'Agir & Soutenir',
            'title' => "Choisissez votre *façon d'aider*",
            'subtitle' => null,
        ],
        'cta' => [
            'label' => "Bandeau d'appel au don",
            'hint' => null,
            'eyebrow' => null,
            'title' => 'Ensemble, construisons un avenir meilleur',
            'subtitle' => 'Votre soutien, quelle que soit sa forme, change concrètement des vies.',
        ],
        'contact' => [
            'label' => 'Contact',
            'hint' => 'Les coordonnées se gèrent dans « Paramètres > Informations ».',
            'eyebrow' => 'Contact',
            'title' => 'Parlons de *votre engagement*',
            'subtitle' => 'Vous souhaitez faire un don, devenir partenaire ou simplement en savoir plus ? Notre équipe vous répond.',
        ],
    ],

    // Objets proposés dans le formulaire de contact
    'contact_subjects' => [
        'Je souhaite faire un don',
        'Je veux parrainer une élève',
        'Je veux devenir bénévole',
        'Proposition de partenariat',
        'Autre demande',
    ],

    // Types d'engagement (mêmes clés que les cartes « Agir »)
    'engagement_types' => [
        'donation' => 'Faire un don',
        'sponsorship' => 'Parrainer une élève',
        'volunteer' => 'Devenir bénévole',
        'partner' => 'Devenir partenaire',
    ],
];
