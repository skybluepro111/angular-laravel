<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('custom.app-name') }} @yield('title')</title>

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

<body>

<!-- Main navbar -->
<div class="navbar navbar-inverse bg-indigo">
    <div class="navbar-header">
        <a class="navbar-brand" href="{{url('')}}"><i class="icon-arrow-left5"></i> Back to {{ config('custom.app-name') }}</a>

        <ul class="nav navbar-nav visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
            <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>
    </div>

    <div class="navbar-collapse collapse" id="navbar-mobile">
        <ul class="nav navbar-nav">
            <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>

        <div class="navbar-right">
            <p class="navbar-text"><a href="{{url('logout')}}" style="color: #fff"><i class="icon-switch2"></i> Logout</a></p>
        </div>
    </div>
</div>
<!-- /main navbar -->


<!-- Page container -->
<div class="page-container">

    <!-- Page content -->
    <div class="page-content">

        <!-- Main sidebar -->
        <div class="sidebar sidebar-main sidebar-default">
            <div class="sidebar-content">

                <!-- User menu -->
                <div class="sidebar-user-material">
                    <div class="category-content">
                        <div class="sidebar-user-material-content">
                            @if (Auth::user()->image)
                            <a href="#"><img src="{{ Auth::user()->image }}" class="img-circle img-responsive"
                                             alt=""></a>
                            @endif
                            <h6>{{ Auth::user()->name }}</h6>
                        </div>
                    </div>
                </div>
                <!-- /user menu -->

                <!-- Main navigation -->
                <div class="sidebar-category sidebar-category-visible">
                    <div class="category-content no-padding">
                        <ul class="navigation navigation-main navigation-accordion">

                            <!-- Main -->
                            <li class="navigation-header"><span>Main Menu</span> <i class="icon-menu" title="{{Auth::user()->name}}"></i>
                            </li>
                            <li class="{{Request::is('dashboard') ? 'active' : ''}}"><a href="{{url('dashboard')}}"><i class="icon-home4"></i>
                                    <span>Dashboard</span></a></li>
                            <li>
                                <a href="#"><i class="icon-stack2"></i> <span>Posts</span></a>
                                <ul>
                                    <li class="{{Request::is('dashboard/post/list') ? 'active' : ''}}" ><a href="{{url('dashboard/post/list')}}">See All Posts</a></li>
                                    <li class="{{Request::is('dashboard/post/request/list') ? 'active' : ''}}" ><a href="{{url('dashboard/post/request/list')}}">See All Post Requests</a></li>
                                    <li class="{{Request::is('dashboard/post') ? 'active' : ''}}"><a href="{{url('dashboard/post')}}">Add New Post</a></li>
                                    @if(Auth::user()->type == \App\Models\UserType::Administrator)
                                        <li class="{{Request::is('dashboard/post/request') ? 'active' : ''}}"><a href="{{url('dashboard/post/request')}}">Add New Request</a></li>
                                    @endif
                                </ul>
                            </li>
                            <li class="{{Request::is('dashboard/analytics') ? 'active' : ''}}"><a href="{{url('dashboard/analytics')}}"><i class="icon-home4"></i>
                                    <span>Analytics</span></a></li>
                            <li>
                            @if(Auth::user()->type == 1)
                                <li>
                                    <a href="#"><i class="icon-user"></i> <span>Users</span></a>
                                    <ul>
                                        <li class="{{Request::is('dashboard/user/list') ? 'active' : ''}}" ><a href="{{url('dashboard/user/list')}}">See All Users</a></li>
                                        <li class="{{Request::is('dashboard/user') ? 'active' : ''}}"><a href="{{url('dashboard/user')}}">Add New User</a></li>
                                    </ul>
                                </li>
                            @endif
                            <li class="navigation-header"><span>My Settings</span> <i class="icon-menu" title="{{Auth::user()->name}}"></i>
                            </li>
                            <li class="{{Request::is('dashboard/user/settings') ? 'active' : ''}}"><a href="{{url('dashboard/user/settings')}}"><i class="icon-cog"></i>
                                    <span>Settings</span></a></li>
                            <li>
                        </ul>
                    </div>
                </div>
                <!-- /main navigation -->

            </div>
        </div>
        <!-- /main sidebar -->

        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Page header -->
            <div class="page-header page-header-default">
                <div class="page-header-content">
                    <div class="page-title">
                        <h4><i class="icon-home position-left"></i> <span class="text-semibold">Dashboard</span>
                            @yield('title')
                        </h4>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <!-- /page header -->

            <!-- Content area -->
            <div class="content">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')

                <!-- Footer -->
                <div class="footer text-muted">
                    &copy; 2016 {{config('custom.app-name')}}
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
<!--js bottom-->
@include('partials.outputsjs')
@yield('js-bottom')
</body>
</html>
