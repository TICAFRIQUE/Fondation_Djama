{{--
  PAGE D'ACCUEIL
  Les sections s'affichent dans l'ordre choisi en admin (Site > Sections de l'accueil).
  Chaque section vit dans resources/views/frontend/sections/<clé>.blade.php
--}}
@extends('frontend.layouts.app')

@php
  // Sections réellement affichées : le menu fait défiler vers elles
  $homeSections = $sections->keys()->all();

  $webSite = [
      '@context' => 'https://schema.org',
      '@type' => 'WebSite',
      'name' => $site->name(),
      'url' => route('index'),
      'inLanguage' => 'fr',
  ];
@endphp

@push('jsonld')
<script type="application/ld+json">{!! json_encode($webSite, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endpush

@section('content')
  @include('frontend.sections.hero')

  @foreach ($sections as $section)
    @include('frontend.sections.' . $section->key)
  @endforeach
@endsection
