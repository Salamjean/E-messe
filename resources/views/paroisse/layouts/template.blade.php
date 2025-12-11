<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Espace - Parish</title>

    <!-- Bootstrap 5 CSS (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('assetsPoste/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsPoste/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsPoste/assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assetsPoste/assets/vendors/jvectormap/jquery-jvectormap.css') }}">

    <!-- Styles personnalisés -->
    <link rel="stylesheet" href="{{ asset('assetsPoste/assets/css/demo/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    @stack('css')

</head>

<body>

    <script src="{{ asset('assetsPoste/assets/js/preloader.js') }}"></script>

    <div class="body-wrapper">
        @include('paroisse.layouts.sidebar')

        <div class="main-wrapper mdc-drawer-app-content">
            @include('paroisse.layouts.navbar')

            <div class="page-wrapper mdc-toolbar-fixed-adjust">
                @yield('content')
                @include('paroisse.layouts.footer')
            </div>
        </div>

    </div>

    <!-- jQuery (DOIT ÊTRE LE PREMIER) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Bootstrap 5 JS (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS (CDN) -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Plugins JS -->
    <script src="{{ asset('assetsPoste/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assetsPoste/assets/vendors/chartjs/Chart.min.js') }}"></script>
    <script src="{{ asset('assetsPoste/assets/vendors/jvectormap/jquery-jvectormap.min.js') }}"></script>
    <script src="{{ asset('assetsPoste/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>

    <!-- Inject JS -->
    <script src="{{ asset('assetsPoste/assets/js/material.js') }}"></script>
    <script src="{{ asset('assetsPoste/assets/js/misc.js') }}"></script>
    <script src="{{ asset('assetsPoste/assets/js/dashboard.js') }}"></script>

    @stack('js')

</body>

</html>
<style>
    /* Force le wrapper principal à prendre toute la hauteur */
    .page-wrapper {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    /* Pousse le footer vers le bas */
    .page-wrapper>footer,
    .page-wrapper>.footer,
    /* Cible le dernier élément (le footer) si les classes ci-dessus ne correspondent pas */
    .page-wrapper> :last-child {
        margin-top: auto;
        /* color: #000; */
    }
</style>
