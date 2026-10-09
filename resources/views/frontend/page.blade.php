{{-- PAGE LIBRE gérée en admin (Site > Pages) : mentions légales, politique de confidentialité... --}}
@extends('frontend.layouts.app')

@section('title', $page->title)
@section('meta_description', $page->meta_description ?: \App\Support\Site::excerpt($page->content, 160))

@section('content')
  @include('frontend.partials.page-hero', ['title' => $page->title])

  <section class="section-pad">
    <div class="container">
      <div class="article-content article-content-narrow">
        {{ \App\Support\Site::rich($page->content) }}
      </div>
    </div>
  </section>
@endsection
