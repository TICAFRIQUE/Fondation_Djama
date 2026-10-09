{{-- À PROPOS : présentation complète de la fondation (Admin > À propos, Impacts, Programmes, Témoignages) --}}
@extends('frontend.layouts.app')

@php
  // Sans fiche « À propos » en admin, la page présente la description générale du site
  $aboutTitle = $apropos->title ?? $site->name();
  $aboutText = $apropos->description ?? $site->description();
  $aboutImage = $apropos->image ?? null;
@endphp

@section('title', 'À propos')
@section('meta_description', \App\Support\Site::excerpt($aboutText, 160))

@section('content')
  @include('frontend.partials.page-hero', ['title' => 'À propos', 'lead' => $site->slogan()])

  <section class="section-pad">
    <div class="container">
      <div class="row g-4 g-lg-5 align-items-start">
        @if ($aboutImage)
        <div class="col-lg-5">
          <div class="apropos-img-block apropos-img-page">
            <img src="{{ asset('storage/' . $aboutImage) }}" alt="{{ $site->name() }} sur le terrain" fetchpriority="high" decoding="async" width="800" height="900">
            @if ($apropos->stat_1_value || $apropos->stat_2_value)
            @include('frontend.partials.apropos-stats')
            @endif
          </div>
        </div>
        @endif

        <div class="{{ $aboutImage ? 'col-lg-7' : 'col-lg-9' }}">
          <div class="section-eyebrow">Qui sommes-nous</div>
          <h2 class="section-title">{{ $aboutTitle }}</h2>
          <div class="article-content article-content-plain">{{ \App\Support\Site::rich($aboutText) }}</div>

          @if ($apropos && $apropos->items->isNotEmpty())
          <div class="d-flex flex-column gap-3 mt-4">
            @foreach ($apropos->items as $item)
            <div class="apropos-info-item">
              <div class="apropos-info-icon" style="background:{{ $item->color ?? '#E3F2FD' }};" aria-hidden="true">{{ $item->icon ?? '📌' }}</div>
              <div>
                <h3>{{ $item->title }}</h3>
                <p>{{ $item->description }}</p>
              </div>
            </div>
            @endforeach
          </div>
          @endif
        </div>
      </div>
    </div>
  </section>

  @if ($impacts->isNotEmpty())
    @include('frontend.sections.impact', ['section' => $site->section('impact')])
  @endif

  @if ($programmes->isNotEmpty())
  <section class="section-pad" id="programmes">
    <div class="container">
      <div class="text-center mb-5">
        @include('frontend.partials.section-head', ['section' => $site->section('programmes'), 'center' => true])
      </div>
      <div class="row g-4 justify-content-center">
        @foreach ($programmes as $item)
        <div class="col-md-6 col-lg-3">
          @include('frontend.partials.card', ['type' => 'programme'])
        </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  @if ($temoignages->isNotEmpty())
    @include('frontend.sections.temoignages', ['section' => $site->section('temoignages')])
  @endif

  @include('frontend.sections.cta', ['section' => $site->section('cta')])
@endsection
