@extends('layouts.main')
@section('title')
    User | edit
@stop
@section('UserActive')
    class="active"
@stop

@section('content')
    <div class="row">
        <div class="col-md-4 offset-4">
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
                    <div class="col-md-6">
                        <h2 class="font-weight-light" style="margin-bottom: 1em;">Edit User</h2>
                    </div>
                    <div class="col-md-3 offset-3">
                        <a href="{{route('user.password.reset',$user->slug)}}" class="btn btn-danger"><i class="zmdi zmdi-edit"></i> Reset password</a>
                    </div>
                </div>

                {!! Form::model($user,['route'=>['user.update',$user->slug],'method'=>'PUT'],['id'=>'user-create-form']) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company" class=" form-control-label">First Name</label>
                                {!! Form::text('fname',null,['class'=>'form-control','id'=>'user_fname','placeholder'=>'Enter first name']) !!}
                                @if($errors->has('fname'))
                                    <small class="text-danger">Enter user first name</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company" class=" form-control-label">Last Name</label>
                                {!! Form::text('lname',null,['class'=>'form-control','id'=>'user_lname','placeholder'=>'Enter last name']) !!}
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
                            {!! Form::email('email',null,['class'=>'form-control','id'=>'user_email','placeholder'=>'Enter email','readonly']) !!}
                            @if($errors->has('email'))
                                <small class="text-danger">Enter email</small>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="company" class=" form-control-label">Phone #</label>
                            {!! Form::text('phone',null,['class'=>'form-control','id'=>'user_phone','placeholder'=>'Enter phone number']) !!}
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
                    <div class="col-md-6" {{$user->role->name=='admin'?'hidden':''}}>
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
