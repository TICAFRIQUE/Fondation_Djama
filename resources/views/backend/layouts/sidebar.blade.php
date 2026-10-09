{{--
    Barre latérale de l'admin. Son comportement (réduction, tiroir mobile) est dans public/adm/js/admin.js.
    Une entrée n'apparaît que si l'utilisateur a la permission « voir-<module> ».
--}}
@php
    $site = app(\App\Support\SiteContext::class);
    $settingsOpen = Route::is('role.*', 'parametre.*', 'module.*', 'permission.*', 'admin-register.*');

    // [permission, route, motif de route active, icône, libellé]
    $groups = [
        "Page d'accueil" => [
            ['voir-sections', 'sections.index', 'sections.*', 'ri-layout-row-line', "Sections de l'accueil"],
            ['voir-infos-flash', 'flash-infos.index', 'flash-infos.*', 'ri-megaphone-line', 'Infos flash'],
            ['voir-sliders', 'sliders.index', 'sliders.*', 'ri-slideshow-4-line', 'Sliders'],
            ['voir-impacts', 'impacts.index', 'impacts.*', 'ri-bar-chart-line', 'Impacts'],
            ['voir-apropos', 'apropos.index', 'apropos.*', 'ri-information-line', 'À propos'],
            ['voir-temoignages', 'temoignages.index', 'temoignages.*', 'ri-double-quotes-l', 'Témoignages'],
        ],
        'Contenus' => [
            ['voir-programmes', 'programmes.index', 'programmes.*', 'ri-briefcase-line', 'Programmes'],
            ['voir-actualites', 'news.index', 'news.*', 'ri-newspaper-line', 'Actualités'],
            ['voir-realisations', 'realisations.index', 'realisations.*', 'ri-award-line', 'Réalisations (Actions)'],
            ['voir-projets', 'projets.index', 'projets.*', 'ri-folder-chart-line', 'Projets'],
            ['voir-galerie', 'galerie.index', 'galerie.*', 'ri-image-line', 'Galerie'],
            ['voir-pages', 'pages.index', 'pages.*', 'ri-file-text-line', 'Pages légales'],
        ],
        'Dons & contacts' => [
            ['voir-agirs', 'agirs.index', 'agirs.*', 'ri-heart-line', 'Actions (Agir)'],
            ['voir-moyens-don', 'moyens-don.index', 'moyens-don.*', 'ri-bank-card-line', 'Moyens de don'],
            ['voir-messages', 'engagements.index', 'engagements.*', 'ri-hand-heart-line', 'Engagements'],
            ['voir-messages', 'messages.index', 'messages.*', 'ri-mail-line', 'Messages'],
        ],
    ];
@endphp
<aside class="adm-sidebar" id="admSidebar" aria-label="Menu d'administration">
    <a href="{{ route('dashboard.index') }}" class="adm-brand">
        <img src="{{ $site->logo() }}" alt="" width="40" height="40">
        <span class="adm-brand-text">
            <strong>{{ $site->name() }}</strong>
            <small>Administration</small>
        </span>
    </a>

    <nav class="adm-nav" id="admNav">
        <ul>
            @can('voir-tableau de bord')
                <li>
                    <a class="adm-nav-link {{ Route::is('dashboard.*') ? 'active' : '' }}" href="{{ route('dashboard.index') }}" title="Tableau de bord">
                        <i class="ri-dashboard-2-line"></i> <span>Tableau de bord</span>
                    </a>
                </li>
            @endcan
            <li>
                <a class="adm-nav-link" href="{{ route('index') }}" target="_blank" rel="noopener" title="Voir le site">
                    <i class="ri-external-link-line"></i> <span>Voir le site</span>
                </a>
            </li>

            @foreach ($groups as $groupTitle => $links)
                @php $visible = array_filter($links, fn ($link) => Auth::user()->can($link[0])); @endphp
                @if ($visible)
                    <li class="adm-nav-title"><span>{{ $groupTitle }}</span></li>
                    @foreach ($visible as [$permission, $route, $pattern, $icon, $label])
                        <li>
                            <a class="adm-nav-link {{ Route::is($pattern) ? 'active' : '' }}" href="{{ route($route) }}" title="{{ $label }}"
                                @if (Route::is($pattern)) aria-current="page" @endif>
                                <i class="{{ $icon }}"></i> <span>{{ $label }}</span>
                            </a>
                        </li>
                    @endforeach
                @endif
            @endforeach

            {{-- SECTION PARAMÈTRES --}}
            @if (in_array(Auth::user()->role, ['superadmin', 'developpeur']) || Auth::user()->can('voir-parametre'))
                <li class="adm-nav-title"><span>Configuration</span></li>
                <li>
                    <a class="adm-nav-link adm-nav-toggle {{ $settingsOpen ? '' : 'collapsed' }}" href="#admSettings" data-bs-toggle="collapse"
                        role="button" aria-expanded="{{ $settingsOpen ? 'true' : 'false' }}" aria-controls="admSettings" title="Paramètres">
                        <i class="ri-settings-3-line"></i> <span>Paramètres</span>
                        <i class="ri-arrow-down-s-line adm-nav-caret"></i>
                    </a>
                    <ul class="collapse adm-nav-sub {{ $settingsOpen ? 'show' : '' }}" id="admSettings">
                        <li><a href="{{ route('parametre.index') }}" class="{{ Route::is('parametre.*') ? 'active' : '' }}">Informations & SEO</a></li>
                        <li><a href="{{ route('admin-register.index') }}" class="{{ Route::is('admin-register.*') ? 'active' : '' }}">Utilisateurs</a></li>
                        <li><a href="{{ route('module.index') }}" class="{{ Route::is('module.*') ? 'active' : '' }}">Modules</a></li>
                        <li><a href="{{ route('role.index') }}" class="{{ Route::is('role.*') ? 'active' : '' }}">Rôles</a></li>
                        <li><a href="{{ route('permission.index') }}" class="{{ Route::is('permission.*') ? 'active' : '' }}">Permissions / Rôles</a></li>
                    </ul>
                </li>
            @endif
        </ul>
    </nav>
</aside>

{{-- voile sombre derrière le menu ouvert sur mobile --}}
<div class="adm-overlay" data-adm-close></div>
