{{--
  Carte d'un contenu (actualité, action, projet ou programme).
  Paramètres : $item, $type (news | realisation | projet | programme), $heading (h3 par défaut).
--}}
@php
  $heading = $heading ?? 'h3';
  $url = match ($type) {
      'news' => route('news.show', $item->slug),
      'projet' => route('projets.show', $item->slug),
      'programme' => route('programmes.show', $item->slug),
      default => route('realisations.show', $item->slug),
  };
  $image = \App\Support\Site::image($item->image);
  $excerpt = \App\Support\Site::excerpt($item->content ?? $item->description, 120);
@endphp
<article class="content-card">
  <a href="{{ $url }}" class="content-card-img" tabindex="-1" aria-hidden="true">
    @if ($image)
    <img src="{{ $image }}" alt="" loading="lazy" decoding="async" width="640" height="400">
    @else
    <span class="img-placeholder"><i class="bi bi-image"></i></span>
    @endif

    @if ($type === 'news' && $item->category)
    <span class="card-badge card-badge-top">{{ $item->category }}</span>
    @elseif ($type === 'projet')
    <span class="card-badge card-badge-top" style="{{ $item->status_style }}">{{ $item->status_label }}</span>
    @elseif ($type === 'realisation' && $item->date_start)
    <span class="card-badge card-badge-year">
      {{ $item->date_start->format('Y') }}@if ($item->date_end && $item->date_end->year !== $item->date_start->year) – {{ $item->date_end->format('Y') }}@endif
    </span>
    @endif
  </a>

  <div class="content-card-body">
    @if ($type === 'news')
    <div class="content-card-meta">
      <span><i class="bi bi-calendar3" aria-hidden="true"></i> <time datetime="{{ ($item->published_at ?? $item->created_at)->toDateString() }}">{{ ($item->published_at ?? $item->created_at)->translatedFormat('j F Y') }}</time></span>
      @if ($item->reading_time)
      <span><i class="bi bi-clock" aria-hidden="true"></i> {{ $item->reading_time }} min</span>
      @endif
    </div>
    @endif

    <{{ $heading }} class="content-card-title"><a href="{{ $url }}">{{ $item->title }}</a></{{ $heading }}>
    <p>{{ $excerpt }}</p>

    @if ($type === 'projet' && $item->status !== 'bientot')
    <div class="projet-progress" role="progressbar" aria-label="Avancement du projet" aria-valuenow="{{ $item->progress }}" aria-valuemin="0" aria-valuemax="100">
      <div class="projet-progress-bar" style="width:{{ min(100, max(0, (int) $item->progress)) }}%"></div>
    </div>
    <div class="projet-progress-label">Avancement : {{ (int) $item->progress }} %</div>
    @endif

    <a href="{{ $url }}" class="btn-more" aria-label="En savoir plus : {{ $item->title }}">En savoir plus <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
  </div>
</article>
