<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Méta-données requises -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Espace - User</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/assets/images/logo E-messe.jpeg') }}" type="image/jpeg">

    <!-- font-awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- CSS : Plugins Vendeurs -->

    <!-- Bootstrap 5 CSS (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <link rel="stylesheet" href="{{ asset('userAssets/assets/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('userAssets/assets/vendors/flag-icon-css/css/flag-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('userAssets/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet"
        href="{{ asset('userAssets/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('userAssets/assets/vendors/jvectormap/jquery-jvectormap.css') }}">
    <link rel="stylesheet" href="{{ asset('userAssets/assets/vendors/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('userAssets/assets/vendors/chartist/chartist.min.css') }}">

    <!-- CSS : Layout Principal -->
    <link rel="stylesheet" href="{{ asset('userAssets/assets/css/vertical-light-layout/style.css') }}">

    <!-- CSS : Personnalisé -->
    <link rel="stylesheet" href="{{ asset('css/event.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-custom.css') }}">

    <!-- CSS Interne : Sticky Footer -->

</head>

<body>
    <div class="container-scroller">

        <!-- NAVBAR -->
        @include('user.layouts.navbar')

        <!-- MAIN WRAPPER (Sidebar + Content) -->
        <div class="container-fluid page-body-wrapper">

            <!-- SIDEBAR -->
            <div>
                @include('user.layouts.sidebar')
            </div>
            <!-- MAIN PANEL -->
            <div class="container-fluid">

                <!-- CONTENT WRAPPER -->
                <div class="content-wrapper mt-0 mt-10 mt-mobile-10">

                    @yield('content')
                </div>

                <!-- FOOTER -->
                @include('user.layouts.footer')

            </div>
            <!-- container-scroller ends -->
        </div>
    </div>
</body>

</html>
<style>
    @media (max-width: 450px) {
        .content-wrapper.mt-mobile-10 {
            margin-top: 90px !important;
            /* Adjust this value as needed */
        }
    }

    @media (max-width: 650px) {
        .content-wrapper.mt-mobile-10 {
            margin-top: 20px !important;
            /* Adjust this value as needed */
        }
    }

    @media (max-width: 850px) {
        .content-wrapper.mt-mobile-10 {
            margin-top: 20px !important;
            /* Adjust this value as needed */
        }
    }

    @media (max-width: 1050px) {
        .content-wrapper.mt-mobile-10 {
            margin-top: 10px !important;
            /* Adjust this value as needed */
        }
    }

    /* Force le wrapper principal à prendre toute la hauteur */
    .page-wrapper {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    /* Pousse le footer vers le bas */
    .container-fluid.page-body-wrapper {
        display: flex;
        flex-direction: column;
        min-height: calc(100vh - 70px);
        flex-grow: 1;
    }

    .container-fluid.page-body-wrapper>.container-fluid {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .container-fluid.page-body-wrapper>.container-fluid>.content-wrapper {
        flex-grow: 1;
    }

    .container-fluid.page-body-wrapper>.container-fluid>footer {
        margin-top: auto;
    }

    .content-wrapper {
        margin-top: 10px;
        margin-left: 10px;
    }
</style>

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

<!-- JS : Core -->
<script src="{{ asset('userAssets/assets/vendors/js/vendor.bundle.base.js') }}"></script>

<!-- JS : Plugins de page -->
<script src="{{ asset('userAssets/assets/vendors/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('userAssets/assets/vendors/jvectormap/jquery-jvectormap.min.js') }}"></script>
<script src="{{ asset('userAssets/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ asset('userAssets/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('userAssets/assets/vendors/moment/moment.min.js') }}"></script>
<script src="{{ asset('userAssets/assets/vendors/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('userAssets/assets/vendors/chartist/chartist.min.js') }}"></script>
<script src="{{ asset('userAssets/assets/vendors/progressbar.js/progressbar.min.js') }}"></script>
<script src="{{ asset('userAssets/assets/js/jquery.cookie.js') }}"></script>

<!-- JS : Layout -->
<script src="{{ asset('userAssets/assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('userAssets/assets/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('userAssets/assets/js/misc.js') }}"></script>
<script src="{{ asset('userAssets/assets/js/settings.js') }}"></script>
<script src="{{ asset('userAssets/assets/js/todolist.js') }}"></script>

<!-- JS : Personnalisé -->
<script src="{{ asset('userAssets/assets/js/dashboard.js') }}"></script>
