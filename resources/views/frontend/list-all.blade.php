{{-- LISTE COMPLÈTE : actualités, actions (réalisations) ou projets --}}
@extends('frontend.layouts.app')

@php
  $intro = [
      'news' => "Suivez la vie de la fondation : événements, remises de dons, témoignages et nouvelles du terrain.",
      'projet' => "Les initiatives que nous menons ou préparons, et leur état d'avancement.",
      'realisation' => "Les actions concrètes déjà menées par la fondation auprès des populations.",
  ][$type];
@endphp

@section('title', $title . ($items->currentPage() > 1 ? ' — page ' . $items->currentPage() : ''))
@section('meta_description', $intro)
@if ($items->currentPage() > 1)
  @section('canonical', $items->url($items->currentPage()))
@endif

@section('content')
  @include('frontend.partials.page-hero', ['title' => $title, 'lead' => $intro])

  <section class="section-pad">
    <div class="container">
      @if ($items->count())
      <div class="row g-4">
        @foreach ($items as $item)
        <div class="col-md-6 col-lg-4">
          @include('frontend.partials.card', ['heading' => 'h2'])
        </div>
        @endforeach
      </div>

      @if ($items->hasPages())
      <nav class="site-pagination mt-5" aria-label="Pagination">
        {{ $items->onEachSide(1)->links('pagination::bootstrap-5') }}
      </nav>
      @endif
      @else
      <div class="empty-state">
        <i class="bi bi-inbox" aria-hidden="true"></i>
        <h2>Aucun contenu pour le moment</h2>
        <p>Cette rubrique sera bientôt alimentée. Revenez nous voir !</p>
        <a href="{{ route('index') }}" class="btn-prog btn-prog-blue">Retour à l'accueil</a>
      </div>
      @endif
    </div>
  </section>

  @include('frontend.sections.cta', ['section' => $site->section('cta')])
@endsection
