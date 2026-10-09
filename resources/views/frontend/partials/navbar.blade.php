{{--
  Menu principal. Sur l'accueil, chaque lien fait défiler vers sa section si elle est affichée ;
  sur les autres pages (ou si la section est masquée), il mène à la page dédiée.
--}}
@php
  $onHome = Route::is('index');
  $homeSections = $homeSections ?? [];

  $menu = [
      ['label' => 'À propos', 'section' => 'apropos', 'url' => route('apropos'), 'active' => Route::is('apropos')],
      ['label' => 'Actualités', 'section' => 'actualites', 'url' => route('news.all'), 'active' => Route::is('news.all', 'news.show')],
      ['label' => 'Actions', 'section' => 'realisations', 'url' => route('realisations.all'), 'active' => Route::is('realisations.all', 'realisations.show')],
      ['label' => 'Projets', 'section' => 'projets', 'url' => route('projets.all'), 'active' => Route::is('projets.all', 'projets.show')],
      ['label' => 'Galerie', 'section' => 'galerie', 'url' => route('galerie'), 'active' => Route::is('galerie')],
  ];
  $donUrl = $onHome && in_array('agir', $homeSections) ? '#agir' : route('don');
@endphp
<nav class="navbar navbar-expand-lg navbar-djama" id="mainNav" aria-label="Menu principal">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ $onHome ? '#top' : route('index') }}">
      <img src="{{ $site->logo() }}" class="navbar-brand-logo" alt="Logo {{ $site->name() }}" width="56" height="56">
      <span class="navbar-brand-text">{{ $site->name() }}</span>
    </a>

    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"
      aria-controls="navMenu" aria-expanded="false" aria-label="Ouvrir le menu">
      <i class="bi bi-list" aria-hidden="true"></i>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-1 py-2 py-lg-0">
        <li class="nav-item">
          <a class="nav-link-djama {{ $onHome ? 'active' : '' }}" href="{{ $onHome ? '#top' : route('index') }}">Accueil</a>
        </li>
        @foreach ($menu as $item)
        @php $scrolls = $onHome && in_array($item['section'], $homeSections); @endphp
        <li class="nav-item">
          <a class="nav-link-djama {{ $item['active'] ? 'active' : '' }}" href="{{ $scrolls ? '#' . $item['section'] : $item['url'] }}"
            @if ($item['active']) aria-current="page" @endif>{{ $item['label'] }}</a>
        </li>
        @endforeach
        <li class="nav-item ms-lg-2">
          <a href="{{ $donUrl }}" class="btn btn-don"><i class="bi bi-heart-fill me-1" aria-hidden="true"></i> Faire un don</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
