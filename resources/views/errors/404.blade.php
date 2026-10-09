{{-- PAGE 404 du site public --}}
@extends('frontend.layouts.app')

@section('title', 'Page introuvable')
@section('robots', 'noindex, follow')

@section('content')
  <section class="section-pad error-page">
    <div class="container">
      <div class="empty-state">
        <div class="error-code" aria-hidden="true">404</div>
        <h1>Cette page est introuvable</h1>
        <p>Le lien est peut-être erroné ou la page a été déplacée.</p>
        <div class="d-flex flex-wrap justify-content-center gap-2">
          <a href="{{ route('index') }}" class="btn-prog btn-prog-blue"><i class="bi bi-house" aria-hidden="true"></i> Retour à l'accueil</a>
          <a href="{{ route('news.all') }}" class="btn-prog btn-prog-outline">Nos actualités</a>
          <a href="{{ route('contact') }}" class="btn-prog btn-prog-outline">Nous contacter</a>
        </div>
      </div>
    </div>
  </section>
@endsection
