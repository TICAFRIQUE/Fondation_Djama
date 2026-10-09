@yield('css')
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
<!-- include summernote css/js -->

<!-- Bootstrap Css -->
<link href="{{ URL::asset('build/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ URL::asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css (composants du gabarit : cartes, tableaux, fenêtres...) -->
<link href="{{ URL::asset('build/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<!-- custom Css-->
<link href="{{ URL::asset('build/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
{{-- @yield('css') --}}
<link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Habillage de l'admin : couleurs de la fondation, polices, menu, barre du haut, connexion -->
{{-- le dossier s'appelle public/adm et non public/admin : un dossier « admin » masquerait la route /admin --}}
<link href="{{ URL::asset('adm/css/admin.css') }}?v={{ filemtime(public_path('adm/css/admin.css')) }}" rel="stylesheet" type="text/css" />
