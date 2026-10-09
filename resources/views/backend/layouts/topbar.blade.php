{{-- Barre du haut de l'admin : bouton du menu, accès au site, thème clair/sombre, compte --}}
@php
    $user = Auth::user();
    $initials = mb_strtoupper(mb_substr($user->username ?? $user->email ?? '?', 0, 2));
@endphp
<header class="adm-topbar">
    {{-- sur ordinateur : réduit le menu aux icônes ; sur mobile : ouvre le menu par-dessus la page --}}
    <button type="button" class="adm-icon-btn" id="topnav-hamburger-icon" data-adm-toggle aria-controls="admSidebar"
        aria-label="Afficher ou réduire le menu">
        <i class="ri-menu-2-line"></i>
    </button>

    <div class="adm-topbar-tools">
        <a href="{{ route('index') }}" target="_blank" rel="noopener" class="btn btn-soft-primary btn-sm d-none d-sm-inline-flex align-items-center gap-1">
            <i class="ri-external-link-line"></i> Voir le site
        </a>

        <button type="button" class="adm-icon-btn" data-adm-theme aria-label="Passer du thème clair au thème sombre">
            <i class="ri-moon-line"></i>
        </button>

        <!-- ========== Start profil ========== -->
        <div class="dropdown">
            <button type="button" class="adm-user" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                @if ($user->avatar != '')
                    <img class="adm-avatar" src="{{ URL::asset('images/' . $user->avatar) }}" alt="">
                @else
                    <span class="adm-avatar">{{ $initials }}</span>
                @endif
                <span class="adm-user-text d-none d-md-block">
                    <strong>{{ $user->username }}</strong>
                    <small>{{ $user->roles[0]->name ?? $user->role }}</small>
                </span>
                <i class="ri-arrow-down-s-line d-none d-md-block"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <h6 class="dropdown-header">Bienvenue {{ $user->username }} !</h6>
                <a class="dropdown-item" href="{{ route('admin-register.profil', $user->id) }}">
                    <i class="ri-account-circle-line text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Profil</span>
                </a>
                <a class="dropdown-item d-sm-none" href="{{ route('index') }}" target="_blank" rel="noopener">
                    <i class="ri-external-link-line text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Voir le site</span>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="ri-logout-box-r-line text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Déconnexion</span>
                </a>
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
        <!-- ========== End profil ========== -->
    </div>
</header>
