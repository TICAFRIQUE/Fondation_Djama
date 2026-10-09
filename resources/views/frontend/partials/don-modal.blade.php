{{-- ════ MODAL AGIR (infos de don) ════ --}}
<div class="modal-overlay" id="agirModal" role="dialog" aria-modal="true" aria-labelledby="agirModalTitle">
  <div class="modal-box">
    <button type="button" class="modal-close" aria-label="Fermer"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
    <h2 id="agirModalTitle" class="modal-title">Soutenir la fondation</h2>

    <p class="don-info-note">Merci pour votre générosité ! Vous pouvez faire un don via l'un des moyens suivants :</p>

    @include('frontend.partials.payment-methods')

    <a href="{{ route('don') }}#engagement-form" class="btn-more mt-2">Nous prévenir de votre don <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
  </div>
</div>
