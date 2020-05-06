<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">
    <title>Login</title>
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
    <div class="login-page-container">
        <div class="main-content">
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 offset-4">
                            <div class="m-b-40 text-center">
                                <img src="{{url('/image/logo.png')}}" alt="Logo" width="300" height="300">
                            </div>
                            <div class="login-form">
                                <h4 class="text-center text-danger m-b-30">Enter your Credentials</h4>
                                {!! Form::open(['route'=>'login'],['id'=>'login-form']) !!}
                                @if($errors->has('email') || $errors->has('password'))
                                    <small class="text-danger">Invalid email or password</small>
                                @endif
                                    <div class="form-group">
                                        <div class="input-group input-group-sm">
                                            <div class="login-input-group-addon">
                                                <span>Email</span>
                                            </div>
                                            <input type="email" id="email" name="email" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="login-input-group-addon">
                                                <span>Password</span>
                                            </div>
                                            <input type="password" autocomplete="off" id="password" name="password"  class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4 offset-8">
                                                <button type="submit" class="btn btn-danger btn-block">Login</button>
                                            </div>
                                        </div>
                                    </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MAIN CONTENT-->
        <!-- END PAGE CONTAINER-->
    </div>

</div>

<!-- Jquery JS-->
<script src="vendor/jquery-3.2.1.min.js"></script>
<!-- Bootstrap JS-->
<script src="vendor/bootstrap-4.1/popper.min.js"></script>
<script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
<!-- Vendor JS       -->
<script src="vendor/slick/slick.min.js">
</script>
<script src="vendor/wow/wow.min.js"></script>
<script src="vendor/animsition/animsition.min.js"></script>
<script src="vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
</script>
<script src="vendor/counter-up/jquery.waypoints.min.js"></script>
<script src="vendor/counter-up/jquery.counterup.min.js">
</script>
<script src="vendor/circle-progress/circle-progress.min.js"></script>
<script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="vendor/chartjs/Chart.bundle.min.js"></script>
<script src="vendor/select2/select2.min.js">
</script>

<!-- Main JS-->
<script src="js/main.js"></script>

</body>

</html>
<!-- end document-->
