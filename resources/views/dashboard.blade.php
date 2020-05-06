@extends('layouts.main')
@section('title')
    Dashboard
@stop
@section('DashboardActive')
    class="active"
@stop
@section('content')
    <div class="row m-t-25">
        <div class="col-sm-6 col-lg-3">
            <div class="overview-item overview-item--c1">
                <div class="overview__inner">
                    <div class="overview-box clearfix">
                        <div class="icon">
                            <i class="zmdi zmdi-book"></i>
                        </div>
                        <div class="text">
                            <h2>{{number_format($cards['projects'])}}</h2>
                            <span>Projects</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="overview-item overview-item--c2">
                <div class="overview__inner">
                    <div class="overview-box clearfix">
                        <div class="icon">
                            <i class="fa fa-box"></i>
                        </div>
                        <div class="text">
                            <h2>{{number_format($cards['plots'])}}</h2>
                            <span>Plots</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="overview-item overview-item--c3">
                <div class="overview__inner">
                    <div class="overview-box clearfix">
                        <div class="icon">
                            <i class="zmdi zmdi-money-box"></i>
                        </div>
                        <div class="text">
                            <h2>{{number_format($cards['sold'])}}</h2>
                            <span>Sold</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="overview-item overview-item--c4">
                <div class="overview__inner">
                    <div class="overview-box clearfix">
                        <div class="icon">
                            <i class="zmdi zmdi-airplay"></i>
                        </div>
                        <div class="text">
                            <h2>{{number_format($cards['available'])}}</h2>
                            <span>Available</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 2em;">
        <div class="col-md-5">
        @if($data['project']!=null)
            <div class="top-campaign" style="">
                <h3 class="title-3 m-b-30 font-weight-light">Current Projects Income</h3>
                <table class="table table-top-campaign">
                    <tbody>
                    <tr class="font-weight-bold">
                        <td>No. Project Name</td>
                        <td>Amount</td>
                    </tr>
                    @php($num=1)
                    @foreach($data['project'] as $project => $income)
                        @if($project=='Suspences')
                            <?php $suspence=$project; $amount=$income; ?>
                            @continue
                        @endif
                        <tr>
                            <td>{{$num}}. {{$project}}</td>
                            <td>{{number_format($income)}}</td>
                        </tr>
                        @php($num++)
                    @endforeach
                        <tr>
                            <td class="text-primary text-muted" style="font-size: 15pt;">   {{$suspence}}</td>
                            <td style="font-size: 15pt;">{{number_format($amount)}}</td>
                        </tr>                    
                    </tbody>
                </table>
            </div>
        @endif
        </div>
        @if($data['plotUseName']!=null)
        <div class="col-md-7">
            <div class="dashboard-chart">
                <div class="title">
                    <h3 class="text-center font-weight-light">Plot Distribution</h3>
                    <canvas id="plotDist" width="400" height="300" aria-label="Plot DIstribution" role="img"></canvas>
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="row m-t-30">
        <div class="col-md-5">
            <div class="top-campaign" style="padding-bottom: 2em;">
                <h3 class="title-3 m-b-30 font-weight-light">Recent User Logs</h3>
                <table class="table table-top-campaign">
                    <tbody>
                    <tr>
                        <td>No. Name</td>
                        <td>Event</td>
                        <td>Time</td>
                    </tr>
                    @php($num=1)
                    @foreach($userLogs as $log)
                        <tr>
                            <td>{{$num}}. {{$log->user->name}}</td>
                            <td>{{$log->description}}</td>
                            <td>{{$log->logged_at->diffForHumans()}}</td>
                        </tr>
                        @php($num++)
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-7">
            <div class="top-campaign" style="padding-bottom: 2em;">
                <h3 class="title-3 m-b-30 font-weight-light">Recent Logs</h3>
                <table class="table table-top-campaign">
                    <tbody>
                    <tr>
                        <td>No. Name</td>
                        <td>Event</td>
                        <td>User</td>
                        <td>Time</td>
                    </tr>
                    @php($num=1)
                    @foreach($otherLogs as $log)
                        <tr>
                            <td>{{$num}}. {{$log->name}}</td>
                            <td>{{$log->description}}</td>
                            <td>{{$log->user->name}}</td>
                            <td>{{$log->logged_at->diffForHumans()}}</td>
                        </tr>
                        @php($num++)
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>

        var data = <?php echo json_encode($data)?>;
        console.log(data);




        var ctx = document.getElementById('plotDist');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.plotUseName,
                datasets: [{
                    label: 'Plots',
                    data: data.plotUseCount,
                    backgroundColor: "transparent",
                    pointBackgroundColor:data.color,
                    pointRadius:5,
                    borderColor: '#b80f26',
                    borderWidth: 1
                }]
            },
            options: {
                legend:{
                    display:false
                }
            }
        });
    </script>
@stop
