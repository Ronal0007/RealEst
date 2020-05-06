@extends('layouts.main')
@section('title')
    Log | index
@stop
@section('LogActive')
    class="active"
@stop

@section('content')
    <div class="row" style="margin-bottom: 2em;">
        <div class="col-md-8">
            <h2 class="font-weight-light">Logs</h2>
        </div>
        <div class="col-md-5">
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
    <div class="row">
        <div class="col-md-12">
            <div class="form-content">
                {!! Form::open(['route'=>'log.index'],['id'=>'filter-log-form']) !!}
                <div class="row">
                    <div class="col-md-4">
                        <label>User:</label>
                        {!! Form::select('slug',$users,null,['class'=>'form-control']) !!}
                    </div>
                    <div class="col-md-4">
                        <label for="from">From</label>
                        <input id="from" type="date" name="from" class="form-control" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}" max="{{\Carbon\Carbon::now()->format('Y-m-d')}}">
                        @if($errors->has('from'))
                            <small class="text-danger">Choose date</small>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <label for="to">To</label>
                        <input type="date" id="to" name="to" class="form-control" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}" min="{{\Carbon\Carbon::now()->format('Y-m-d')}}" >
                        @if($errors->has('to'))
                            <small class="text-danger">Choose date</small>
                        @endif
                    </div>
                </div>
                <div class="row" style="margin-top: 1em;margin-bottom: 1em;">
                    <div class="col-md-3 offset-9">
                        <button type="submit" class="btn btn-success btn-block">Submit</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            @if(!empty($logs))
                <div class="row">
                    <div class="col-md-12">
                        <div class="top-campaign" style="padding-bottom: 2em;margin-top: 2em;">
                            <h3 class="title-3 m-b-30 font-weight-light">{{$header}} found ({{$logs->total()}})</h3>
                            <table class="table table-top-campaign">
                                <tbody>
                                <tr class="font-weight-bold">
                                    <td>No. Name</td>
                                    <td>Event</td>
                                    <td>User</td>
                                    <td>Time</td>
                                </tr>
                                @php($num=$logs->firstItem())
                                @foreach($logs as $log)
                                    <tr>
                                        <td>{{$num}}. {{$log->name}}</td>
                                        <td>{{$log->description}}{{$log->subject_id?" -> {".$log->subject_id."}":''}}</td>
                                        <td>{{$log->user->name}}</td>
                                        <td>{{$log->logged_at->format('D d-m-Y H:i:s')}}</td>
                                    </tr>
                                    @php($num++)
                                @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-8 offset-2">
                                    {{$logs->links()}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="row m-t-30">
    </div>
@stop
