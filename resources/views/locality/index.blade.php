@extends('layouts.main')
@section('title')
    Locality | index
@stop
@section('LocalityActive')
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
            <h2 class="font-weight-light">Localities</h2>
        </div>
        <div class="col-md-2">
        </div>
        @if(auth()->user()->granted('locality_add'))
            <div class="col-md-2">
                <a href="#add-locality-modal" data-toggle="modal" class="btn btn-success btn-block"><i class="zmdi zmdi-plus"></i> Add Locality</a>
                @if($errors->has('name'))
                    <small class="text-danger">Locality name is required</small>
                @endif
                @if($errors->has('project_id'))
                    <small class="text-danger">Please select project</small>
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
                        <th>No.</th>
                        <th>Locality Name</th>
                        <th>Project Name</th>
                        <th>Blocks</th>
                        <th>Plots</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    @if(count($localities)>0)
                        @php($num=$localities->firstItem())
                        @foreach($localities as $locality)
                            <tr>
                                <td>{{$num}}</td>
                                <td>{{$locality->name}}</td>
                                <td title="view project"><a class="text-danger" href="{{route('project.show',$locality->project->slug)}}">{{$locality->project->name}}</a></td>
                                <td>{{$locality->blocks()->count()}}</td>
                                <td class="text-danger">
                                    @if(auth()->user()->granted('plot_view'))
                                        <a class="text-danger" href="{{route('locality.plots',['locality'=>$locality->slug])}}">{{$locality->plots()->count()}}</a>
                                    @else
                                        {{$locality->plots()->count()}}
                                    @endif
                                </td>
                                <td>{{$locality->created_at->format('d M Y H:i')}}</td>
                                <td class="table-action">
                                    @if($locality->blocks()->count()==0)
                                        <div class="dropdown">
                                            <a class="action-icon text-secondary" style="width: 100%;" data-toggle="dropdown" href="javascript:"><i class="fa fa-ellipsis-v"></i></a>
                                            <ul class="dropdown-menu pull-right">
                                                @if((auth()->user()->granted('locality_edit')))
                                                    <li>
                                                        <a href="#edit-locality-modal" class="item" data-toggle="modal" data-locality="{{$locality->id}}" data-name="{{$locality->name}}" data-project="{{$locality->project->id}}">
                                                            <i class="zmdi zmdi-edit"></i> Edit
                                                        </a>
                                                    </li>
                                                @endif
                                                @if(auth()->user()->granted('locality_delete'))
                                                    <li>
                                                        <a href="#delete-locality-modal" class="item" data-toggle="modal" data-locality="{{$locality->slug}}">
                                                            <i class="zmdi zmdi-delete"></i> Delete
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @php($num++)
                        @endforeach
                    @else
                        <h2 class="text-muted">No Locality available</h2>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6 offset-3">
            {{$localities->links()}}
        </div>
    </div>
@stop

@section('modal')
    @if(auth()->user()->granted('locality_add'))
    {{--    Add modal--}}
        <div class="modal" id="add-locality-modal" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['route'=>'locality.store','id'=>'add-locality-form']) !!}
                        <div class="modal-header">
                            <h4 class="modal-title">Add Locality</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company" class=" form-control-label">Locality Name</label>
                                        {!! Form::text('name','',['class'=>'form-control','id'=>'locality_name','placeholder'=>'Enter locality name']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company" class=" form-control-label">Project</label>
                                        {!! Form::select('project_id',$projects, null, ['id'=>'locality_project_id','placeholder' => 'Select project','class'=>'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    @endif
    @if(auth()->user()->granted('locality_edit'))
        {{--    Edit modal--}}
        <div class="modal" id="edit-locality-modal" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="edit-locality-form">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Locality</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="locality_id_edit">
                            <span id="url" hidden>{{route('update.locality')}}</span>
                            <span id="token" hidden>{{csrf_token()}}</span>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company" class=" form-control-label">Locality Name</label>
                                        {!! Form::text('name','',['class'=>'form-control','id'=>'locality_name_edit','placeholder'=>'Enter locality name']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company" class=" form-control-label">Project</label>
                                        {!! Form::select('project_id',$projects, null, ['id'=>'locality_project_id_edit','placeholder' => 'Select project','class'=>'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    @if(auth()->user()->granted('locality_delete'))
        {{--    delete modal--}}
        <div class="modal" id="delete-locality-modal" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="delete-project-modal">
                        <span id="locality_id_delete" hidden></span>
                        <span id="locality_url_delete" hidden>{{route('locality.delete','')}}</span>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p>Delete this Locality?</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button id="locality_delete_btn" type="button" class="btn btn-danger">Yes</button>
                                <button type="submit" class="btn btn-info" data-dismiss="modal">No</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@stop
