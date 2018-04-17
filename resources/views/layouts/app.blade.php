{{--
/**
 * @package     ST-AsterTools
 * @subpackage  resources/views/layouts
 * @author      Servitux Servicios Informáticos, S.L.
 * @copyright   (C) 2017 - Servitux Servicios Informáticos, S.L.
 * @license     http://www.gnu.org/licenses/gpl-3.0-standalone.html
 * @link        https://www.servitux.es - https://www.servitux-app.com - https://www.servitux-voip.com
 *
 * This file is part of ST-AsterTools.
 *
 * ST-AsterTools is free software: you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any later
 * version.
 *
 * ST-AsterTools is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * ST-AsterTools. If not, see http://www.gnu.org/licenses/.
 */
 --}}

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name') }} | @yield('title')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{ config('adminlte.path') }}/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{ config('adminlte.path') }}/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ config('adminlte.path') }}/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{ config('adminlte.path') }}/dist/css/skins/_all-skins.min.css">
  <!-- iCheckBox -->
  <link rel="stylesheet" href="{{ config('adminlte.path') }}/plugins/iCheck/square/blue.css">
  <!-- Bootstrap Toggle -->
  <link rel="stylesheet" href="{{ config('adminlte.path') }}/plugins/bootstrap-toggle/css/bootstrap-toggle.min.css" rel="stylesheet">
  <!-- Bootstrap Notify (animate.css)-->
  <link rel="stylesheet" href="{{ config('adminlte.path') }}/plugins/bootstrap-notify/animate.css" rel="stylesheet">
  <!-- Notify Animations -->
  <link href='//cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.0/animate.min.css' rel='stylesheet'>
  <style> [data-notify='progressbar'] { margin-bottom: 0px; position: absolute; bottom: 0px; left: 0px; width: 100%; height: 5px; }</style>
  <!-- DataTables -->
  <link rel='stylesheet' href='{{ config('adminlte.path') }}/plugins/datatables/jquery.dataTables.min.css'>
  <link rel='stylesheet' href='{{ config('adminlte.path') }}/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css'>
  <link rel='stylesheet' href='{{ config('adminlte.path') }}/plugins/datatables/extensions/Responsive/css/dataTables.responsive.css'>
  <!-- CSS Servitux -->
  <link rel='stylesheet' href='{{ asset('css/servitux.css') }}'>
  <!-- CSS AlertBox Servitux -->
  <link rel='stylesheet' href='{{ asset('css/sweetalert2.min.css') }}'>

  @yield('css_links')
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Scripts -->
  <script>
      window.Laravel = <?php echo json_encode([
          'csrfToken' => csrf_token(),
      ]); ?>
  </script>
</head>
@if (Auth::check())
<body class="hold-transition {{ Auth::user()->layout_skin }} sidebar-mini{{ Auth::user()->layout_sidebar_collapsed ? ' sidebar-collapse' : '' }} ">
@else
<body class="hold-transition skin-yellow sidebar-mini">
@endif
  <div class="wrapper">
    @include('home.header')
    @include('home.navbar')

    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          @yield('title')
        </h1>
        <div class="row" style="margin-top: 20px">
          <div class="col-md-12">
            @yield('buttons')
          </div>
        </div>
        <ol class="breadcrumb">
          @yield('breadcrumb')
        </ol>
      </section>

      @yield('content')
    </div>

    @include('home.footer')
    @include('home.config')
  </div>

  <!-- jQuery 2.2.3 -->
  <script src="{{ config('adminlte.path') }}/plugins/jQuery/jquery-2.2.3.min.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="{{ config('adminlte.path') }}/bootstrap/js/bootstrap.min.js"></script>
  <!-- FastClick -->
  <script src="{{ config('adminlte.path') }}/plugins/fastclick/fastclick.js"></script>
  <!-- AdminLTE App -->
  <script src="{{ config('adminlte.path') }}/dist/js/app.min.js"></script>
  <!-- Sparkline -->
  <script src="{{ config('adminlte.path') }}/plugins/sparkline/jquery.sparkline.min.js"></script>
  <!-- jvectormap -->
  <script src="{{ config('adminlte.path') }}/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
  <script src="{{ config('adminlte.path') }}/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
  <!-- SlimScroll 1.3.0 -->
  <script src="{{ config('adminlte.path') }}/plugins/slimScroll/jquery.slimscroll.min.js"></script>
  <!-- ChartJS 1.0.1 -->
  <script src="{{ config('adminlte.path') }}/plugins/chartjs/Chart.min.js"></script>
  <!-- iCheck 1.0.1 -->
  <script src="{{ config('adminlte.path') }}/plugins/iCheck/icheck.min.js"></script>
  <!-- Bootstrap Toggle -->
  <script src="{{ config('adminlte.path') }}/plugins/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>
  <!-- Bootstrap Notify -->
  <script src="{{ config('adminlte.path') }}/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
  <!-- DataTables -->
  <script src='{{ config('adminlte.path') }}/plugins/datatables/jquery.dataTables.min.js'></script>
  <script src='{{ config('adminlte.path') }}/plugins/datatables/dataTables.bootstrap.min.js'></script>
  <script src='{{ config('adminlte.path') }}/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js'></script>
  <script src='{{ config('adminlte.path') }}/plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js'></script>
  <!-- AlertBox -->
  <script src='{{ asset('js/sweetalert2.min.js') }}'></script>
  <!-- scripts generales -->
  @yield('script_links')

  <script>
  @yield('script')

  @foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has('alert-' . $msg))
      $.notify({ message: $('<textarea />').html("{{Session::get("alert-$msg")}}").text() },{ type: "{{ $msg }}", placement: { from: "top", align: "center" } });
    @endif
  @endforeach

  $('.sidebar-toggle').on('click', function() {
    var cls = $('body').attr('class');
    if (cls.indexOf('sidebar-collapse') == -1)
    {
      $('.servitux-logo').addClass('collapse');
      $('.servitux-logo-mini').removeClass('collapse');
    }
    else
    {
      $('.servitux-logo').removeClass('collapse');
      $('.servitux-logo-mini').addClass('collapse');
    }
  });
  </script>

  <!-- documento load jquery -->
  <script>
    $(document).ready(function() {
      @yield('jquery_onload')
    });
  </script>

  <!-- Home -->
  <script src="{{ url('js/home.js') }}"></script>
</body>
</html>
