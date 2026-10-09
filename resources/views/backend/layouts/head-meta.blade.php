{{-- Balises communes aux pages de l'admin (avec ou sans menu) --}}
<meta charset="utf-8" />
<title>@yield('title') | Administration — {{ $data_parametre->nom_projet ?? config('site.name') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="Administration du site de la fondation" name="description" />
<meta name="robots" content="noindex, nofollow">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="theme-color" content="#0D2B45">
<!-- App favicon -->
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('site/img/favicon-32.png') }}">
<link rel="apple-touch-icon" href="{{ asset('site/img/apple-touch-icon.png') }}">

{{-- applique avant l'affichage le thème et l'état du menu choisis par l'utilisateur (évite un clignotement) --}}
<script>
    (function () {
        try {
            var root = document.documentElement;
            var theme = localStorage.getItem('adm-theme');
            if (theme) root.setAttribute('data-bs-theme', theme);
            if (localStorage.getItem('adm-sidebar') === 'collapsed') root.classList.add('adm-collapsed');
        } catch (e) {}
    })();
</script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

@include('backend.layouts.head-css')
