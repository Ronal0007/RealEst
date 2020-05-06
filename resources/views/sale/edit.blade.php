@extends('layouts.main')
@section('title')
    Sale | Edit
@stop
@section('SaleActive')
    active
@stop

@section('content')
    <div class="row m-t-30">
        <div class="col-md-9 offset-2">
            <div class="form-content" style="margin-bottom: 2em;">
                <div class="row">
                    <div class="col-md-11">
                        <h2 class="font-weight-light" style="margin-bottom: 1em;">Edit Sale</h2>
                    </div>
                </div>
                @if(Session('msg'))
                    <div class="row">
                        <div class="col-md-6 offset-3">
                            <div class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
                                {{Session('msg')}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
                {!! Form::model($sale,['route'=>['sale.update',$sale->number],'method'=>'PUT'],['id'=>'plot-sale-edit-form']) !!}
                    <span id="plot_token" hidden>{{csrf_token()}}</span>
                    <span id="locality_url" hidden>{{route('locality.get')}}</span>
                    <span id="block_url" hidden>{{route('block.get')}}</span>
                    <span id="plot_url" hidden>{{route('plot.get')}}</span>
                    <div class="card">
                        @foreach($errors as $error)
                            <small>{{$error}}</small>
                            @endforeach
                        <div class="card-header">Choose Plot</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company" class="font-weight-bold form-control-label">Project</label>
                                        {!! Form::select('project_id', $projects, $sale->plot->block->locality->project->id,['id'=>'plot_project_id','class'=>'form-control','placeholder'=>'Select Project']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company" class="font-weight-bold form-control-label">Locality</label>
                                        {!! Form::select('locality_id', $sale->plot->block->locality->project->localities()->pluck('name','id'), $sale->plot->block->locality->id,['id'=>'plot_locality_id','class'=>'form-control','placeholder'=>'Select Locality']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company" class="font-weight-bold form-control-label">Block</label>
                                        {!! Form::select('block_id', $sale->plot->block->locality->blocks()->pluck('code','id'), $sale->plot->block->id,['id'=>'plot_block_id','class'=>'form-control','placeholder'=>'Select block']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company" class="font-weight-bold form-control-label">Availbale Plot</label>
                                        {!! Form::select('plot_id', $sale->plot->block->plots()->pluck('number','id'), $sale->plot->id,['id'=>'plot_id','class'=>'form-control','placeholder'=>'Select plot']) !!}
                                        @if($errors->has('plot_id'))
                                            <small class="text-danger">please select plot</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="company" class="font-weight-bold form-control-label">Plot Details</label>
                                        @php
                                            $amount = $sale->plot->block->locality->project->projectPrices()->where('plotuse_id',$sale->plot->plotuse->id)->first()->amount;
                                            $plotDetails = "Plot No. ".str_pad($sale->plot->number,3,0,STR_PAD_LEFT)." Block: "
                                                .$sale->plot->block->code." Location: '".$sale->plot->block->locality->name."' Plot use: ".$sale->plot->plotuse->name.
                                                ", Area: ".$sale->plot->size.", Amount: ".number_format(($sale->plot->size*$amount));
                                        @endphp
                                        <textarea id="plot_details" style="font-size: 11pt;font-weight: 500;" cols="30" rows="4" class="form-control" readonly>{{$plotDetails}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">Customer Info</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" class="font-weight-bold">First Name</label>
                                        {!! Form::text('fname',$sale->customer->fname,['id'=>'fname','class'=>'form-control','placeholder'=>'Enter first name']) !!}
                                        @if($errors->has('fname'))
                                            <small class="text-danger">Enter first name</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" class="font-weight-bold">Last Name</label>
                                        {!! Form::text('lname',$sale->customer->lname,['id'=>'lname','class'=>'form-control','placeholder'=>'Enter last name']) !!}
                                        @if($errors->has('lname'))
                                            <small class="text-danger">Enter last name</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" class="font-weight-bold">Gender</label>
                                        {!! Form::select('gender_id',$genders,$sale->customer->gender->id,['id'=>'gender','class'=>'form-control','placeholder'=>'Select gender']) !!}
                                        @if($errors->has('gender_id'))
                                            <small class="text-danger">Select gender</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" class="font-weight-bold">Phone No.</label>
                                        {!! Form::text('phone',$sale->customer->phone,['id'=>'phone','class'=>'form-control','placeholder'=>'Enter phone number']) !!}
                                        @if($errors->has('phone'))
                                            <small class="text-danger">Enter phone number</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" class="font-weight-bold">Jiji Control#(optional)</label>
                                        {!! Form::text('jijiControl',$sale->jijiControl,['id'=>'jijiControl','class'=>'form-control','placeholder'=>'Enter Jiji COntrol Number']) !!}
                                        @if($errors->has('jijiControl'))
                                            <small class="text-danger">Enter Jiji Control#</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" class="font-weight-bold">Created at</label>
                                        {!! Form::date('created_at',$sale->created_at,['id'=>'created_at','class'=>'form-control','placeholder'=>'Enter date created']) !!}
                                        @if($errors->has('created_at'))
                                            <small class="text-danger">Choose date created</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="card">
                    <div class="card-header">Payment Info</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="" class="font-weight-bold">Payment Period</label>
                                    {!! Form::select('payment_period_id',$periods,$sale->period->id,['id'=>'period','class'=>'form-control','placeholder'=>'Select Payment period']) !!}
                                    @if($errors->has('payment_period_id'))
                                        <small class="text-danger">Select Payment Period</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="" class="font-weight-bold">Invoice Constant</label>
                                    {!! Form::select('constant_id',$constants,$sale->constant->id,['id'=>'constant','class'=>'form-control','placeholder'=>'Select Constant']) !!}
                                    @if($errors->has('constant_id'))
                                        <small class="text-danger">Select invoice Constant</small>
                                    @endif
                                </div>
                            </div>
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
        var plotDetails = [];
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
            noBlockData();
        }



        //Get Blocks
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
                        noPlotData();
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
            noPlotData();
        }

        //Get Plots
        $('#plot_block_id').change(function () {
            var block = $(this).val();
            var url = $('#plot_url').text();
            var token = $('#plot_token').text();
            if(block.trim().length!=0){
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
                    // console.log(data);
                    if(data.count>0){
                        plotDetails = data.plotdetails;
                        $('#plot_id').empty();
                        $('#plot_id').append("<option value=''>Select Plot</option>");
                        $.each(data.data,function (index,value) {
                            $('#plot_id').append("<option value='"+value+"'>"+index+"</option>");
                        });
                        $('#plot_id').removeAttr('disabled');
                    }else{
                        noPlotData();
                    }
                });

            }else{
                noPlotData();
            }
        });

        function noPlotData(){
            $('#plot_id').empty();
            $('#plot_id').append("<option>Select plot</option>");
            $('#plot_id').attr('disabled','disabled');
            clearPlotDetails();
        }

        $('#plot_id').change(function () {
            var id = $(this).val();

            if(plotDetails[id]){
                $('#plot_details').text(plotDetails[id]);
            }else{
                clearPlotDetails();
            }
        });

        function clearPlotDetails() {
            $('#plot_details').text('');
        }
    </script>
@stop
