@extends('layouts.main')
@section('title')
    Plot | create
@stop
@section('PlotActive')
    class="active"
@stop

@section('content')
    <div class="row">
        <div class="col-md-5 offset-4">
            @if(Session('message'))
                <div class="sufee-alert alert with-close alert-success alert-dismissible fade show">
                    {{Session('message')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            @endif
        </div>
    </div>
    <div class="row m-t-30">
        <div class="col-md-10 offset-1">
            <div class="form-content" style="margin-bottom: 2em;">
                <div class="row">
                    <div class="col-md-11">
                        <h2 class="font-weight-light" style="margin-bottom: 1em;">New Plot</h2>
                    </div>
                </div>
                {!! Form::open(['route'=>'plot.store'],['id'=>'plot-create-form']) !!}
                <span id="plot_token" hidden>{{csrf_token()}}</span>
                <span id="locality_url" hidden>{{route('locality.get')}}</span>
                <span id="block_url" hidden>{{route('block.get')}}</span>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="company" class=" form-control-label">Project</label>
                            {!! Form::select('project_id', $projects, null,['id'=>'plot_project_id','class'=>'form-control','placeholder'=>'Select Project']) !!}
                            @if($errors->has('project_id'))
                                <small class="text-danger">please select project</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="company" class=" form-control-label">Locality</label>
                            {!! Form::select('locality_id', [], null,['id'=>'plot_locality_id','class'=>'form-control','placeholder'=>'Select Locality','disabled']) !!}
                            @if($errors->has('locality_id'))
                                <small class="text-danger">please select locality</small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="company" class=" form-control-label">Block</label>
                            {!! Form::select('block_id', [], null,['id'=>'plot_block_id','class'=>'form-control','placeholder'=>'Select block','disabled']) !!}
                            @if($errors->has('block_id'))
                                <small class="text-danger">please select block</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="company" class=" form-control-label">Plot Use</label>
                            {!! Form::select('plotuse_id', $plotuses, null,['id'=>'plot_plotuse_id','class'=>'form-control','placeholder'=>'Select plot use']) !!}
                            @if($errors->has('plotuse_id'))
                                <small class="text-danger">please select plot use</small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company" class=" form-control-label">Plot No.</label>
                                {!! Form::number('number','',['class'=>'form-control','id'=>'plot_number','placeholder'=>'Enter Plot number','min'=>'1']) !!}
                                @if($errors->has('number'))
                                    <small class="text-danger">Plot number is required</small>
                                @endif
                                <small id="number_exists" class="text-danger" hidden>Number Exists</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company" class=" form-control-label">Plot Size M<sup>2</sup></label>
                                {!! Form::number('size','',['class'=>'form-control','id'=>'plot_size','placeholder'=>'Enter plot size','min'=>'1']) !!}
                                @if($errors->has('size'))
                                    <small class="text-danger">Project size is required</small>
                                @endif
                            </div>
                        </div>
                    </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="company" class=" form-control-label">Survey Plan No.</label>
                            {!! Form::text('surveyNumber','',['class'=>'form-control','id'=>'plot_surveyNumber','placeholder'=>'Enter survey plan No']) !!}
                            @if($errors->has('surveyNumber'))
                                <small class="text-danger">Survey Plan No. is required</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="company" class=" form-control-label">Reg No.</label>
                            {!! Form::text('registeredNumber','',['class'=>'form-control','id'=>'plot_registeredNumber','placeholder'=>'Enter Reg No.']) !!}
                            @if($errors->has('registeredNumber'))
                                <small class="text-danger">Reg No. is required</small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 offset-9">
                        <button type="submit" class="btn btn-success btn-block btn-lg">Save</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
        //Get localities
        $('#plot_project_id').change(function () {
            var project = $(this).val();
            var url = $('#locality_url').text();
            var token = $('#plot_token').text();
            if(project.trim().length!=0){
                $.ajax({
                    url: url,
                    type: 'POST',
                    data:{
                        project:project
                    },
                    headers: {
                        'X-CSRF-Token': token
                    }
                }).done(function (data) {
                    if(data){
                        // console.log(data);
                        $('#plot_locality_id').empty();
                        $('#plot_locality_id').append("<option value=''>Select Locality</option>");
                        $.each(data,function (index,value) {
                            $('#plot_locality_id').append("<option value='"+index+"'>"+value+"</option>");
                        });

                        $('#plot_locality_id').removeAttr('disabled');
                    }else{
                        noLocalityData();
                    }
                });
            }else{
                noLocalityData();
            }
        });

        function noLocalityData(){
            $('#plot_locality_id').empty();
            $('#plot_locality_id').append("<option>No locality available</option>");
            $('#plot_locality_id').attr('disabled','disabled');
            $('#plot_block_id').empty();
            $('#plot_block_id').append("<option>Select block</option>");
            $('#plot_block_id').attr('disabled','disabled');
        }



        //Get blocks
        $('#plot_locality_id').change(function () {
            var locality = $(this).val();
            var url = $('#block_url').text();
            var token = $('#plot_token').text();
            if(locality.trim().length!=0){
                $.ajax({
                    url: url,
                    type: 'POST',
                    data:{
                        locality:locality
                    },
                    headers: {
                        'X-CSRF-Token': token
                    }
                }).done(function (data) {
                    if(data.count>0){
                        // console.log(data.data);
                        $('#plot_block_id').empty();
                        $('#plot_block_id').append("<option value=''>Select Block</option>");
                        $.each(data.data,function (index,value) {
                            $('#plot_block_id').append("<option value='"+index+"'>"+value+"</option>");
                        });

                        $('#plot_block_id').removeAttr('disabled');
                    }else{
                        noBlockData();
                    }
                });

            }else{
                noBlockData();
            }
        });

        function noBlockData(){
            $('#plot_block_id').empty();
            $('#plot_block_id').append("<option>No block found</option>");
            $('#plot_block_id').attr('disabled','disabled');
        }

        //Get plots in block
        var plots = [];

        $('#plot_block_id').change(function () {
            var block = $(this).val();
            var url = '{{url('/get/create/plot')}}';
            var token = $('#plot_token').text();
            if(block.length!=0){
                $.ajax({
                    url: url,
                    type: 'POST',
                    data:{
                        block:block
                    },
                    headers: {
                        'X-CSRF-Token': token
                    }
                }).done(function (data) {
                    if(data!=null){
                        plots = data;
                    }

                    if($('#plot_number').val().length!=0){
                        checkIfPlotExists($('#plot_number').val());
                    }
                });

            }
        });

        //Check if plots number exists
        $('#plot_number').keyup(function () {
            var number = $(this).val();

            if(number.trim().length===0){
                $("#number_exists").attr('hidden','true');
            }
            checkIfPlotExists(number);
        });

        function checkIfPlotExists(number) {
            $.each(plots,function (index,value) {
                if(plots[index]==number){
                    $("#plot_number").addClass("is-invalid");
                    $("#number_exists").text("Plot number exists").removeClass('text-success').addClass('text-danger').removeAttr("hidden");
                    // console.log("Exists");
                    return false;
                }else{
                    $("#plot_number").removeClass("is-invalid");
                    $("#number_exists").text("Good!").removeClass('text-danger').addClass('text-success');
                }
            });
        }
    </script>
@stop
