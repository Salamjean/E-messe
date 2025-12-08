<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Espace - User</title>

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('userAssets/assets/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('userAssets/assets/vendors/flag-icon-css/css/flag-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('userAssets/assets/vendors/css/vendor.bundle.base.css') }}">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/event.css') }}">

    <!-- Page plugins -->
    <link rel="stylesheet" href="{{ asset('userAssets/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('userAssets/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('userAssets/assets/vendors/jvectormap/jquery-jvectormap.css') }}">
    <link rel="stylesheet" href="{{ asset('userAssets/assets/vendors/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('userAssets/assets/vendors/chartist/chartist.min.css') }}">

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('userAssets/assets/css/vertical-light-layout/style.css') }}">

    <link rel="shortcut icon" href="{{ asset('assets/assets/images/logo E-messe.jpeg') }}">
</head>

<body>
    <div class="container-scroller page-wrapper">

        <!-- NAVBAR -->
        @include('user.layouts.navbar')

        <!-- MAIN WRAPPER -->
        <div class="container-fluid page-body-wrapper">

            <!-- SIDEBAR -->
            @include('user.layouts.sidebar')

            <!-- CONTENT -->
            <div class="main-panel">
                @yield('content')

                <!-- FOOTER -->
                @include('user.layouts.footer')
            </div>

        </div>

    </div>

    <!-- Core JS -->
    <script src="{{ asset('userAssets/assets/vendors/js/vendor.bundle.base.js') }}"></script>

    <!-- Page plugins -->
    <script src="{{ asset('userAssets/assets/vendors/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('userAssets/assets/vendors/jvectormap/jquery-jvectormap.min.js') }}"></script>
    <script src="{{ asset('userAssets/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('userAssets/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('userAssets/assets/vendors/moment/moment.min.js') }}"></script>
    <script src="{{ asset('userAssets/assets/vendors/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('userAssets/assets/vendors/chartist/chartist.min.js') }}"></script>
    <script src="{{ asset('userAssets/assets/vendors/progressbar.js/progressbar.min.js') }}"></script>
    <script src="{{ asset('userAssets/assets/js/jquery.cookie.js') }}"></script>

    <!-- Layout JS -->
    <script src="{{ asset('userAssets/assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('userAssets/assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('userAssets/assets/js/misc.js') }}"></script>
    <script src="{{ asset('userAssets/assets/js/settings.js') }}"></script>
    <script src="{{ asset('userAssets/assets/js/todolist.js') }}"></script>

    <!-- Custom page JS -->
    <script src="{{ asset('userAssets/assets/js/dashboard.js') }}"></script>

</body>

</html>

<style>
    /* Conserve ton footer toujours en bas */
    .page-wrapper {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .page-wrapper>footer,
    .page-wrapper>.footer,
    .page-wrapper> :last-child {
        margin-top: auto;
    }
</style>
