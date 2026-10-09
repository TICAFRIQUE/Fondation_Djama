{{-- ════ ACTIONS ════ (Admin > Réalisations) : les 6 premières, la liste complète a sa page --}}
<section class="section-pad section-alt" id="realisations">
  <div class="container">
    <div class="row align-items-center mb-5 gy-3">
      <div class="col-lg-6">
        @include('frontend.partials.section-head', ['mb0' => true])
      </div>
      <div class="col-lg-6">
        @if ($section->subtitle) <p class="section-lead mb-0">{{ $section->subtitle }}</p> @endif
      </div>
    </div>

    <div class="row g-4">
      @foreach ($realisations as $item)
      <div class="col-sm-6 col-lg-4">
        @include('frontend.partials.card', ['type' => 'realisation'])
      </div>
      @endforeach
    </div>

    <div class="text-center mt-5">
      <a href="{{ route('realisations.all') }}" class="btn-prog btn-prog-blue">Voir toutes nos actions <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
    </div>
  </div>
</section>
