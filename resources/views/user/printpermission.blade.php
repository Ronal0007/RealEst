<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Print Permission</title>
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

    <style>
        /*@page {*/
        /*    size: A4;*/
        /*    margin: 0;*/
        /*}*/
        @media print {
            /*html, body {*/
            /*    width: 210mm;*/
            /*    height: 297mm;*/
            /*}*/

            #print-btn {
                display: none;
            }
        }
    </style>

</head>
<body>
@if($user)
    <div id="printPermissions" class="container" style="margin-top:1em;margin-bottom: 4em;width:210vh;font-family: 'Times New Roman', Times, serif;font-size: 20pt;">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2>USER PERMISSIONS FORM</h2>
            </div>
        </div>
        <div class="row" style="margin-top: 3em;">
            <div class="col-md-8">
                <div class="row" style="margin-top: 2em;">
                    <div class="col-md-3">
                        <h4><strong style="float:right;">User Name:</strong></h4>
                    </div>
                    <div class="col-md-7">
                        <h4>{{$user->name}}</h4>
                    </div>
                </div>
                <div class="row" style="margin-top: 1em;">
                    <div class="col-md-3">
                        <h4><strong style="float:right;">User Role:</strong></h4>
                    </div>
                    <div class="col-md-7">
                        <h4>{{ucfirst($user->role->name)}}</h4>
                    </div>
                </div>
                <div class="row" style="margin-top: 1em;">
                    <div class="col-md-3">
                        <h4><strong style="float:right;">Gender:</strong></h4>
                    </div>
                    <div class="col-md-7">
                        <h4>{{$user->gender->name}}</h4>
                    </div>
                </div>
                <div class="row" style="margin-top: 1em;">
                    <div class="col-md-3">
                        <h4><strong style="float:right;">Phone#:</strong></h4>
                    </div>
                    <div class="col-md-7">
                        <h4>{{$user->phone}}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <img src="{{url('/image/logo.png')}}" alt="Logo" style="height: 180px;">
            </div>
        </div>
        <div class="row" style="margin-top: 2em;">
            <div class="col-md-8 offset-2">
                <h3>GRANTED PERMISSIONS</h3>
            </div>
        </div>
        <div class="row" style="margin-top: 2em;font-size: 20pt;">
            @php($num=1)
            @foreach($permissions->chunk(10) as $chunk)
                <div class="col-md-3">
                    <table class="table table-borderless">
                        <tbody>
                        @foreach($chunk as $p)
                            <tr>
                                <td><img src="{{url('/image/tick.jpg')}}" alt="" width="20"></td>
                                <td>{{$p}}</td>
                            </tr>
                            @php($num++)
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
        <div style="position: absolute;bottom: 6em;width: 100%; margin-top: 3em;">
            <div class="row" style="margin-bottom: 2em;font-size: 18pt;">
                <div class="col-md-12">
                    <p>I <u>&nbsp;&nbsp;&nbsp;{{$user->name}}&nbsp;&nbsp;&nbsp;</u> agree that I have been given the above mentioned permission(s) to use in the</p>
                    <p> system as a <strong>{{$user->role->name}}</strong> and I will be responsible for any misuse of the given permission(s).</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <p>Name: <strong>{{$user->name}}</strong></p>
                    <p>Signature_______________________</p>
                    <p>Date____________________________</p>
                </div>
                <div class="col-md-6">
                    <p>Authorizer: <strong>{{auth()->user()->name}}</strong> </p>
                    <p>Signature__________________________</p>
                    <p>Date_______________________________</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 2em;">
        <div class="col-md-2 offset-9">
            <button id="print-btn" class="btn btn-primary btn-block">Print</button>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-md-12 text-center">
            <h2 class="text-muted" style="margin-top: 10em;">Please select user first!</h2>
        </div>
    </div>
@endif


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
<script src="{{url('js/print.js')}}"></script>
<script>
    $('document').ready(function () {
        window.print();
    });
    $('#print-btn').click(function () {
        window.print();
    });
</script>
</body>
</html>
