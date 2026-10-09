<!doctype html>
<html lang="fr" data-bs-theme="light">

<head>
    @include('backend.layouts.head-meta')
</head>

<body class="adm-auth">
@include('sweetalert::alert')

@yield('content')

@include('backend.layouts.vendor-scripts')
</body>

</html>
