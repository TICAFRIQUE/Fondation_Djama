{{-- ════ AGIR ════ (Admin > Actions (Agir) et Moyens de don) --}}
<section class="section-pad" id="agir">
  <div class="container">
    <div class="text-center mb-5">
      @include('frontend.partials.section-head', ['center' => true])
    </div>

    <div class="row g-4 justify-content-center">
      @foreach ($agirs as $agir)
      <div class="col-sm-6 col-lg-3">
        @include('frontend.partials.agir-card')
      </div>
      @endforeach
    </div>
  </div>
</section>

@include('frontend.partials.don-modal')
