{{--
  Balises de référencement communes à toutes les pages.
  Chaque page peut préciser : title, meta_description, og_image, og_type, canonical, robots.
  Les sections Blade sont déjà échappées, d'où l'affichage avec {!! !!}.
--}}
@php
  $pageTitle = trim($__env->yieldContent('title'));
  $seoTitle = $pageTitle !== '' ? $pageTitle . ' | ' . e($site->name()) : e($site->metaTitle());
  $seoDescription = trim($__env->yieldContent('meta_description')) ?: e(\App\Support\Site::excerpt($site->metaDescription(), 160));
  $seoImage = trim($__env->yieldContent('og_image')) ?: e($site->shareImage());
  $seoUrl = trim($__env->yieldContent('canonical')) ?: e(url()->current());

  $organisation = array_filter([
      '@context' => 'https://schema.org',
      '@type' => 'NGO',
      'name' => $site->name(),
      'url' => route('index'),
      'logo' => url($site->logo()),
      'slogan' => $site->slogan(),
      'description' => $site->description(),
      'email' => $site->emails()[0] ?? null,
      'telephone' => $site->phones()[0] ?? null,
      'address' => array_filter([
          '@type' => 'PostalAddress',
          'streetAddress' => $site->address(),
          'addressLocality' => config('site.locality'),
          'addressCountry' => config('site.country'),
      ]),
      'sameAs' => array_column($site->socials(), 'url'),
  ]);
  $jsonFlags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG;
@endphp
<title>{!! $seoTitle !!}</title>
<meta name="description" content="{!! $seoDescription !!}">
@if ($site->setting('meta_keywords'))
<meta name="keywords" content="{{ $site->setting('meta_keywords') }}">
@endif
<meta name="robots" content="@yield('robots', 'index, follow, max-image-preview:large')">
<link rel="canonical" href="{!! $seoUrl !!}">

<meta property="og:locale" content="fr_FR">
<meta property="og:site_name" content="{{ $site->name() }}">
<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:title" content="{!! $seoTitle !!}">
<meta property="og:description" content="{!! $seoDescription !!}">
<meta property="og:url" content="{!! $seoUrl !!}">
<meta property="og:image" content="{!! $seoImage !!}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{!! $seoTitle !!}">
<meta name="twitter:description" content="{!! $seoDescription !!}">
<meta name="twitter:image" content="{!! $seoImage !!}">

<script type="application/ld+json">{!! json_encode($organisation, $jsonFlags) !!}</script>
@stack('jsonld')
