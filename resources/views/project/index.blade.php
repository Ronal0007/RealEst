@extends('layouts.main')
@section('title')
    Project | index
@stop
@section('ProjectActive')
    class="active"
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <h2 class="font-weight-light">Projects</h2>
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
        <div class="col-md-2 offset-8">
        </div>
        @if(auth()->user()->granted('project_add'))
            <div class="col-md-2">
                <a href="{{route('project.create')}}" class="btn btn-success btn-block"><i class="zmdi zmdi-plus"></i> Add Project</a>
            </div>
        @endif
    </div>
    <div class="row m-t-30">
        <div class="col-md-12">
            <div class="table-responsive m-b-40">
                <table class="table table-borderless table-data3">
                    <thead class="text-center">
                        <tr>
                            <th>NO.</th>
                            <th>Project Name</th>
                            <th>Localities</th>
                            <th>Blocks</th>
                            <th>Plots</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                    @if(count($projects)>0)
                        <h4 class="text-muted">{{$projects->count()}} Project(s) available</h4>
                        @php($num=$projects->firstItem())
                        @foreach($projects as $project)
                            <tr>
                                <td>{{$num}}</td>
                                <td>{{$project->name}}</td>
                                <td>{{$project->localities()->count()}}</td>
                                <td>{{$project->blocks()->count()}}</td>
                                <td><a class="text-danger" href="{{route('project.plots',['project'=>$project->slug])}}">{{$project->plots()->count()}}</a></td>
                                <td>{{$project->created_at->format('d M Y H:i')}}</td>
                                <td class="{{$project->status->id==1?'text-success':'text-danger'}}">{{ucfirst($project->status->name)}}</td>
                                <td class="table-action">
                                    <div class="dropdown">
                                        <a class="action-icon text-secondary" style="width: 100%;" data-toggle="dropdown" href="javascript:"><i class="fa fa-ellipsis-v"></i></a>
                                        <ul _ class="dropdown-menu pull-right">
                                            @if($project->controlNumbers()->count()==0 && (auth()->user()->granted('project_edit')))
                                                <li>
                                                    <a href="{{route('project.edit',$project->slug)}}" class="item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit">
                                                        <i class="zmdi zmdi-edit"></i> Edit
                                                    </a>
                                                </li>
                                            @endif
                                            @if($project->localities()->count()==0 && (auth()->user()->granted('project_delete')))
                                                <li>
                                                    <a href="#delete-project-modal" class="item" data-toggle="modal" data-project="{{$project->slug}}">
                                                        <i class="zmdi zmdi-delete"></i> Delete
                                                    </a>
                                                </li>
                                            @endif
                                            <li>
                                                <a href="{{route('project.show',$project->slug)}}" class="item" data-toggle="tooltip" data-placement="top" title="" data-original-title="View Project">
                                                    <i class="zmdi zmdi-eye"></i> Show
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @php($num++)
                        @endforeach
                    @else
                        <h2 class="text-muted">No Project available</h2>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6 offset-3">
            {{$projects->links()}}
        </div>
    </div>
@stop

@section('modal')
    @if(auth()->user()->granted('project_delete'))
        {{--    delete modal--}}
        <div class="modal" id="delete-project-modal" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="delete-project-modal">
                        <span id="project_id_delete" hidden></span>
                        <span id="project_url_delete" hidden>{{url('/delete/project','')}}</span>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p>Delete this project?</p>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button id="project_delete_btn" type="button" class="btn btn-danger">Yes</button>
                            <button type="submit" class="btn btn-info" data-dismiss="modal">No</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@stop
