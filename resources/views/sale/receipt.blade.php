<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Print Receipt</title>
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
@if($payment)
    <div id="printReceipt" class="container" style="margin-top:1em;margin-bottom: 4em;width:210vh;">
        <div class="row">
            <div class="col-md-5">
                <img src="{{url('/image/logo.png')}}" alt="Logo" style="height: 200px;">
            </div>
            <div class="col-md-7">
                <div class="text-center">
                    <h2><span class="text-info">GODWIN LUSASO</span> CO. <span class="text-danger">LIMITED</span></h2>
                    <h2>P.O.BOX 4120 Dodoma</h2>
                    <h2>Tel:+255 737 720 000/+255 622 587 759</h2>
                    <h2><u class="text-info">OFFICIAL RECEIPT</u></h2>
                    <h2><span class="text-muted">TIN: 135-485-225</span></h2>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 4em;">
            <div class="col-md-6">
                <h2 class="text-info">Date: <u style="text-decoration-style: dotted;">&nbsp;&nbsp;&nbsp;{{$payment->created_at->format('d/m/Y')}}&nbsp;&nbsp;&nbsp;</u></h2>
            </div>
            <div class="col-md-6" style="padding-right: 5em;">
            <h2 style="float: right;"><span class="text-info">No.</span>{{str_pad($payment->id,4,0,STR_PAD_LEFT)}}</h2>
            </div>
        </div>
        <div class="row" style="margin-top: 2em;">
            <div class="col-md-12">
                <h2>Received from: <u style="text-decoration-style: dotted;">&nbsp;&nbsp;&nbsp;{{strtoupper($payment->depositor)}}&nbsp;&nbsp;&nbsp;</u> </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h2 style="margin-top: 1em;line-height: 2em;">The Sum of Tshs: <u style="text-decoration-style: dotted;">&nbsp;&nbsp;&nbsp;{{strtoupper($numberTransformer->toWords($payment->amount))}} ONLY &nbsp;&nbsp;&nbsp;</u> </h2>
            </div>
        </div>
        <div class="row" style="margin-top: 2em;">
            <div class="col-md-12">
                <h2>Being payments for: <u style="text-decoration-style: dotted;">&nbsp;&nbsp;&nbsp;{{$payment->controlNumber->plotDetails}}&nbsp;&nbsp;&nbsp;</u> </h2>
            </div>
        </div>
        <div class="row" style="margin-top: 1em;">
            <div class="col-md-7">
                <div class="cash-box">
                    <h2>Cash Tshs. {{number_format($payment->amount)}}</h2>
                </div>
            </div>
            <div class="col-md-5">
                <h2>Chq. NO........................</h2>
                <h2>With Thans</h2>
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
            <h2 class="text-muted" style="margin-top: 10em;">Please get control Number first!</h2>
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
