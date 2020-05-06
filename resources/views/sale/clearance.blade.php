<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Print Clearance</title>
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
@if($controlNumber)
    <div id="printClearance" class="container" style="margin-top:1em;margin-bottom: 4em;width:210vh;font-family: 'Times New Roman', Times, serif;">
        <div class="row">
            <div class="col-md-4">
                <img src="{{url('/image/logo.png')}}" alt="Logo" style="height: 200px;">
            </div>
            <div class="col-md-8">
                <div class="text-center"style="font-family: Arial,sans-serif;">
                    <p style="font-size: 30pt;font-weight: bold;"><span class="text-info">GODWIN LUSASO</span> CO. <span class="text-danger">LIMITED</span></p>
                    <h2>P.O.BOX 4120 Dodoma</h2>
                    <h2>Tel:+255 737 720 000/+255 622 587 759</h2>
                    <h2><u class="text-info">OFFICIAL RECEIPT</u></h2>
                    <h2><span class="text-muted">TIN: 135-485-225</span></h2>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 1em;">
            <div class="col-md-6">
                <h3>GLL/DCC/L/{{\Carbon\Carbon::now()->format('Y')}}/______________</h3>
            </div>
            <div class="col-md-6">
                <h3 style="float: right;">TAREHE: <u>&nbsp;&nbsp;&nbsp;{{\Carbon\Carbon::now()->format('d/m/Y')}}&nbsp;&nbsp;&nbsp;</u></h3>
            </div>
        </div>
        <div class="row" style="margin-top: 2em;">
            <div class="col-md-6">
                <h3>MKURUGENZI WA JIJI,</h3>
                <h3>HALMASHAURI YA JIJI LA DODOMA,</h3>
                <h3>S.L.P 4120,</h3>
                <h3>DODOMA</h3>
            </div>
        </div>
        <div class="row" style="margin-top: 2em;">
            <div class="col-md-6">
                <h3>MAIONE: Afisa Ardhi Mteule wa Jiji</h3>
            </div>
        </div>
        <div class="row" style="margin-top: 2em;">
            <div class="col-md-12 text-center">
                <h3>YAH: UTHIBITISHO WA KUKAMILISHA MALIPO YA KIWANJA Na.<u>&nbsp;&nbsp;&nbsp;{{$controlNumber->plot->number}}&nbsp;&nbsp;&nbsp;</u> KITALU<u>&nbsp;&nbsp;&nbsp;{{strtoupper($controlNumber->plot->block->code)}}&nbsp;&nbsp;&nbsp;</u> ENEO LA <u>&nbsp;&nbsp;&nbsp;'{{strtoupper($controlNumber->plot->block->locality->name)}}'&nbsp;&nbsp;&nbsp;</u>   JIJI LA DODOMA.</h3>
            </div>
        </div>
        <div class="row" style="margin-top: 2em;">
            <div class="col-md-12">
                <h3>Nathibitisha kuwa ndugu<u>&nbsp;&nbsp;&nbsp;{{ucfirst($controlNumber->customer->name)}}&nbsp;&nbsp;&nbsp;</u> mwenye Control Number '{{$controlNumber->number}}' amekamilisha malipo yote ya kiwanja tajwa hapo juu kama ifuatavyo:-</h3>
            </div>
        </div>
        <div class="row" style="margin-top: 2em;">
            <div class="col-md-12">
                <table class="table table-bordered text-center">
                    <theady>
                        <tr>
                            <th><h3>Na.</h3></th>
                            <th><h3>Maelezo</h3></th>
                            <th><h3>Kiasi (Tshs)</h3></th>
                        </tr>
                    </theady>
                    <tbody>
                        <tr>
                            <td><h3>1</h3></td>
                            <td><h3>Gharama za utwaaji,Upangaj na Upimaji wa Ardhi</h3></td>
                            <td><h3></h3></td>
                        </tr>
                        <tr>
                            <td><h3>2</h3></td>
                            <td><h3>80% ya GODWIN LUSASO CO LIMITED</h3></td>
                            <td><h3>{{number_format($controlNumber->totalRequiredAcq)}}</h3></td>
                        </tr>
                        <tr>
                            <td><h3></h3></td>
                            <td><h3 style="float: right;">JUMLA TSHS</h3></td>
                            <td><h3>{{number_format($controlNumber->totalRequiredAcq)}}</h3></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row" style="margin-top: 2em;">
            <div class="col-md-12">
                <h3>Nawakilisha kwako "Ushuhuda wa Malipo" ili uendelee na taratibu za kuandaa Hati ya kumiliki Aridhi ya kiwanja hiki.</h3>
            </div>
        </div>
        <div class="row" style="margin-top: 2em;">
            <div class="col-md-12">
                <h3><i>Mkurugenzi Mkuu</i></h3>
                <h3><i>GODWIN LUSASO CO. LIMITED</i></h3>
            </div>
        </div>
        <div class="row" style="margin-top: 3em;">
            <div class="col-md-12">
                <h3><i>Nime/Tumekabidhiwa nakala yangu. Sahihi ya mpokeaji:________________________________</i></h3>
            </div>
        </div>
        <div class="row" style="margin-top: 1em;">
            <div class="col-md-12">
                <h3 style="float: right;"><i>Tarehe:_________________________________</i></h3>
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
        // window.print();
    });
    $('#print-btn').click(function () {
        window.print();
    });
</script>
</body>
</html>
