@extends('layouts.main')
@section('title')
    Project | show
@stop

@section('ProjectActive')
    class="active"
@stop

@section('content')
    <div class="col-md-10 offset-1">
        <div class="form-content">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title-3">Project:  <span class="text-danger">{{$project?$project->name:''}}</span></h2>
                </div>
            </div>
            <div class="row m-t-30" style="font-family: 'Poppins', 'sans-serif';font-size: 11pt;">
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-md-5">
                            <p>Number of Localites:</p>
                        </div>
                        <div class="col-md-7">
                            <p class="text-danger font-weight-bold" style="float: left;font-weight: bold;">{{$project?$project->localities()->count():''}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <p>Number of Blocks:</p>
                        </div>
                        <div class="col-md-7">
                            <p class="text-danger font-weight-bold" style="float: left;font-weight: bold;">{{$project?$project->blocks()->count():''}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <p>Number of Plots:</p>
                        </div>
                        <div class="col-md-7">
                            <p class="text-danger font-weight-bold" style="float: left;font-weight: bold;">{{$project?$project->plots()->count():''}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <p>Sold Plots:</p>
                        </div>
                        <div class="col-md-7">
                            <p class="text-danger font-weight-bold" style="float: left;font-weight: bold;">{{$project?$project->soldplots:''}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-6">
                            <p>Income so Far:</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-danger font-weight-bold" style="float: left;font-weight: bold;">{{$project?number_format($project->currentamount):''}}/=</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p>Expected Income:</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-danger font-weight-bold" style="float: left;font-weight: bold;">{{$project?number_format($project->expectedAmount):''}}/=</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-t-20">
                <div class="col-md-12">
                    <h3 class="title-3 m-b-30">Plot Uses</h3>
                    <div class="table-responsive">
                        <table class="table table-top-campaign">
                            <tbody>
                            <tr class="font-weight-bold">
                                <td>NO.</td>
                                <td>Plot Use</td>
                                <td class="text-center">No. of Plot</td>
                                <td class="text-center">Sold</td>
                                <td class="text-center">Available</td>
                                <td>Price @ m<sup>2</sup></td>
                            </tr>
                            @php($num=1)
                            @foreach($plotusesdata as $plotuse)
                                <tr>
                                    <td>{{$num}}</td>
                                    <td>{{$plotuse->name}}</td>
                                    <td class="text-center">{{$plotuse->plot}}</td>
                                    <td class="text-center">{{$plotuse->sold}}</td>
                                    <td class="text-center">{{$plotuse->plot-$plotuse->sold}}</td>
                                    <td>{{number_format($plotuse->price)}}</td>
                                </tr>
                                @php($num++)
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>

        var labels = <?php echo json_encode(array_keys($data)) ?>;
        var data = <?php echo json_encode(array_values($data)) ?>;
        var color = <?php echo json_encode($color) ?>;
        // console.log(labels);
        console.log(color);




        var ctx = document.getElementById('plotDist');
        var myChart = new Chart(ctx, {
            type: 'polarArea',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: color,
                    borderColor: color,
                    borderWidth: 1
                }]
            },
            options: {
                title:{
                    display:false,
                    text:'Plots Distribution',
                    position:'top',
                    fontSize:23,
                    fontStyle:'bold'
                }
            }
        });
    </script>
@stop
