{{-- ════ PROGRAMMES ════ (Admin > Programmes). Chaque programme a sa propre page. --}}
@php $banner = $programmes->first(); @endphp
<section class="section-pad section-alt" id="programmes">
  <div class="container">
    <div class="text-center mb-5">
      @include('frontend.partials.section-head', ['center' => true, 'lead' => false])
    </div>

    <div class="programmes-split">
      <div class="programmes-banner">
        @if ($banner->image)
        <img src="{{ asset('storage/' . $banner->image) }}" alt="" loading="lazy" decoding="async" width="680" height="1080">
        @endif
        <div class="programmes-banner-overlay"></div>

        <div class="programmes-banner-content">
          <div class="programmes-banner-badge">
            {{ $programmes->count() }} {{ $programmes->count() > 1 ? 'programmes actifs' : 'programme actif' }}
          </div>

          <h3>{{ $programmes->take(4)->pluck('title')->implode(' · ') }}</h3>
          @if ($section->subtitle) <p>{{ $section->subtitle }}</p> @endif

          <a href="{{ in_array('agir', $homeSections) ? '#agir' : route('don') }}" class="btn-slide-primary align-self-start"><i class="bi bi-heart-fill" aria-hidden="true"></i> Soutenir un programme</a>
        </div>
      </div>

      <ol class="programmes-list">
        @foreach ($programmes as $prog)
        <li class="prog-list-item">
          <div class="prog-list-num" style="background:{{ $prog->color_bg ?: '#1F4E79' }};color:{{ $prog->color_text ?: '#fff' }};" aria-hidden="true">{{ $loop->iteration }}</div>
          <div>
            <h3>{{ $prog->title }}</h3>
            <p>{{ \App\Support\Site::excerpt($prog->description, 110) }}</p>
            <a class="prog-list-link" href="{{ route('programmes.show', $prog->slug) }}" aria-label="En savoir plus : {{ $prog->title }}">
              En savoir plus <i class="bi bi-arrow-right" aria-hidden="true"></i>
            </a>
          </div>
        </li>
        @endforeach
      </ol>
    </div>
  </div>
</section>
