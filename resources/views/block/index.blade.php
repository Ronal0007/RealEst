@extends('layouts.main')
@section('title')
    Block | index
@stop
@section('BlockActive')
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
            <h2 class="font-weight-light">Blocks</h2>
        </div>
        <div class="col-md-2">
        </div>
        @if(auth()->user()->granted('block_add') || auth()->user()->role->name=='admin')
            <div class="col-md-2">
                <a href="#add-block-modal" data-toggle="modal" class="btn btn-success btn-block"><i class="zmdi zmdi-plus"></i> Add Block</a>
                @if($errors->has('name'))
                    <small class="text-danger">Block name is required</small>
                @endif
                @if($errors->has('project_id'))
                    <small class="text-danger">Please select project</small>
                @endif
                @if($errors->has('locality_id'))
                    <small class="text-danger">Please select locality</small>
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
                        <th>Block code</th>
                        <th>Survey Plan Number</th>
                        <th>Locality Name</th>
                        <th>Project Name</th>
                        <th>Plots</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="text-center">
                    @if(count($blocks)>0)
                        @php($num=$blocks->firstItem())
                        @foreach($blocks as $block)
                            <tr>
                                <td>{{$num}}</td>
                                <td>{{$block->code}}</td>
                                <td>{{$block->surveyNumber}}</td>
                                <td>{{$block->locality->name}}</td>
                                <td>{{$block->locality->project->name}}</td>
                                <td class="text-danger">
                                    @if(auth()->user()->granted('plot_view'))
                                        <a class="text-danger" href="{{route('block.plots',['block'=>$block->slug])}}">{{$block->plots()->count()}}
                                        </a>
                                    @else
                                        {{$block->plots()->count()}}
                                    @endif
                                </td>
                                <td>{{$block->created_at->format('d M Y H:i')}}</td>
                                <td class="table-action">
                                    @if($block->plots()->count()==0)
                                        <div class="dropdown">
                                            <a class="action-icon text-secondary" style="width: 100%;" data-toggle="dropdown" href="javascript:"><i class="fa fa-ellipsis-v"></i></a>
                                            <ul class="dropdown-menu pull-right">
                                                @if(auth()->user()->granted('block_edit'))
                                                    <li>
                                                        <a href="#edit-block-modal" title="edit" class="item" data-toggle="modal" data-block="{{$block->id}}" data-code="{{$block->code}}" data-survey="{{$block->surveyNumber}}" data-locality="{{$block->locality->id}}" data-project="{{$block->locality->project->id}}">
                                                            <i class="zmdi zmdi-edit"></i> Edit
                                                        </a>
                                                    </li>
                                                @endif
                                                @if(auth()->user()->granted('block_delete'))
                                                    <li>
                                                        <a href="#delete-block-modal" class="item" data-toggle="modal" data-block="{{$block->slug}}">
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
                        <h2 class="text-muted">No Block(s) available</h2>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6 offset-3">
            {{$blocks->links()}}
        </div>
    </div>
@stop

@section('modal')
    @if(auth()->user()->granted('block_add') || auth()->user()->role->name=='admin')
        {{--    Add modal--}}
        <div class="modal" id="add-block-modal" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['route'=>'block.store','id'=>'add-block-form']) !!}
                    <div class="modal-header">
                        <h4 class="modal-title">Add Block</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <span class="token" hidden>{{csrf_token()}}</span>
                        <span id="url" hidden>{{route('locality.get')}}</span>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company" class=" form-control-label">Project</label>
                                    {!! Form::select('',$projects, null, ['placeholder' => 'Select project','class'=>'form-control block_project_id']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company" class=" form-control-label">Locality</label>
                                    {!! Form::select('locality_id',[], null, ['class'=>'form-control block_locality_id','disabled'=>'true']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company" class=" form-control-label">Block code</label>
                                    {!! Form::text('code','',['class'=>'form-control','id'=>'block_name','placeholder'=>'Enter locality name']) !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company" class=" form-control-label">Survey Plan Number</label>
                                    {!! Form::text('surveyNumber','',['class'=>'form-control','id'=>'block_survey_number','placeholder'=>'Enter Survey plan number name']) !!}
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

    @if(auth()->user()->granted('block_edit') || auth()->user()->role->name=='admin')
        {{--    Edit modal--}}
        <div class="modal" id="edit-block-modal" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="edit-block-form" method="POST">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Block</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            {{csrf_field()}}
                            <input type="hidden" id="block_id_edit">
                            <input type="hidden" name="_method" value="PUT">
                            <span id="url" hidden>{{route('locality.get')}}</span>
                            <span id="updateUrl" hidden>{{route('block.update','')}}</span>
                            <span class="token" hidden>{{csrf_token()}}</span>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company" class=" form-control-label">Block Code</label>
                                        {!! Form::text('code','',['class'=>'form-control','id'=>'block_code_edit','placeholder'=>'Enter locality name']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company" class=" form-control-label">Survey Plan Number</label>
                                        {!! Form::text('surveyNumber','',['class'=>'form-control','id'=>'block_survey_number_edit','placeholder'=>'Enter Survey plan number name']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company" class=" form-control-label">Project</label>
                                        {!! Form::select('project_id',$projects, null, ['placeholder' => 'Select project','class'=>'form-control block_project_id']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company" class=" form-control-label">Locality</label>
                                        {!! Form::select('locality_id',[], null, ['placeholder' => 'Select Locality','class'=>'form-control block_locality_id']) !!}
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
    @if(auth()->user()->granted('block_delete') || auth()->user()->role->name=='admin')
        {{--    delete modal--}}
        <div class="modal" id="delete-block-modal" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="delete-project-modal">
                        <span id="block_id_delete" hidden></span>
                        <span id="block_url_delete" hidden>{{route('block.delete','')}}</span>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p>Delete this Block?</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button id="block_delete_btn" type="button" class="btn btn-danger">Yes</button>
                                <button type="submit" class="btn btn-info" data-dismiss="modal">No</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@stop
