@extends('layouts.main')
@section('title')
    Plot | index
@stop
@section('PlotActive')
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
            <h2 class="font-weight-light">Plots </h2>
        </div>
        <div class="col-md-2">
        </div>
        @if(auth()->user()->granted('plot_add'))
            <div class="col-md-2">
                <a href="{{route('plot.create')}}" class="btn btn-success btn-block"><i class="zmdi zmdi-plus"></i> Add Plot</a>
            </div>
        @endif
    </div>
    <div class="row m-t-30" style="padding-bottom: 3em;">
        <div class="col-md-12">
            <div class="table-responsive m-b-40">
                <table class="table table-borderless table-data3">
                    <thead class="text-center">
                        <tr>
                            <th>Plot#</th>
                            <th>Size</th>
                            <th>Location</th>
                            <th>Survey Plan#</th>
                            <th>Registered#</th>
                            <th>Plot Use</th>
                            <th>Created By</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                    @if(count($plots)>0)
                        <h4 class="text-muted">{{$plots->total()}} Plot(s) available, Entry {{$plots->firstItem()}}-{{$plots->lastItem()}}</h4>
                        @foreach($plots as $plot)
                            <tr>
                                <td>{{$plot->number}}</td>
                                <td>{{$plot->size}}</td>
                                <td style="font-size: 10pt;">{{$plot->block->locality->project->name}}_{{$plot->block->locality->name}}_{{$plot->block->code}}</td>
                                <td  style="font-size: 10pt;">{{$plot->surveyNumber}}</td>
                                <td>{{$plot->registeredNumber}}</td>
                                <td>{{$plot->plotuse->name}}</td>
                                <td>{{$plot->user->name}}</td>
                                <td class="{{$plot->status->id==1?'text-success':'text-warning'}}">
                                    @if($plot->status->id==1)
                                        Available
                                    @else
                                        @if(auth()->user()->granted('payment_view'))
                                            <a class="{{$plot->status->id==1?'text-success':'text-warning'}}" href="{{route('sale.payment',$plot->controlNumber->number)}}">Sold</a>
                                        @else
                                            <span class="{{$plot->status->id==1?'text-success':'text-warning'}}">Sold</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="table-action">
                                    @if($plot->status->id==1)
                                        <div class="dropdown">
                                            <a class="action-icon text-secondary" style="width: 100%;" data-toggle="dropdown" href="javascript:"><i class="fa fa-ellipsis-v"></i></a>
                                            <ul class="dropdown-menu pull-right">
                                                @if(auth()->user()->granted('plot_edit'))
                                                    <li>
                                                        <a href="{{route('plot.edit',$plot->slug)}}" title="edit" class="item">
                                                            <i class="zmdi zmdi-edit"></i> Edit
                                                        </a>
                                                    </li>
                                                @endif
                                                @if($plot->status->id==1 && auth()->user()->granted('plot_delete'))
                                                    <li>
                                                        <a href="#delete-plot-modal" class="item" data-toggle="modal" data-plot="{{$plot->slug}}">
                                                            <i class="zmdi zmdi-delete"></i> Delete
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <h2 class="text-muted">No plot(s) available</h2>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6 offset-3">
                {{$plots->links()}}
        </div>
    </div>
@stop

@section('modal')
    <div class="modal" id="delete-plot-modal" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="delete-project-modal">
                    <span id="plot_id_delete" hidden></span>
                    <span id="plot_url_delete" hidden>{{url('/delete/plot','')}}</span>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p>Delete this plot?</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="plot_delete_btn" type="button" class="btn btn-danger">Yes</button>
                            <button type="submit" class="btn btn-info" data-dismiss="modal">No</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
        //Delete project
        $('#delete-plot-modal').on('show.bs.modal',function (e) {
            $(this).find('#plot_id_delete').text($(e.relatedTarget).data('plot'));
        });

        $('#plot_delete_btn').on('click',function () {
            var id = $('#delete-plot-modal').find('#plot_id_delete').text();
            var url = $('#delete-plot-modal').find('#plot_url_delete').text()+'/'+id;
            window.location.replace(url);
        });
    </script>
@stop
