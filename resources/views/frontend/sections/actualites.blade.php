{{-- ════ ACTUALITÉS ════ (Admin > Actualités) : les 3 derniers articles --}}
<section class="section-pad" id="actualites">
  <div class="container">
    <div class="section-bar mb-5">
      <div>
        @include('frontend.partials.section-head', ['mb0' => true])
      </div>
      <a href="{{ route('news.all') }}" class="btn-prog btn-prog-outline">Toutes les actualités <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
    </div>

    <div class="row g-4">
      @foreach ($news as $item)
      <div class="col-md-6 col-lg-4">
        @include('frontend.partials.card', ['type' => 'news'])
      </div>
      @endforeach
    </div>
  </div>
</section>
