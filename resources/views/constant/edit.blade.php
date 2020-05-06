@extends('layouts.main')
@section('title')
    Constant | edit
@stop
@section('ConstantActive')
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
        <div class="col-10 offset-1">
            <div class="form-content">
                <div class="row" style="margin-bottom: 2em;">
                    <div class="col-md-8">
                        <h2 class="font-weight-light">Edit Constant</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10">
                        {!! Form::model($constant,['route'=>['constant.update',$constant->id]],['id'=>'edit-constant-form']) !!}
                        <div class="row">
                                <div class="col-md-10 offset-1">
                                    <div class="form-group">
                                        <label>Year</label>
                                        {!! Form::number('year',$constant->year,['id'=>'year','class'=>'form-control','readonly']) !!}
                                    </div>
                                    <div class="form-group">
                                        <label>Advance Factor</label>
                                        {!! Form::number('advanceFactor',$constant->advanceFactor,['id'=>'advanceFactor','class'=>'form-control','min'=>0.001,'step'=>0.001]) !!}
                                    </div>
                                    <div class="form-group">
                                        <label>Ramani</label>
                                        {!! Form::number('ramani',$constant->ramani,['id'=>'ramani','class'=>'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        <label>Hati</label>
                                        {!! Form::number('hati',$constant->hati,['id'=>'hati','class'=>'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        <label>Usajiri Factor</label>
                                        {!! Form::number('usajiriFactor',$constant->usajiriFactor,['id'=>'usajiriFactor','class'=>'form-control','min'=>0.01,'step'=>0.01]) !!}
                                    </div>
                                    <div class="form-group">
                                        <label>Ushuru Substraction</label>
                                        {!! Form::number('ushuruSubstraction',$constant->ushuruSubstraction,['id'=>'ushuruSubstraction','class'=>'form-control']) !!}
                                    </div>
                                    <div class="form-group">
                                        <label>Ushuru Factor</label>
                                        {!! Form::number('ushuruFactor',$constant->ushuruFactor,['id'=>'ushuruFactor','class'=>'form-control','min'=>0.01,'step'=>0.01]) !!}
                                    </div>
                                    <div class="form-group">
                                        <label>Ushuru Addition</label>
                                        {!! Form::number('ushuruAddition',$constant->ushuruAddition,['id'=>'ushuruAddition','class'=>'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 offset-8">
                                    <button type="submit" class="btn btn-success btn-block btn-lg">Submit</button>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('modal')
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

@stop
