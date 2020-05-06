<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>@yield('title')</title>
    <link rel="icon" href="{{url('/image/logo.png')}}">
    <link href="{{url('css/font-face.css')}}" rel="stylesheet" media="all">
    <link href="{{url('vendor/font-awesome-4.7/css/font-awesome.min.css')}}" rel="stylesheet" media="all">
    <link href="{{url('vendor/font-awesome-5/css/fontawesome-all.min.css')}}" rel="stylesheet" media="all">
    <link href="{{url('vendor/mdi-font/css/material-design-iconic-font.min.css')}}" rel="stylesheet" media="all">
    <link href="{{url('vendor/bootstrap-4.1/bootstrap.min.css')}}" rel="stylesheet" media="all">
    <link href="{{url('vendor/animsition/animsition.min.css')}}" rel="stylesheet" media="all">
    <link href="{{url('vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css')}}" rel="stylesheet" media="all">
    <link href="{{url('vendor/wow/animate.css')}}" rel="stylesheet" media="all">
    <link href="{{url('vendor/css-hamburgers/hamburgers.min.css')}}" rel="stylesheet" media="all">
    <link href="{{url('vendor/slick/slick.css')}}" rel="stylesheet" media="all">
    <link href="{{url('vendor/select2/select2.min.css')}}" rel="stylesheet" media="all">
    <link href="{{url('vendor/perfect-scrollbar/perfect-scrollbar.css')}}" rel="stylesheet" media="all">
    <link href="{{url('css/theme.css')}}" rel="stylesheet" media="all">

</head>

<body>
<div class="page-wrapper">

    <aside class="menu-sidebar d-none d-lg-block">

        <div class="menu-sidebar__content js-scrollbar1">
            <div class="mylogo text-center">
                <a href="{{route('home')}}"><img src="{{url('/image/logo.png')}}" width="200" alt="Logo"></a>
            </div>
            <nav class="navbar-sidebar">
                <ul class="list-unstyled navbar__list">
                    @if(auth()->user()->role->name=='admin')
                        <li @yield('DashboardActive')>
                            <a href="{{route('dashboard')}}">
                                <i class="fa fa-tachometer-alt"></i>Dashboard</a>
                        </li>
                    @endif
                    @if(auth()->user()->granted('project_view'))
                            <li @yield('ProjectActive')>
                                <a href="{{route('project.index')}}">
                                    <i class="zmdi zmdi-book"></i>Project</a>
                            </li>
                    @endif
                    @if(auth()->user()->granted('locality_view'))
                        <li @yield('LocalityActive')>
                            <a href="{{route('locality.index')}}">
                                <i class="zmdi zmdi-my-location"></i>Locality</a>
                        </li>
                    @endif
                    @if(auth()->user()->granted('block_view'))
                        <li @yield('BlockActive')>
                            <a href="{{route('block.index')}}">
                                <i class="zmdi zmdi-airline-seat-individual-suite"></i>Block</a>
                        </li>
                    @endif
                    @if(auth()->user()->granted('plot_view'))
                        <li @yield('PlotActive')>
                            <a href="{{route('plot.index')}}">
                                <i class="zmdi zmdi-crop-square"></i>Plot</a>
                        </li>
                    @endif
                    @if(auth()->user()->granted('control_view'))
                        <li class="has-sub  @yield('SaleActive')">
                            <a class="js-arrow" href="#">
                                <i class="zmdi zmdi-shopping-cart"></i>Sale &nbsp;&nbsp;<i class="fa fa-angle-right"></i> </a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="{{route('sale.index')}}" style="font-size: 11pt;padding-left: 1em;">view sale</a>
                                </li>
                                @if(auth()->user()->granted('control_add'))
                                    <li>
                                        <a href="{{route('sale.create')}}" style="font-size: 11pt;padding-left: 1em;">new sale</a>
                                    </li>
                                    <li>
                                        <a href="{{route('sale.suspence')}}" style="font-size: 11pt;padding-left: 1em;">Suspences
                                            @if((\App\Suspence::all()->count())>0)
                                                <span style="margin-left: 1em;" class="badge badge-danger">{{\App\Suspence::all()->count()}}</span>
                                            @endif
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(auth()->user()->granted('defaulter_view'))
                        <li class="has-sub  @yield('DefaultActive')">
                            <a class="js-arrow" href="#">
                                <i class="zmdi zmdi-block"></i>Defaulters
                                @if((\App\ControlNumber::yearDefaulter() + \App\ControlNumber::monthDefaulter())>0)
                                    <span style="margin-left: 4px;" class="badge badge-danger">{{\App\ControlNumber::yearDefaulter() + \App\ControlNumber::monthDefaulter()}}</span>
                                @endif
                                <i class="fa fa-angle-right"></i> </a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="{{route('sale.defaulter','90')}}" style="font-size: 11pt;">90 days
                                    @if((\App\ControlNumber::monthDefaulter())>0)
                                        <span style="margin-left: 1em;" class="badge badge-danger">{{\App\ControlNumber::monthDefaulter()}}</span>
                                    @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('sale.defaulter','360')}}" style="font-size: 11pt;">360 days
                                        @if((\App\ControlNumber::yearDefaulter())>0)
                                            <span style="margin-left: 1em;" class="badge badge-danger">{{\App\ControlNumber::yearDefaulter()}}</span>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @if(auth()->user()->granted('user_view'))
                        <li  @yield('UserActive')>
                            <a href="{{route('user.index')}}">
                                <i class="fa fa-user"></i>User</a>
                        </li>
                    @endif
                    @if(auth()->user()->granted('constant_view'))
                        <li  @yield('CostantActive')>
                            <a href="{{route('constant.index')}}">
                                <i class="zmdi zmdi-code-setting"></i>Constants</a>
                        </li>
                    @endif
                    @if(auth()->user()->granted('log_view'))
                            <li  @yield('LogActive')>
                                <a href="{{route('log.index')}}">
                                    <i class="zmdi zmdi-view-list"></i>Logs</a>
                            </li>
                    @endif
                </ul>
            </nav>
        </div>
    </aside>

    <div class="page-container">
        <header class="header-desktop">
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    <div id="user-profile">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" style="font-size: 11pt;">
                                {{auth()->user()->name}} &nbsp;&nbsp;
                            </a>
                            <div class="dropdown-menu" style="font-size: 10pt;">
                                <a class="dropdown-item" href="{{route('user.password',auth()->user()->slug)}}">Change password</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{route('logout')}}"><i class="fa fa-power-off"></i> Logout</a>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </header>

        <div class="main-content">
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

</div>

@yield('modal')

<script src="{{url('vendor/jquery-3.2.1.min.js')}}"></script>
<script src="{{url('vendor/bootstrap-4.1/popper.min.js')}}"></script>
<script src="{{url('vendor/bootstrap-4.1/bootstrap.min.js')}}"></script>
<script src="{{url('vendor/slick/slick.min.js')}}"></script>
<script src="{{url('vendor/wow/wow.min.js')}}"></script>
<script src="{{url('vendor/bootstrap-progressbar/bootstrap-progressbar.min.js')}}"></script>
<script src="{{url('vendor/counter-up/jquery.waypoints.min.js')}}"></script>
<script src="{{url('vendor/counter-up/jquery.counterup.min.js')}}"></script>
<script src="{{url('vendor/circle-progress/circle-progress.min.js')}}"></script>
<script src="{{url('vendor/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
<script src="{{url('vendor/chartjs/Chart.bundle.min.js')}}"></script>
<script src="{{url('vendor/select2/select2.min.js')}}"></script>
<script src="{{url('js/main.js')}}"></script>
<script src="{{url('js/scripts.js')}}"></script>
<script src="{{url('js/chart.js')}}"></script>
@yield('script')
</body>

</html>
