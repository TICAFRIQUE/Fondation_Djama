{{-- En-tête d'une section de l'accueil : sur-titre, titre et texte d'introduction saisis en admin --}}
@php $center = $center ?? false; @endphp
@if ($section->eyebrow)
<div class="section-eyebrow {{ $center ? 'justify-content-center' : '' }}">{{ $section->eyebrow }}</div>
@endif
@if ($section->title)
<h2 class="section-title {{ ($mb0 ?? false) ? 'mb-0' : '' }}">{{ \App\Support\Site::title($section->title) }}</h2>
@endif
@if ($center && ($lead ?? true) && $section->subtitle)
<p class="section-lead mx-auto">{{ $section->subtitle }}</p>
@endif
