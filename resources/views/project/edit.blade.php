@extends('layouts.main')
@section('title')
    Project | edit
@stop
@section('ProjectActive')
    class="active"
@stop

@section('content')
    <div class="row">
        <div class="col-md-11 offset-1">
            <h2>Edit Project</h2>
        </div>
    </div>
    <div class="row m-t-30">
        <div class="col-md-11 offset-1">
            <div class="form-content">
                {!! Form::model($project,['route'=>['project.update',$project->slug],'method'=>'PUT'],['id'=>'project-edit-form']) !!}
                <div class="card">
                    <div class="card-header">Project Info</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company" class="font-weight-bold form-control-label">Project Name</label>
                                    {!! Form::text('name',$project->name,['class'=>'form-control','id'=>'name','placeholder'=>'Enter project name']) !!}
                                    @if($errors->has('name'))
                                        <small class="text-danger">Project name is required</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company" class="font-weight-bold form-control-label">Status</label>
                                    {!! Form::select('status_id', $statuses, $project->status->id,['class'=>'form-control']) !!}
                                    @if($errors->has('status_id'))
                                        <small class="text-danger">please select status</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="company" class="font-weight-bold form-control-label">Project Acquisition Factor</label>
                                    {!! Form::number('acqfactor',$project->acqfactor,['class'=>'form-control','id'=>'acqfactor','placeholder'=>'Enter Acquisition Factor','min'=>1]) !!}
                                    @if($errors->has('acqfactor'))
                                        <small class="text-danger">Value is required</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 offset-2">
                                <div class="form-group">
                                    <label for="company" class="font-weight-bold form-control-label">Project DMC Factor</label>
                                    {!! Form::number('dmcfactor',$project->dmcfactor,['class'=>'form-control','id'=>'dmcfactor','placeholder'=>'Enter DMC Factor','max'=>1,'min'=>0.01,'step'=>0.01]) !!}
                                    @if($errors->has('dmcfactor'))
                                        <small class="text-danger">Value is required</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Project Constants</div>
                    <div class="card-body">
                        @php($projectPrices = $project->projectPrices()->get())
                        @if(count($projectPrices)>0)
                            @foreach ($projectPrices->chunk(2) as $chunk)
                                <div class="row">
                                    @foreach ($chunk as $projectPrice)
                                        <div class="col-md-6">
                                            <label class="font-weight-bold">{{$projectPrice->plotuse->name}}</label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{--                                                    <label for="company" class=" form-control-label">Amount</label>--}}
                                                        {!! Form::number(str_replace(' ','_',str_replace('/','_',$projectPrice->plotuse->name)).'_amount',$projectPrice->amount,['class'=>'form-control amounts','id'=>'name','min'=>0,'placeholder'=>'amount']) !!}
                                                        @if($errors->has(str_replace(' ','_',str_replace('/','_',$projectPrice->plotuse->name)).'_amount'))
                                                            <small class="text-danger">Value is required</small>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        {{--                                                    <label for="company" class=" form-control-label">Rate</label>--}}
                                                        {!! Form::number(str_replace(' ','_',str_replace('/','_',$projectPrice->plotuse->name)).'_rate',$projectPrice->rate,['class'=>'form-control rates','id'=>'name','min'=>1,'placeholder'=>'rate','max'=>99]) !!}
                                                        @if($errors->has(str_replace(' ','_',str_replace('/','_',$projectPrice->plotuse->name)).'_rate'))
                                                            <small class="text-danger">Value is required</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 offset-9">
                        <button type="submit" class="btn btn-success btn-block btn-lg">Submit</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop