<!doctype html>
<html lang="fr" data-bs-theme="light">

<head>
    @include('backend.layouts.head-meta')
</head>

<body class="adm">
    @include('backend.layouts.sidebar')

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="adm-main">
        @include('backend.layouts.topbar')

        <main class="adm-content">
            <div class="container-fluid">
                @include('sweetalert::alert')
                @yield('content')
            </div>
            <!-- container-fluid -->
        </main>

        @include('backend.layouts.footer')
    </div>
    <!-- end main content-->

    <!-- JAVASCRIPT -->
    @include('backend.layouts.vendor-scripts')
</body>

</html>
