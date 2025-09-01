
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Espace - User</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{asset('userAssets/assets/vendors/simple-line-icons/css/simple-line-icons.css')}} ">
    <link rel="stylesheet" href="{{asset('userAssets/assets/vendors/flag-icon-css/css/flag-icons.min.css')}}">
    <link rel="stylesheet" href="{{asset('userAssets/assets/vendors/css/vendor.bundle.base.css')}}">

    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{asset('userAssets/assets/vendors/font-awesome/css/font-awesome.min.css')}}" />
    <link rel="stylesheet" href="{{asset('userAssets/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('userAssets/assets/vendors/jvectormap/jquery-jvectormap.css')}}">
    <link rel="stylesheet" href="{{asset('userAssets/assets/vendors/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('userAssets/assets/vendors/chartist/chartist.min.css')}}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{asset('userAssets/assets/css/vertical-light-layout/style.css')}}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{asset('assets/assets/images/logo E-messe.jpeg')}}" />
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
      @include('user.layouts.navbar')
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        @include('user.layouts.sidebar')
        <!-- partial -->
        @yield('content')
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{asset('userAssets/assets/vendors/js/vendor.bundle.base.js')}}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{asset('userAssets/assets/vendors/chart.js/chart.umd.js')}}"></script>
    <script src="{{asset('userAssets/assets/vendors/jvectormap/jquery-jvectormap.min.js')}}"></script>
    <script src="{{asset('userAssets/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
    <script src="{{asset('userAssets/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('userAssets/assets/vendors/moment/moment.min.js')}}"></script>
    <script src="{{asset('userAssets/assets/vendors/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('userAssets/assets/vendors/chartist/chartist.min.js')}}"></script>
    <script src="{{asset('userAssets/assets/vendors/progressbar.js/progressbar.min.js')}}"></script>
    <script src="{{asset('userAssets/assets/js/jquery.cookie.js')}}"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{asset('userAssets/assets/js/off-canvas.js')}}"></script>
    <script src="{{asset('userAssets/assets/js/hoverable-collapse.js')}}"></script>
    <script src="{{asset('userAssets/assets/js/misc.js')}}"></script>
    <script src="{{asset('userAssets/assets/js/settings.js')}}"></script>
    <script src="{{asset('userAssets/assets/js/todolist.js')}}"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="{{asset('userAssets/assets/js/dashboard.js')}}"></script>
    <!-- End custom js for this page -->
  </body>
</html>