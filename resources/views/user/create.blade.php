@extends('layouts.main')
@section('title')
    User | create
@stop
@section('UserActive')
    class="active"
@stop

@section('content')
    <div class="row m-t-30">
        <div class="col-md-10 offset-1">
            <div class="form-content" style="margin-bottom: 2em;">
                <div class="row">
                    <div class="col-md-11">
                        <h2 class="font-weight-light" style="margin-bottom: 1em;">New User</h2>
                    </div>
                </div>

                {!! Form::open(['route'=>'user.store'],['id'=>'user-create-form']) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company" class=" form-control-label">First Name</label>
                                {!! Form::text('fname','',['class'=>'form-control','id'=>'user_fname','placeholder'=>'Enter first name']) !!}
                                @if($errors->has('fname'))
                                    <small class="text-danger">Enter user first name</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company" class=" form-control-label">Last Name</label>
                                {!! Form::text('lname','',['class'=>'form-control','id'=>'user_lname','placeholder'=>'Enter last name']) !!}
                                @if($errors->has('lname'))
                                    <small class="text-danger">Enter user last name</small>
                                @endif
                            </div>
                        </div>
                    </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="company" class=" form-control-label">Email</label>
                            {!! Form::email('email','',['class'=>'form-control','id'=>'user_email','placeholder'=>'Enter email']) !!}
                            @if($errors->has('email'))
                                <small class="text-danger">Enter email</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="company" class=" form-control-label">Phone #</label>
                            {!! Form::text('phone','',['class'=>'form-control','id'=>'user_phone','placeholder'=>'Enter phone number']) !!}
                            @if($errors->has('phone'))
                                <small class="text-danger">Enter phone number</small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="company" class=" form-control-label">Gender</label>
                            {!! Form::select('gender_id', $genders, null,['id'=>'user_gender','class'=>'form-control','placeholder'=>'Select Gender']) !!}
                            @if($errors->has('gender_id'))
                                <small class="text-danger">please select gender</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="company" class=" form-control-label">Status</label>
                            {!! Form::select('status_id', $statuses, null,['id'=>'user_status','class'=>'form-control','placeholder'=>'Select Status']) !!}
                            @if($errors->has('status_id'))
                                <small class="text-danger">please select status</small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="company" class=" form-control-label">Role</label>
                            {!! Form::select('role_id', $roles, null,['id'=>'user_role','class'=>'form-control','placeholder'=>'Select Role']) !!}
                            @if($errors->has('role_id'))
                                <small class="text-danger">please select role</small>
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