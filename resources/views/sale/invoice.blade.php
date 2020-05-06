<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Print Invoice</title>
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
    <div id="printArea" class="container" style="margin-top:1em;margin-bottom: 1em;">
        <div class="row">
            <div class="col-md-6 offset-3 text-center">
                <h3>HALMASHAURI YA JIJI LA DODOMA</h3>
            </div>
        </div>
        <div class="row"  style="margin-top: 1em;">
            <div class="col-md-4"  style="margin-top: 1em;">
                <p><strong>Simu: 2354817</strong></p>
                <p><strong>Fax: 2354817</strong></p>
                <p>E-mail: <strong><u>md@dodomamc.go.tz</u></strong></p>
                <strong>Kumb. Na. CCD/LD/ </strong>
            </div>
            <div class="col-md-4 text-center">
                <img src="{{url('image/invoicelogo.png')}}" alt="" width="150" height="150">
            </div>
            <div class="col-md-4"  style="margin-top: 1em;">
                <p>Ofisi ya Mkurugenzi,</p>
                <p>Halmashauri ya Jiji,</p>
                <p>S.L.P 1249,</p>
                <p>Idara ya Mipango Miji na Ardhi.</p>
                <p><strong>DODOMA</strong></p>
                <p><strong>Tarehe: {{\Carbon\Carbon::now()->format('d/m/Y')}}</strong></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <p><strong>Control Number:</strong> {{$controlNumber->number}}</p>
                <p><strong>Ndg.</strong> {{$controlNumber->customer->name}}</p>
                <p><strong>Namba ys simu: </strong> {{$controlNumber->customer->phone}}</p>
            </div>
        </div>
        <div class="row" style="margin-bottom: 2em;">
            <div class="col-md-10 offset-1 text-center">
                <p><strong>Yah: KUKUBALIWA KUMILIKISHWA KIWANJA Na. '{{$controlNumber->plot->number}}' KITALU '{{$controlNumber->plot->block->code}}' ENEO LA '{{strtoupper($controlNumber->plot->block->locality->name)}}' CHENYE UKUBWA WA  {{$controlNumber->plot->size}} m<sup>2</sup> JIJI LA DODOMA.</strong></p>
            </div>
        </div>
        <div class="row" style="margin-bottom: 1em;">
            <div class="col-md-12">
                <p>Ombi lako la kumilikishwa kiwanja kilichotajwa hapo juu limekubaliwa.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p>Hivyo unatakiwa ulipie ada na gharama zifuatazo hapo chini iliupatiwe "Ushuhuda wa malipo" (Acknowledgement of Payment) na hatimaye uweze kutayarishiwa Hati ya kumiliki Ardhi ya kiwanja tajwa hapo juu.</p>
            </div>
        </div>
        <div class="row" style="margin-top: 2em;">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S/NO</th>
                            <th>ITEM</th>
                            <th>KIASI</th>
                            <th>AKAUNTI NAMBA</th>
                            <th>JINA LA AKAUNTI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>i.</td>
                            <td>
                                <p>Gharama za utwaaji,</p>
                                <p>Upangaji & Upimaji Ardhi</p>
                            </td>
                            <td>{{number_format($controlNumber->totalRequiredAcq)}}</td>
                            <td class="text-right">
                                @foreach($banks as $bank)
                                    <p style="margin-bottom: 5px;"><strong>{{$bank->account}}, {{$bank->name}}</strong></p>
                                @endforeach
                                <p></p>
                                <P style="margin-top: 2em;">{{number_format($controlNumber->totalRequiredAcq)}}</P>
                            </td>
                            <td class="text-center">
                                <strong>GODWIN LUSASO GENERAL SUPPLY LTD</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>ii.</td>
                            <td>
                                Dodoma City Council
                            </td>
                            <td>
                                {{number_format($controlNumber->dcc)}}
                            </td>
                            <td rowspan="2" >
                                <p style="float: right; margin-top: 1em">{{number_format($controlNumber->dcc+$controlNumber->constant->ramani)}}</p>
                            </td>
                            <td rowspan="2" class="text-center">
                                DMC OWN SOURCE COLLECTION NMB CONTROL NO.
                            </td>
                        </tr>
                        <tr>
                            <td>iii.</td>
                            <td>Ada ya Ramani za Hati</td>
                            <td>{{number_format($controlNumber->constant->ramani)}}</td>
                        </tr>
                        <tr>
                            <td>iv.</td>
                            <td>Maandalizi ya Hati</td>
                            <td>{{number_format($controlNumber->constant->hati)}}</td>
                            <td rowspan="5">
                                <p style=" margin-top: 6em; float: right">
                                    {{number_format($controlNumber->constant->hati+$controlNumber->adaUsajiri+$controlNumber->ushuruSerikali+$controlNumber->kodi+$controlNumber->premium)}}
                                </p>
                            </td>
                            <td rowspan="5" class="text-center">
                                <p style=" margin-top: 6em;">
                                    GePG CONTROL NO
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>v.</td>
                            <td>Ada ya Usajiri</td>
                            <td>{{number_format($controlNumber->adaUsajiri)}}</td>
                        </tr>
                        <tr>
                            <td>vi.</td>
                            <td>Ushuru wa Serikali</td>
                            <td>{{number_format($controlNumber->ushuruSerikali)}}</td>
                        </tr>
                        <tr>
                            <td>vii.</td>
                            <td>
                                <p>Kodi ya toka</p>
                                <p>{{$controlNumber->kodiStart}} hadi</p>
                                <p>{{$controlNumber->kodiEnd}}</p>
                            </td>
                            <td>
                                {{number_format($controlNumber->kodi)}}
                            </td>
                        </tr>
                        <tr>
                            <td>viii.</td>
                            <td>Malipo ya Mbele</td>
                            <td>{{number_format($controlNumber->premium)}}</td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td><strong>JUMLA</strong></td>
                            <td class="text-right"><strong>{{number_format($controlNumber->landValue)}}</strong></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
        <div class="row" style="margin-top: 2em;">
            <div class="col-md-12" >
                <p>
                    kwamba malipo haya yanatakiwa yalipwe ndani ya siku ({{$controlNumber->period->duration}}) kuanzia tarehe ya barua hii na kuwasilisha stakabadhi za malipo,
                    picha sita "Passport Size" na uthibitisho wa uraia. Iwapo hutakuwa umelipa malipo yote ndani ya muda uliotajwa, serikali itakigawa kiwanja
                    hiki kwa muombaji mwingine bila taarifa tena kwako.
                </p>
            </div>
        </div>
        <div class="row" style="margin-top: 1em;" >
            <div class="col-md-4 offset-4 text-center">
                <p>
                    <strong>
                        AFISA ARDHI
                    </strong>
                </p>
                <P>
                    <strong>
                        HALMASHAURI YA JIJI
                    </strong>
                </P>
                <P>
                    <strong>
                        DODOMA
                    </strong>
                </P>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p>
                    Imepokelewa leo Tarehe: ..........................................
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p>
                    Jina:..........................................
                </p>
            </div>
            <div class="col-md-6">
                <p>
                    Sahihi:..........................................
                </p>
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
