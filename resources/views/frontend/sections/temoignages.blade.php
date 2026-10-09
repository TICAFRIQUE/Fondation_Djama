{{-- ════ TÉMOIGNAGES ════ (Admin > Témoignages) --}}
<section class="section-pad section-alt" id="temoignages">
  <div class="container">
    <div class="text-center mb-5">
      @include('frontend.partials.section-head', ['center' => true])
    </div>

    <div class="row g-4 justify-content-center">
      @foreach ($temoignages as $temoignage)
      <div class="col-md-6 col-lg-4">
        @include('frontend.partials.testimonial')
      </div>
      @endforeach
    </div>
  </div>
</section>
