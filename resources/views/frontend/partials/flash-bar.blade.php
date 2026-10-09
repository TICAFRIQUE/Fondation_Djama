{{-- Bandeau d'infos flash, alimenté depuis l'admin (Site > Infos flash) --}}
@php $flashInfos = $site->flashInfos(); @endphp
@if ($flashInfos->isNotEmpty())
<aside class="flash-bar flash-{{ $flashInfos->first()->type }}" id="flashBar" aria-label="Infos flash"
  data-key="{{ md5($flashInfos->map(fn ($flash) => $flash->id . $flash->updated_at)->implode('|')) }}">
  <div class="container flash-bar-inner">
    <span class="flash-label"><i class="bi bi-megaphone-fill" aria-hidden="true"></i> Info flash</span>

    <div class="flash-viewport" aria-live="polite">
      @foreach ($flashInfos as $flash)
      <p class="flash-item {{ $loop->first ? 'active' : '' }}" data-type="{{ $flash->type }}">
        <span>{{ $flash->message }}</span>
        @if ($flash->link_url)
        <a href="{{ \App\Support\Site::link($flash->link_url) }}">{{ $flash->link_text ?: 'En savoir plus' }} <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
        @endif
      </p>
      @endforeach
    </div>

    @if ($flashInfos->count() > 1)
    <span class="flash-count" aria-hidden="true"><span id="flashIndex">1</span>/{{ $flashInfos->count() }}</span>
    @endif
    <button type="button" class="flash-close" id="flashClose" aria-label="Masquer les infos flash"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
  </div>
</aside>
@endif
