{{-- ════ CTA BANDE ════ --}}
@php $onHome = Route::is('index'); @endphp
<section class="cta-bande section-pad-sm">
  <div class="container text-center position-relative">
    @if ($section->title) <h2 class="mb-2">{{ \App\Support\Site::plain($section->title) }}</h2> @endif
    @if ($section->subtitle) <p class="mb-4">{{ $section->subtitle }}</p> @endif
    <div class="d-flex flex-wrap justify-content-center gap-3">
      <a href="{{ $onHome && in_array('agir', $homeSections ?? []) ? '#agir' : route('don') }}" class="btn-cta-white btn"><i class="bi bi-heart-fill me-2" aria-hidden="true"></i>Faire un don maintenant</a>
      <a href="{{ $onHome && in_array('contact', $homeSections ?? []) ? '#contact' : route('contact') }}" class="btn btn-cta-ghost">Nous contacter</a>
    </div>
  </div>
</section>
