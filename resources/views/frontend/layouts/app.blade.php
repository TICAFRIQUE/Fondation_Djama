<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  @include('frontend.partials.seo')

  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('site/img/favicon-32.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('site/img/apple-touch-icon.png') }}">
  <meta name="theme-color" content="#1F4E79">

  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet" />
  <link href="{{ asset('site/css/site.css') }}?v={{ filemtime(public_path('site/css/site.css')) }}" rel="stylesheet" />

  @stack('head')
</head>

<body>
  <a class="skip-link" href="#contenu">Aller au contenu</a>

  @include('frontend.partials.flash-bar')
  @include('frontend.partials.navbar')

  <main id="contenu">
    @yield('content')
  </main>

  @include('frontend.partials.footer')

  <button class="back-top" id="backTop" aria-label="Retour en haut"><i class="bi bi-chevron-up"></i></button>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
  <script src="{{ asset('site/js/site.js') }}?v={{ filemtime(public_path('site/js/site.js')) }}" defer></script>
  @stack('scripts')
</body>

</html>
