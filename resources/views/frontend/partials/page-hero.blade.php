{{--
  Bandeau de titre des pages intérieures, avec fil d'Ariane.
  Paramètres : $title, $lead (facultatif), $crumbs (facultatif) = [['label' => ..., 'url' => ...], ...]
--}}
@php
  $trail = array_merge([['label' => 'Accueil', 'url' => route('index')]], $crumbs ?? [], [['label' => $title, 'url' => url()->current()]]);

  $breadcrumbList = [
      '@context' => 'https://schema.org',
      '@type' => 'BreadcrumbList',
      'itemListElement' => array_map(fn ($crumb, $index) => [
          '@type' => 'ListItem',
          'position' => $index + 1,
          'name' => $crumb['label'],
          'item' => $crumb['url'],
      ], $trail, array_keys($trail)),
  ];
@endphp
<header class="page-hero">
  <div class="container">
    <nav aria-label="Fil d'Ariane">
      <ol class="crumbs">
        @foreach ($trail as $crumb)
        @if ($loop->last)
        <li aria-current="page">{{ \Illuminate\Support\Str::limit($crumb['label'], 60) }}</li>
        @else
        <li><a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a></li>
        @endif
        @endforeach
      </ol>
    </nav>
    <h1>{{ $title }}</h1>
    @if (! empty($lead))
    <p class="page-hero-lead">{{ $lead }}</p>
    @endif
  </div>
</header>
@push('jsonld')
<script type="application/ld+json">{!! json_encode($breadcrumbList, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endpush
