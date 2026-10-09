{{-- ════ HERO SLIDER ════ (Admin > Sliders). Le premier slide porte le titre principal (h1) de la page. --}}
@php
  $donUrl = in_array('agir', $homeSections) ? '#agir' : route('don');
@endphp
<div class="hero-slider" id="heroSlider" role="region" aria-roledescription="carrousel" aria-label="À la une">
  <div class="slider-track" id="sliderTrack">
    @forelse ($sliders as $slide)
    <div class="slide {{ $loop->first ? 'active' : '' }}" role="group" aria-roledescription="diapositive" aria-label="{{ $loop->iteration }} sur {{ $loop->count }}">
      @if ($slide->image)
      <img class="slide-bg" src="{{ asset('storage/' . $slide->image) }}" alt="" decoding="async"
        @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif>
      @else
      <div class="slide-bg"></div>
      @endif
      <div class="slide-overlay"></div>

      <div class="slide-content">
        <div class="container py-5">
          <div class="row">
            <div class="col-lg-8 col-xl-7">
              @if ($slide->badge) <div class="slide-badge">{!! $slide->badge !!}</div> @endif
              <{{ $loop->first ? 'h1' : 'h2' }} class="slide-title">
                {{ $slide->title }}
                @if ($slide->highlight) <br><span>{{ $slide->highlight }}</span> @endif
              </{{ $loop->first ? 'h1' : 'h2' }}>
              @if ($slide->description) <p class="slide-desc">{{ $slide->description }}</p> @endif
              <div class="slide-btns">
                @if ($slide->btn1_text) <a href="{{ \App\Support\Site::link($slide->btn1_link) }}" class="btn-slide-primary">{{ $slide->btn1_text }}</a> @endif
                @if ($slide->btn2_text) <a href="{{ \App\Support\Site::link($slide->btn2_link) }}" class="btn-slide-secondary">{{ $slide->btn2_text }} <i class="bi bi-arrow-right" aria-hidden="true"></i></a> @endif
              </div>
              @if ($slide->stats)
              <div class="slider-stats">
                @foreach ($slide->stats as $stat)
                <div class="slider-stat">
                  <div class="slider-stat-num">{{ $stat['value'] ?? '' }}</div>
                  <div class="slider-stat-label">{{ $stat['label'] ?? '' }}</div>
                </div>
                @endforeach
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
    @empty
    {{-- Aucun slider en admin : bannière par défaut construite avec les paramètres du site --}}
    <div class="slide active">
      <div class="slide-bg"></div>
      <div class="slide-overlay"></div>
      <div class="slide-content">
        <div class="container py-5">
          <div class="row">
            <div class="col-lg-8 col-xl-7">
              <h1 class="slide-title">{{ $site->name() }}<br><span>{{ $site->slogan() }}</span></h1>
              <p class="slide-desc">{{ $site->description() }}</p>
              <div class="slide-btns">
                <a href="{{ $donUrl }}" class="btn-slide-primary"><i class="bi bi-heart-fill" aria-hidden="true"></i> Faire un don</a>
                <a href="{{ route('apropos') }}" class="btn-slide-secondary">Découvrir la fondation <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforelse
  </div>

  @if ($sliders->count() > 1)
  <button class="slider-arrow prev" id="sliderPrev" aria-label="Diapositive précédente"><i class="bi bi-chevron-left" aria-hidden="true"></i></button>
  <button class="slider-arrow next" id="sliderNext" aria-label="Diapositive suivante"><i class="bi bi-chevron-right" aria-hidden="true"></i></button>

  <div class="slider-dots" id="sliderDots">
    @foreach ($sliders as $slide)
    <button class="slider-dot {{ $loop->first ? 'active' : '' }}" aria-label="Aller à la diapositive {{ $loop->iteration }}"></button>
    @endforeach
  </div>

  <div class="slider-progress" id="sliderProgress"></div>
  @endif
</div>
