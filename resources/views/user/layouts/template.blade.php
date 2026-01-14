<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Méta-données requises -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Espace - User</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/assets/images/logo_principal.svg') }}" type="image/jpeg">

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

@if (session('show_tutorial_popup'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Utilisation du localStorage pour rendre l'affichage spécifique à l'appareil et au compte
            const userId = "{{ auth()->id() }}";
            const tutorialKey = 'tutorial_seen_' + userId;

            if (!localStorage.getItem(tutorialKey)) {
                Swal.fire({
                    title: '<div class="text-center mb-3"><i class="fas fa-desktop fa-3x" style="color: #cca45e;"></i></div><h3 class="font-weight-bold">Bienvenue sur E-messe</h3>',
                    html: '<p class="text-muted small">Pour vous aider à démarrer sur E-messe, nous avons préparé quelques vidéos tutoriels pour vous montrer comment demander une messe simplement.</p>',
                    showCancelButton: true,
                    confirmButtonText: 'Regarder la vidéo',
                    cancelButtonText: 'Plus tard',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-tutorial px-4 mx-2',
                        cancelButton: 'btn btn-outline-secondary px-4 mx-2',
                        popup: 'border-radius-15'
                    },
                    buttonsStyling: false,
                    background: '#ffffff',
                    width: '400px',
                    padding: '2rem'
                }).then((result) => {
                    // Masquer définitivement pour cet utilisateur sur cet appareil
                    localStorage.setItem(tutorialKey, 'true');

                    if (result.isConfirmed) {
                        window.location.href = "{{ route('user.settings.index') }}#tutorials";
                    }
                });
            }
        });
    </script>
    <style>
        .border-radius-15 {
            border-radius: 15px !important;
        }

        .btn-tutorial {
            background-color: #cca45e !important;
            border-color: #cca45e !important;
            color: #ffffff !important;
        }

        .btn-tutorial:hover {
            background-color: #b38f4d !important;
            border-color: #b38f4d !important;
            color: #ffffff !important;
        }

        .swal2-title {
            padding-top: 0 !important;
        }

        .swal2-html-container {
            margin-top: 10px !important;
        }
    </style>
@endif
