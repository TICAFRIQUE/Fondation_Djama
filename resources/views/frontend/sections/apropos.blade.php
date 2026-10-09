{{-- ════ À PROPOS ════ (Admin > À propos) --}}
@php $hasStats = $apropos->stat_1_value || $apropos->stat_2_value; @endphp
<section class="section-pad" id="apropos">
  <div class="container">
    <div class="row g-0 align-items-stretch apropos-card">

      @if ($apropos->image)
      <div class="col-lg-5">
        <div class="apropos-img-block">
          <img src="{{ asset('storage/' . $apropos->image) }}" alt="{{ $site->name() }} sur le terrain" loading="lazy" decoding="async" width="800" height="900">

          @if ($hasStats)
          @include('frontend.partials.apropos-stats')
          @endif
        </div>
      </div>
      @endif

      <div class="{{ $apropos->image ? 'col-lg-7' : 'col-12' }} apropos-body">
        @if ($section->eyebrow) <div class="section-eyebrow">{{ $section->eyebrow }}</div> @endif
        <h2 class="section-title">{{ $apropos->title }}</h2>
        <p class="apropos-text">{{ \Illuminate\Support\Str::limit($apropos->description, 480) }}</p>

        <div class="d-flex flex-column gap-3 mb-4">
          @foreach ($apropos->items->take(3) as $item)
          <div class="apropos-info-item">
            <div class="apropos-info-icon" style="background:{{ $item->color ?? '#E3F2FD' }};" aria-hidden="true">{{ $item->icon ?? '📌' }}</div>
            <div>
              <h3>{{ $item->title }}</h3>
              <p>{{ $item->description }}</p>
            </div>
          </div>
          @endforeach
        </div>

        <div class="d-flex flex-wrap gap-2">
          <a href="{{ route('apropos') }}" class="btn btn-don px-4">Découvrir la fondation</a>
          <a href="{{ in_array('agir', $homeSections) ? '#agir' : route('don') }}" class="btn-prog btn-prog-outline">Nous soutenir <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
        </div>
      </div>

    </div>
  </div>
</section>
