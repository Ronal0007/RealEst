@extends('layouts.main')
@section('title')
    User | index
@stop
@section('UserActive')
    class="active"
@stop

@section('content')
    <div class="row">
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
        <div class="col-md-2 offset-8">
            <div class="dropdown">
                <button type="button" class="btn btn-secondary dropdown-toggle btn-block" data-toggle="dropdown">
                    Sort
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{route('user.index',['sort'=>'az'])}}">Name a-z</a>
                    <a class="dropdown-item" href="{{route('user.index',['sort'=>'za'])}}">Name z-a</a>
                    <a class="dropdown-item" href="{{route('user.index',['sort'=>'new'])}}">Newest</a>
                    <a class="dropdown-item" href="{{route('user.index',['sort'=>'old'])}}">Old</a>
                </div>
            </div>
        </div>
        @if(auth()->user()->granted('user_add'))
            <div class="col-md-2">
                <a href="{{route('user.create')}}" class="btn btn-success btn-block"><i class="fa fa-user-plus"></i> Add User</a>
            </div>
        @endif
    </div>
    <div class="row m-t-30">
        <div class="col-md-12">
            <div class="user-data m-b-30" style="margin-bottom: 2em;padding-bottom: 3em;">
                <h3 class="title-3 m-b-30">
                    <i class="zmdi zmdi-account-calendar"></i>user data</h3>
                <div class="table-responsive table-data">
                    <table class="table">
                        <thead>
                            <tr>
                                <td>Name</td>
                                <td>Email</td>
                                <td>Gender</td>
                                <td>Phone</td>
                                <td>role</td>
                                <td>Status</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                        @if(count($users)>0)
                            <h4 class="text-muted font-weight-light" style="margin-left: 2em;">{{$users->count()}} User(s) available</h4>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="table-data__info">
                                            <h6>{{$user->fname}} {{$user->lname}}</h6>
                                        </div>
                                    </td>
                                    <td class="text-info font-weight-light" style="font-size: 11pt;">{{$user->email}}</td>
                                    <td><i class="zmdi zmdi-{{$user->gender->id==1?'male':'female'}}"></i> {{$user->gender->name}}</td>
                                    <td>{{$user->phone}}</td>
                                    <td>
                                        @switch($user->role->name)
                                            @case('admin')
                                                <span class="role admin">Administrator</span>
                                            @break
                                            @case('manager')
                                            <span class="role manager">Manager</span>
                                            @break
                                            @case('dataentry')
                                            <span class="role dataentry">Data Entry</span>
                                            @break
                                            @case('land')
                                            <span class="role land">Land Officer</span>
                                            @break
                                        @endswitch
                                    </td>
                                    <td class="{{$user->status->id==1?'text-success':'text-danger'}}">{{$user->status->name}}</td>
                                    <td>
                                        <div class="table-data-feature">
                                            @if(auth()->user()->granted('user_edit'))
                                                <a href="{{route('user.edit',$user->slug)}}" class="item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit">
                                                    <i class="zmdi zmdi-edit text-info"></i>
                                                </a>
                                            @endif
                                            @if(auth()->user()->granted('user_permission'))
                                                <a {{$user->role->name=='admin'?'hidden':''}} href="{{route('user.permission',$user->slug)}}" class="item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit Permissions">
                                                    <i class="zmdi zmdi-key text-info"></i>
                                                </a>
                                            @endif
                                            @if(auth()->user()->granted('user_delete'))
                                                {!! Form::open(['route'=>['user.destroy',$user->slug],'method'=>'DELETE']) !!}
                                                    <button type="submit" class="item" data-toggle="tooltip" data-placement="top" title="Delete User" data-original-title="Delete">
                                                        <i class="zmdi zmdi-delete text-danger"></i>
                                                    </button>
                                                {!! Form::close() !!}
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4 offset-4">
            {{$users->links()}}
        </div>
    </div>
@stop
