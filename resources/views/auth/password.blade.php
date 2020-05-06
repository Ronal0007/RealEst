<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">


    <title>Change Password</title>


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
        <header class="login-header-desktop">
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    <div class="header-wrap">
                        <div class="header-button">
{{--                            Login button--}}
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- HEADER DESKTOP-->

        <!-- MAIN CONTENT-->
        <div class="main-content">
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    <div class="row" style="margin-top: 9em;">
                        <div class="col-md-5 offset-3">
                            <div class="form-content">
                                <h3 class="text-center" style="margin-bottom: 1em;">Change Password</h3>
                                {!! Form::model($user,['route'=>['user.password.change',$user->slug]]) !!}
                                @if($errors->count()>0)
                                    <small class="text-danger">Password don't Match</small>
                                @endif
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="login-input-group-addon">
                                                <span style="font-size: small">New Password</span>
                                            </div>
                                            <input type="password" id="password" name="password" placeholder="New password" class="form-control form-control-sm" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="login-input-group-addon">
                                                <span style="font-size: small">Confirm</span>
                                            </div>
                                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" class="form-control form-control-sm" required>

                                        </div>
                                    </div>
                                    <div class="form-group text-center">
                                        <button class="btn btn-success btn-lg">Submit</button>
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
