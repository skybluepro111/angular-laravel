<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{config('custom.app-name')}} Login</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet"
          type="text/css">
    <link href="{{ asset('assets/dashboard/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/dashboard/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/dashboard/css/core.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/dashboard/css/components.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/dashboard/css/colors.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/dashboard/css/main.css') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    @yield('css')

            <!-- Core JS files -->
    <script type="text/javascript" src="{{ asset('assets/dashboard/js/plugins/loaders/pace.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/dashboard/js/core/libraries/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/dashboard/js/core/libraries/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/dashboard/js/plugins/loaders/blockui.min.js') }}"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="{{ asset('assets/dashboard/js/plugins/visualization/d3/d3.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('assets/dashboard/js/plugins/visualization/d3/d3_tooltip.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('assets/dashboard/js/plugins/forms/styling/switchery.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('assets/dashboard/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('assets/dashboard/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/dashboard/js/plugins/ui/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/dashboard/js/plugins/pickers/daterangepicker.js') }}"></script>

    <script type="text/javascript" src="{{ asset('assets/dashboard/js/core/app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/dashboard/js/pages/dashboard.js') }}"></script>

    <script type="text/javascript" src="{{ asset('assets/dashboard/js/plugins/ui/ripple.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('assets/dashboard/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <!-- /theme JS files -->
</head>

<body class="login-container">

<!-- Main navbar -->
<div class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href="{{url('/')}}"><img src="{{ config('custom.site-logo-path') }}" alt=""></a>

        <ul class="nav navbar-nav pull-right visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
        </ul>
    </div>
</div>
<!-- /main navbar -->


<!-- Page container -->
<div class="page-container">

    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Content area -->
            <div class="content">

                <div>
                    @yield('content')
                </div>


                <!-- Footer -->
                <div class="footer text-muted text-center">
                    &copy; {{date('Y')}} <a href="{{ url('/') }}">{{config('custom.app-name')}}</a>
                </div>
                <!-- /footer -->

            </div>
            <!-- /content area -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

</div>
<!-- /page container -->

</body>
</html>