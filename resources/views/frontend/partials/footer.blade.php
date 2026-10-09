@php
  $socials = $site->socials();
  $phones = $site->phones();
  $emails = $site->emails();
  $footerPages = $site->footerPages();
@endphp
<footer class="footer-djama py-5">
  <div class="container">
    <div class="row gy-4">
      <div class="col-lg-4">
        <div class="d-flex align-items-center gap-2 mb-2">
          <img src="{{ $site->logo('logo_footer') }}" class="navbar-brand-logo" alt="Logo {{ $site->name() }}" width="56" height="56" loading="lazy">
          <div class="footer-brand">{{ $site->name() }}</div>
        </div>
        <div class="footer-slogan">« {{ $site->slogan() }} »</div>
        <p class="footer-text mt-3">{{ $site->description() }}</p>

        @if ($socials)
        <div class="d-flex flex-wrap gap-2 mt-3">
          @foreach ($socials as $social)
          <a href="{{ $social['url'] }}" class="social-btn" aria-label="{{ $social['label'] }}" target="_blank" rel="noopener"><i class="bi {{ $social['icon'] }}" aria-hidden="true"></i></a>
          @endforeach
        </div>
        @endif
      </div>

      <nav class="col-6 col-lg-2" aria-label="Navigation du pied de page">
        <div class="footer-title">Navigation</div>
        <a class="footer-link" href="{{ route('index') }}">Accueil</a>
        <a class="footer-link" href="{{ route('apropos') }}">À propos</a>
        <a class="footer-link" href="{{ route('news.all') }}">Actualités</a>
        <a class="footer-link" href="{{ route('realisations.all') }}">Actions</a>
        <a class="footer-link" href="{{ route('projets.all') }}">Projets</a>
      </nav>

      <nav class="col-6 col-lg-2" aria-label="Ressources">
        <div class="footer-title">Ressources</div>
        <a class="footer-link" href="{{ route('galerie') }}">Galerie</a>
        <a class="footer-link" href="{{ route('don') }}">Faire un don</a>
        <a class="footer-link" href="{{ route('contact') }}">Contact</a>
        @foreach ($footerPages as $footerPage)
        <a class="footer-link" href="{{ route('page.show', $footerPage->slug) }}">{{ $footerPage->title }}</a>
        @endforeach
      </nav>

      <div class="col-lg-4">
        <div class="footer-title">Nous joindre</div>
        <ul class="footer-contact">
          @if ($site->address())
          <li><i class="bi bi-geo-alt" aria-hidden="true"></i><span>{{ $site->address() }}</span></li>
          @endif
          @foreach ($phones as $phone)
          <li><i class="bi bi-telephone" aria-hidden="true"></i><a href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}">{{ $phone }}</a></li>
          @endforeach
          @foreach ($emails as $email)
          <li><i class="bi bi-envelope" aria-hidden="true"></i><a href="mailto:{{ $email }}">{{ $email }}</a></li>
          @endforeach
        </ul>

        <a href="{{ route('don') }}" class="btn btn-don footer-don">
          <i class="bi bi-heart-fill me-2" aria-hidden="true"></i>Faire un don
        </a>
      </div>
    </div>

    <hr class="footer-divider my-4" />

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
      <div class="footer-copy">© {{ date('Y') }} {{ $site->name() }}. Tous droits réservés.</div>
      @if ($footerPages->isNotEmpty())
      <div class="d-flex flex-wrap gap-3">
        @foreach ($footerPages as $footerPage)
        <a class="footer-legal" href="{{ route('page.show', $footerPage->slug) }}">{{ $footerPage->title }}</a>
        @endforeach
      </div>
      @endif
    </div>
  </div>
</footer>
