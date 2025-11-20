<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Espace - Parish</title>

  <!-- Bootstrap 5 CSS -->
  <link rel="stylesheet" href="{{ asset('bootstrap5.0.2/css/bootstrap.min.css') }}">

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="{{ asset('DataTables/css/dataTables.bootstrap5.min.css') }}">

  <!-- Plugins et icônes -->
  <link rel="stylesheet" href="{{ asset('assetsPoste/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assetsPoste/assets/vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('assetsPoste/assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assetsPoste/assets/vendors/jvectormap/jquery-jvectormap.css') }}">

  <!-- Styles personnalisés -->
  <link rel="stylesheet" href="{{ asset('assetsPoste/assets/css/demo/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  {{-- <link rel="stylesheet" href="{{ asset('css/dashboard_paroisse.css') }}"> --}}

  <link rel="shortcut icon" href="{{ asset('assets/assets/images/logo E-messe.jpeg') }}" />

  <!-- Styles spécifiques aux pages -->
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
    </div>
  </div>
</div>

<!-- jQuery (DOIT ÊTRE LE PREMIER) -->
<script src="{{ asset('DataTables/js/jquery-3.7.0.min.js') }}"></script>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Bootstrap 5 JS bundle (Popper inclus) -->
<script src="{{ asset('bootstrap5.0.2/js/bootstrap.bundle.min.js') }}"></script>

<!-- DataTables JS (DOIT ÊTRE APRÈS jQuery) -->
<script src="{{ asset('DataTables/dataTables.js') }}"></script>
<script src="{{ asset('DataTables/dataTables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Plugins JS -->
<script src="{{ asset('assetsPoste/assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('assetsPoste/assets/vendors/chartjs/Chart.min.js') }}"></script>
<script src="{{ asset('assetsPoste/assets/vendors/jvectormap/jquery-jvectormap.min.js') }}"></script>
<script src="{{ asset('assetsPoste/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>

<!-- Inject JS -->
<script src="{{ asset('assetsPoste/assets/js/material.js') }}"></script>
<script src="{{ asset('assetsPoste/assets/js/misc.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('assetsPoste/assets/js/dashboard.js') }}"></script>

<!-- Scripts spécifiques aux pages -->
@stack('js')

</body>
</html>