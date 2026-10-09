{{-- PAGE DÉTAIL : actualité, action (réalisation), projet ou programme --}}
@extends('frontend.layouts.app')

@php
  $config = [
      'news' => ['label' => 'Actualités', 'list' => route('news.all'), 'related' => 'À lire aussi'],
      'realisation' => ['label' => 'Actions', 'list' => route('realisations.all'), 'related' => 'Autres actions'],
      'projet' => ['label' => 'Projets', 'list' => route('projets.all'), 'related' => 'Autres projets'],
      'programme' => ['label' => 'Programmes', 'list' => route('apropos') . '#programmes', 'related' => 'Nos autres programmes'],
  ][$type];

  $body = $data->content ?? $data->description;
  $summary = \App\Support\Site::excerpt($body, 160);
  $image = \App\Support\Site::image($data->image);
  $published = $data->published_at ?? $data->created_at;
  $shareUrl = urlencode(url()->current());
  $shareText = urlencode($data->title);

  // Données structurées : un article pour les actualités, pour que Google affiche date et image
  $article = $type === 'news' ? array_filter([
      '@context' => 'https://schema.org',
      '@type' => 'NewsArticle',
      'headline' => \Illuminate\Support\Str::limit($data->title, 110, ''),
      'description' => $summary,
      'image' => $image ? [$image] : null,
      'datePublished' => $published?->toAtomString(),
      'dateModified' => $data->updated_at?->toAtomString(),
      'articleSection' => $data->category,
      'mainEntityOfPage' => url()->current(),
      'author' => ['@type' => 'Organization', 'name' => $site->name(), 'url' => route('index')],
      'publisher' => ['@type' => 'Organization', 'name' => $site->name(), 'logo' => ['@type' => 'ImageObject', 'url' => url($site->logo())]],
  ]) : null;
@endphp

@section('title', $data->title)
@section('meta_description', $summary)
@section('og_type', $type === 'news' ? 'article' : 'website')
@if ($image)
  @section('og_image', $image)
@endif

@if ($article)
  @push('jsonld')
  <script type="application/ld+json">{!! json_encode($article, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
  @endpush
@endif

@section('content')
  @include('frontend.partials.page-hero', [
      'title' => $data->title,
      'crumbs' => [['label' => $config['label'], 'url' => $config['list']]],
  ])

  <div class="section-pad">
    <div class="container">
      <div class="row g-4 g-lg-5">

        <article class="col-lg-8">
          @if ($image)
          <figure class="article-cover">
            <img src="{{ $image }}" alt="{{ $data->title }}" fetchpriority="high" decoding="async" width="1200" height="675">
          </figure>
          @endif

          <div class="article-content">
            {{ \App\Support\Site::rich($body) }}
          </div>
        </article>

        <aside class="col-lg-4">
          <div class="article-aside">
            <h2 class="article-aside-title">Informations</h2>

            <dl class="article-facts">
              @if ($type === 'news')
              @if ($data->category)
              <div><dt>Rubrique</dt><dd>{{ $data->category }}</dd></div>
              @endif
              <div><dt>Publication</dt><dd><time datetime="{{ $published->toDateString() }}">{{ $published->translatedFormat('j F Y') }}</time></dd></div>
              @if ($data->reading_time)
              <div><dt>Lecture</dt><dd>{{ $data->reading_time }} min</dd></div>
              @endif
              @endif

              @if (in_array($type, ['realisation', 'projet']) && $data->date_start)
              <div>
                <dt>Période</dt>
                <dd>
                  {{ $data->date_start->translatedFormat('F Y') }}
                  @if ($data->date_end) — {{ $data->date_end->translatedFormat('F Y') }} @endif
                </dd>
              </div>
              @endif

              @if ($type === 'projet')
              <div><dt>Statut</dt><dd><span class="card-badge" style="{{ $data->status_style }}">{{ $data->status_label }}</span></dd></div>
              @if ($data->status !== 'bientot')
              <div>
                <dt>Avancement</dt>
                <dd>
                  <div class="projet-progress" role="progressbar" aria-label="Avancement du projet" aria-valuenow="{{ (int) $data->progress }}" aria-valuemin="0" aria-valuemax="100">
                    <div class="projet-progress-bar" style="width:{{ min(100, max(0, (int) $data->progress)) }}%"></div>
                  </div>
                  <div class="projet-progress-label">{{ (int) $data->progress }} %</div>
                </dd>
              </div>
              @endif
              @endif
            </dl>

            <div class="article-share">
              <div class="article-share-label">Partager</div>
              <div class="d-flex flex-wrap gap-2">
                <a class="share-btn" href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener" aria-label="Partager sur Facebook"><i class="bi bi-facebook" aria-hidden="true"></i></a>
                <a class="share-btn" href="https://wa.me/?text={{ $shareText }}%20{{ $shareUrl }}" target="_blank" rel="noopener" aria-label="Partager sur WhatsApp"><i class="bi bi-whatsapp" aria-hidden="true"></i></a>
                <a class="share-btn" href="https://twitter.com/intent/tweet?text={{ $shareText }}&url={{ $shareUrl }}" target="_blank" rel="noopener" aria-label="Partager sur X"><i class="bi bi-twitter-x" aria-hidden="true"></i></a>
                <button type="button" class="share-btn" data-copy-link="{{ url()->current() }}" aria-label="Copier le lien"><i class="bi bi-link-45deg" aria-hidden="true"></i></button>
              </div>
            </div>

            <a href="{{ route('don') }}" class="btn btn-don w-100 mt-4"><i class="bi bi-heart-fill me-2" aria-hidden="true"></i>Soutenir la fondation</a>
            <a href="{{ $config['list'] }}" class="btn-more mt-3"><i class="bi bi-arrow-left" aria-hidden="true"></i> Retour : {{ $config['label'] }}</a>
          </div>
        </aside>

      </div>

      @if ($related->isNotEmpty())
      <section class="related-block" aria-labelledby="related-title">
        <h2 class="section-title" id="related-title">{{ $config['related'] }}</h2>
        <div class="row g-4">
          @foreach ($related as $item)
          <div class="col-md-6 col-lg-4">
            @include('frontend.partials.card')
          </div>
          @endforeach
        </div>
      </section>
      @endif
    </div>
  </div>
@endsection
