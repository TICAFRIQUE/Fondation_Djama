{{-- CONTACT : coordonnées (Admin > Paramètres) et formulaire (Admin > Messages) --}}
@extends('frontend.layouts.app')

@php $section = $site->section('contact'); @endphp

@section('title', 'Contact')
@section('meta_description', \App\Support\Site::excerpt($section->subtitle ?: 'Contactez ' . $site->name() . ' : adresse, téléphone, email et formulaire de contact.', 160))

@section('content')
  @include('frontend.partials.page-hero', ['title' => 'Contact', 'lead' => $section->subtitle])

  <section class="section-pad">
    <div class="container">
      <div class="row gy-5">
        <div class="col-lg-5">
          <div class="section-eyebrow">Nos coordonnées</div>
          <h2 class="section-title">{{ \App\Support\Site::title($section->title ?: 'Écrivez-nous') }}</h2>
          @include('frontend.partials.contact-details')
        </div>
        <div class="col-lg-7">
          @include('frontend.partials.contact-form')
        </div>
      </div>
    </div>
  </section>
@endsection
