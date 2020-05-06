@extends('layouts.main')
@section('title')
    Constant | index
@stop
@section('CostantActive')
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
    <div class="row">
        <div class="col-md-8">
            <h2 class="font-weight-light">Invoice Constant</h2>
        </div>
        @if(auth()->user()->granted('constant_add') || auth()->user()->role->name=='admin')
            <div class="col-md-2 offset-2">
                <a href="#add-constant-modal" data-toggle="modal" class="btn btn-success btn-block"><i class="zmdi zmdi-plus"></i> Add Constant</a>
                @if(count($errors)>0)
                        <small class="text-danger">Fill all field</small>
                @endif
            </div>
        @endif
    </div>
    <div class="row m-t-30">
        <div class="col-md-12">
            <div class="table-responsive m-b-40">
                <table class="table table-borderless table-data3">
                    <thead class="text-center">
                    <tr>
                        <th>Year</th>
                        <th>Advance Factor</th>
                        <th>Ramani</th>
                        <th>Hati</th>
                        <th>Usajiri Factor</th>
                        <th>Ushuru Substraction</th>
                        <th>Ushuru Factor</th>
                        <th>Ushuru Addition</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    @if(count($constants)>0)
                        @foreach($constants as $constant)
                            <tr>
                                <td>{{$constant->year}}</td>
                                <td>{{$constant->advanceFactor}}</td>
                                <td>{{number_format($constant->ramani)}}</td>
                                <td>{{number_format($constant->hati)}}</td>
                                <td>{{$constant->usajiriFactor}}</td>
                                <td>{{number_format($constant->ushuruSubstraction)}}</td>
                                <td>{{$constant->ushuruFactor}}</td>
                                <td>{{$constant->ushuruAddition}}</td>
                                <td>
                                    <div class="table-data-feature">
                                        @if($constant->controlNumbers()->count()==0)
                                            @if(auth()->user()->granted('constant_edit'))
                                                <a href="{{route('constant.edit',$constant->id)}}" class="item" title="edit">
                                                    <i class="zmdi zmdi-edit text-info"></i>
                                                </a>
                                            @endif
                                            @if(auth()->user()->granted('constant_delete'))
                                                <a href="#delete-constant-modal" class="item" title="delete">
                                                    <i class="zmdi zmdi-delete text-danger"></i>
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <h2 class="text-muted">No Constant available</h2>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-4 offset-4">
            {{$constants->links()}}
        </div>
    </div>
    <div class="row" style="margin-top: 2em;">
        <div class="col-md-6">
            <div class="top-campaign">
                <h3 class="title-3 m-b-30">Bank Accounts</h3>
                <div class="table-responsive">
                    <table class="table table-top-campaign">
                        <tbody>
                        @if($banks->count()>0)
                            @php($num=1)
                            @foreach($banks as $bank)
                                <tr>
                                    <td>{{$num}}. {{$bank->name}}</td>
                                    <td>{{$bank->account}}</td>
                                    <td>
{{--                                        <div class="table-data-feature">--}}
{{--                                            @if(auth()->user()->granted('constant_edit'))--}}
{{--                                                <a href="#" class="item" title="edit">--}}
{{--                                                    <i class="zmdi zmdi-edit text-info"></i>--}}
{{--                                                </a>--}}
{{--                                            @endif--}}
{{--                                            @if(auth()->user()->granted('constant_delete'))--}}
{{--                                                <a href="#delete-constant-modal" class="item" title="delete">--}}
{{--                                                    <i class="zmdi zmdi-delete text-danger"></i>--}}
{{--                                                </a>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
                                    </td>
                                </tr>
                                @php($num++)
                            @endforeach
                        @else
                            <p class="text-muted font-italic">No banks accounts</p>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="top-campaign">
                <h3 class="title-3 m-b-30">Plot Uses</h3>
                    @php($num=1)
                    <div class="row">
                        @foreach($plotuses->chunk(7) as $chunk)
                            <div class="col-md-6">
                                <table class="table table-top-campaign">
                                <tbody>
                                    @foreach($chunk as $plotuse)
                                        <tr>
                                            <td>{{$num}}. {{$plotuse}}</td>
                                            <td></td>
                                        </tr>
                                        @php($num++)
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                        @endforeach
                    </div>
            </div>
        </div>
    </div>
@stop

@section('modal')
    @if(auth()->user()->granted('constant_add'))
        <div class="modal" id="add-constant-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['route'=>'constant.store'],['id'=>'add-constant-form']) !!}
                <div class="modal-header">
                    <h4 class="modal-title">Add Constant</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10 offset-1">
                            <div class="form-group">
                                <label>Year</label>
                                {!! Form::number('year',null,['id'=>'year','class'=>'form-control']) !!}
                            </div>
                            <div class="form-group">
                                <label>Advance Factor</label>
                                {!! Form::number('advanceFactor',null,['id'=>'advanceFactor','class'=>'form-control','min'=>0.001,'step'=>0.001]) !!}
                            </div>
                            <div class="form-group">
                                <label>Ramani</label>
                                {!! Form::number('ramani',null,['id'=>'ramani','class'=>'form-control']) !!}
                            </div>
                            <div class="form-group">
                                <label>Hati</label>
                                {!! Form::number('hati',null,['id'=>'hati','class'=>'form-control']) !!}
                            </div>
                            <div class="form-group">
                                <label>Usajiri Factor</label>
                                {!! Form::number('usajiriFactor',null,['id'=>'usajiriFactor','class'=>'form-control','min'=>0.01,'step'=>0.01]) !!}
                            </div>
                            <div class="form-group">
                                <label>Ushuru Substraction</label>
                                {!! Form::number('ushuruSubstraction',null,['id'=>'ushuruSubstraction','class'=>'form-control']) !!}
                            </div>
                            <div class="form-group">
                                <label>Ushuru Factor</label>
                                {!! Form::number('ushuruFactor',null,['id'=>'ushuruFactor','class'=>'form-control','min'=>0.01,'step'=>0.01]) !!}
                            </div>
                            <div class="form-group">
                                <label>Ushuru Addition</label>
                                {!! Form::number('ushuruAddition',null,['id'=>'ushuruAddition','class'=>'form-control']) !!}
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    @endif

@stop
