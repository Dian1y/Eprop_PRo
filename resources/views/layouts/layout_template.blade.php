<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>B2B Enesis Portal</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset('vendor/admin-lte/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('vendor/admin-lte/bower_components/font-awesome/css/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('vendor/admin-lte/bower_components/Ionicons/css/ionicons.min.css')}}">
    <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('vendor/admin-lte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
   <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('vendor/admin-lte/bower_components/select2/dist/css/select2.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('vendor/admin-lte/dist/css/AdminLTE.min.css')}}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{ asset('vendor/admin-lte/dist/css/skins/_all-skins.min.css')}}">
  <!-- Morris chart -->
  <link rel="stylesheet" href="{{ asset('vendor/admin-lte/bower_components/morris.js/morris.css')}}">
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{ asset('vendor/admin-lte/bower_components/jvectormap/jquery-jvectormap.css')}}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{ asset('vendor/admin-lte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('vendor/admin-lte/bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="{{ asset('vendor/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
 <!--
  <script src="{{ asset('css/app.css') }}"></script> -->

  <!-- Google Font -->
   <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
      .example-modal .modal {
        position: relative;
        top: auto;
        bottom: auto;
        right: auto;
        left: auto;
        display: block;
        z-index: 1;
      }

      .example-modal .modal {
        background: transparent !important;
      }

        input[type=text]
        {
            font-size:24px;
        }

        :required {
          background: lightyellow;
          font-size:24px;
        }
        /**
         * style input elements that have a required
         * attribute and a focus state
         */
        :required:focus {
          border: 1px solid red;
          outline: none;
        }

        /**
         * style input elements that have a required
         * attribute and a hover state
         */
        :required:hover {
          opacity: 1;
        }

        .select2-required {
            background: lightyellow; 
        }
    </style>
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">
 <!-- Main Header -->
    @include('layouts.header')
    <!-- Sidebar -->
    @include('layouts.sidebar')
    <div class="content-wrapper">
      @include('layouts.partials._message')
      <section class="content-header">
          @yield('content_header')
      </section>
      <section class="content">
        @yield('content')
      </section>
    </div>
    @stack('modal')
    <!-- /.content-wrapper -->
    <!-- Footer -->
    @include('layouts.footer')
    <!-- ./wrapper -->
    <!-- REQUIRED JS SCRIPTS -->
    <!-- ./wrapper -->
    <!-- jQuery 3 -->
    <script src="{{ asset('vendor/admin-lte/bower_components/jquery/dist/jquery.min.js')}}"></script>
    <!-- jQuery UI 1.11.4 
    <script src="{{ asset('vendor/admin-lte/bower_components/jquery-ui/jquery-ui.min.js')}}"></script> -->
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip 
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script> -->
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('vendor/admin-lte/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- Morris.js charts -->
    <script src="{{ asset('vendor/admin-lte/bower_components/raphael/raphael.min.js')}}"></script>
    <script src="{{ asset('vendor/admin-lte/bower_components/morris.js/morris.min.js')}}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('vendor/admin-lte/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js')}}"></script>
    <!-- DataTables -->
    <script src="{{ asset('vendor/admin-lte/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('vendor/admin-lte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <!-- Datatables Button-->
    <script src="{{ asset('vendor/admin-lte/bower_components/datatables.net/js/buttons/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('vendor/admin-lte/bower_components/datatables.net/js/buttons/buttons.flash.min.js')}}"></script>
    <script src="{{ asset('vendor/admin-lte/bower_components/datatables.net/js/buttons/jszip.min.js')}}"></script>
    <script src="{{ asset('vendor/admin-lte/bower_components/datatables.net/js/buttons/pdfmake.min.js')}}"></script>
    <script src="{{ asset('vendor/admin-lte/bower_components/datatables.net/js/buttons/vfs_fonts.js')}}"></script>
    <script src="{{ asset('vendor/admin-lte/bower_components/datatables.net/js/buttons/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('vendor/admin-lte/bower_components/datatables.net/js/buttons/buttons.print.min.js')}}"></script>
    <!-- DataTables CSS-->
    <link rel="stylesheet" href="{{ asset('vendor/admin-lte/bower_components/datatables.net/js/buttons/buttons.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{ asset('vendor/admin-lte/bower_components/datatables.net/js/buttons/jquery.dataTables.min.css')}}"> 
    <!-- Select2 -->
    <script src="{{ asset('vendor/admin-lte/bower_components/select2/dist/js/select2.full.min.js')}}"></script>

    <!-- jvectormap -->
    <script src="{{ asset('vendor/admin-lte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
    <script src="{{ asset('vendor/admin-lte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('vendor/admin-lte/bower_components/jquery-knob/dist/jquery.knob.min.js')}}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('vendor/admin-lte/bower_components/moment/min/moment.min.js')}}"></script>
    <script src="{{ asset('vendor/admin-lte/bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <!-- datepicker -->
    <script src="{{ asset('vendor/admin-lte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{ asset('vendor/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
    <!-- Slimscroll 
    <script src="{{ asset('vendor/admin-lte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>-->
    <!-- FastClick -->
    <script src="{{ asset('vendor/admin-lte/bower_components/fastclick/lib/fastclick.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('vendor/admin-lte/dist/js/adminlte.min.js')}}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('vendor/admin-lte/dist/js/pages/dashboard.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('vendor/admin-lte/dist/js/demo.js')}}"></script>
    <!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience. Slimscroll is required when using the
      fixed layout. -->
      @stack('javascript')
       <script>
      $(document).ready(function() {
        //Date picker
        $('#birthDate').datepicker({
          autoclose: true,
          format: 'yyyy/mm/dd'
        });
        $('#hiredDate').datepicker({
          autoclose: true,
          format: 'yyyy/mm/dd'
        });
        $('#from').datepicker({
          autoclose: true,
          format: 'yyyy/mm/dd'
        });
        $('#to').datepicker({
          autoclose: true,
          format: 'yyyy/mm/dd'
        });
    });
    </script>  
</div>
@stack('scripts')

</body>
</html>