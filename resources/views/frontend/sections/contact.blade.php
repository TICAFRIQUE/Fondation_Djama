{{-- ════ CONTACT ════ (coordonnées : Admin > Paramètres > Informations) --}}
<section class="section-pad" id="contact">
  <div class="container">
    <div class="row gy-5">
      <div class="col-lg-5">
        @include('frontend.partials.section-head')
        @if ($section->subtitle) <p class="contact-lead">{{ $section->subtitle }}</p> @endif

        @include('frontend.partials.contact-details')
      </div>

      <div class="col-lg-7">
        @include('frontend.partials.contact-form')
      </div>
    </div>
  </div>
</section>
