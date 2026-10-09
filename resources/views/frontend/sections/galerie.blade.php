{{-- ════ GALERIE ════ (Admin > Galerie) : les photos « À la une », sinon les plus récentes --}}
<section class="galerie-section" id="galerie">
  <div class="container">
    <div class="text-center mb-5">
      @if ($section->eyebrow) <div class="section-eyebrow section-eyebrow-light justify-content-center">{{ $section->eyebrow }}</div> @endif
      @if ($section->title) <h2 class="galerie-quote">{{ \App\Support\Site::title($section->title) }}</h2> @endif
      @if ($section->subtitle) <p class="galerie-lead">{{ $section->subtitle }}</p> @endif
    </div>

    <div class="galerie-grid" data-count="{{ $galerie->count() }}">
      @foreach ($galerie as $photo)
      <a class="gal-item gal-{{ $loop->iteration }}" href="{{ asset('storage/' . $photo->path) }}" data-lightbox data-caption="{{ $photo->title }}">
        <img src="{{ asset('storage/' . $photo->path) }}" alt="{{ $photo->title ?: 'Photo de la fondation' }}" loading="lazy" decoding="async">
        <div class="gal-overlay"></div>
        @if ($photo->title) <div class="gal-label">{{ $photo->title }}</div> @endif
      </a>
      @endforeach
    </div>

    <div class="text-center mt-5">
      <a href="{{ route('galerie') }}" class="btn btn-ghost-light">
        <i class="bi bi-images me-2" aria-hidden="true"></i>Voir toute la galerie
      </a>
    </div>
  </div>
</section>
