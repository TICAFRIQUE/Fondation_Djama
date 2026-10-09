{{-- ════ PROJETS ════ (Admin > Projets) : carrousel à défilement horizontal --}}
<section class="section-pad" id="projets">
  <div class="container">
    <div class="section-bar mb-5">
      <div>
        @include('frontend.partials.section-head', ['mb0' => true])
      </div>

      <div class="d-flex align-items-center gap-3">
        <a href="{{ route('projets.all') }}" class="btn-prog btn-prog-outline">Voir tous les projets <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
        <div class="projets-nav" id="projetsNav">
          <button class="projets-nav-btn" id="projetPrev" aria-label="Projets précédents"><i class="bi bi-chevron-left" aria-hidden="true"></i></button>
          <button class="projets-nav-btn" id="projetNext" aria-label="Projets suivants"><i class="bi bi-chevron-right" aria-hidden="true"></i></button>
        </div>
      </div>
    </div>

    <ul class="projets-carousel-track" id="projetsTrack" tabindex="0" aria-label="Liste des projets">
      @foreach ($projets as $item)
      <li class="projet-slide">
        @include('frontend.partials.card', ['type' => 'projet'])
      </li>
      @endforeach
    </ul>
  </div>
</section>
