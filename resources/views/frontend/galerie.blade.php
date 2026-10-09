{{-- GALERIE : toutes les photos et vidéos envoyées depuis l'admin --}}
@extends('frontend.layouts.app')

@php
  $lead = "Photos et vidéos de nos actions sur le terrain.";
  $hasVideos = $images->contains('type', 'video');
  $hasPhotos = $images->contains('type', 'image');
@endphp

@section('title', 'Galerie photos et vidéos')
@section('meta_description', "Découvrez en images l'action de la fondation : " . $lead)

@section('content')
  @include('frontend.partials.page-hero', ['title' => 'Galerie', 'lead' => $lead])

  <section class="section-pad">
    <div class="container">
      @if ($hasVideos && $hasPhotos)
      <div class="d-flex flex-wrap gap-2 mb-4" role="group" aria-label="Filtrer la galerie">
        <button type="button" class="media-tab-btn active" data-filter="all" aria-pressed="true">Tout voir</button>
        <button type="button" class="media-tab-btn" data-filter="image" aria-pressed="false">Photos</button>
        <button type="button" class="media-tab-btn" data-filter="video" aria-pressed="false">Vidéos</button>
      </div>
      @endif

      @if ($images->isNotEmpty())
      <div class="media-grid" id="mediaGrid">
        @foreach ($images as $item)
        <figure class="media-card" data-type="{{ $item->type }}">
          @if ($item->type == 'video')
          <video controls preload="metadata" playsinline>
            <source src="{{ asset('storage/' . $item->path) }}#t=0.5">
            Votre navigateur ne peut pas lire cette vidéo.
          </video>
          @else
          <a href="{{ asset('storage/' . $item->path) }}" data-lightbox data-caption="{{ $item->title }}">
            <img src="{{ asset('storage/' . $item->path) }}" alt="{{ $item->title ?: 'Photo de la fondation' }}" loading="lazy" decoding="async">
          </a>
          @endif
          @if ($item->title)
          <figcaption>{{ $item->title }}</figcaption>
          @endif
        </figure>
        @endforeach
      </div>
      @else
      <div class="empty-state">
        <i class="bi bi-images" aria-hidden="true"></i>
        <h2>La galerie est en préparation</h2>
        <p>Nos photos et vidéos seront bientôt disponibles.</p>
        <a href="{{ route('index') }}" class="btn-prog btn-prog-blue">Retour à l'accueil</a>
      </div>
      @endif
    </div>
  </section>

  @include('frontend.sections.cta', ['section' => $site->section('cta')])
@endsection
