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
            <h2 class="font-weight-light">
                @if(empty($block))
                    Plots
                @else
                    {{$block->locality->project->name}} {{$block->locality->name}} {{$block->code}} Plots
                @endif
            </h2>
        </div>
        <div class="col-md-2">
            <div class="dropdown">
                <button type="button" class="btn btn-secondary dropdown-toggle btn-block" data-toggle="dropdown">
                    Sort
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{route('locality.index',['sort'=>'az'])}}">Name a-z</a>
                    <a class="dropdown-item" href="{{route('locality.index',['sort'=>'za'])}}">Name z-a</a>
                    <a class="dropdown-item" href="{{route('locality.index',['sort'=>'new'])}}">Newest</a>
                    <a class="dropdown-item" href="{{route('locality.index',['sort'=>'old'])}}">Old</a>
                </div>
            </div>
        </div>
        @if(auth()->user()->granted('plot_add'))
            <div class="col-md-2">
                <a href="{{route('plot.create')}}" class="btn btn-success btn-block"><i class="zmdi zmdi-plus"></i> Add Plot</a>
            </div>
        @endif
    </div>
    <div class="row m-t-30">
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
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                    @if(count($block->p)>0)
                        <h4 class="text-muted">{{$block->p->total()}} Plot(s) available</h4>
                        @foreach($block->p as $plot)
                            <tr>
                                <td>{{$plot->number}}</td>
                                <td>{{$plot->size}}</td>
                                <td class="text-primary" style="font-size: 10pt;">{{$plot->block->locality->project->name}}_{{$plot->block->locality->name}}_{{$plot->block->code}}</td>
                                <td class="text-info" style="font-size: 10pt;">{{$plot->surveyNumber}}</td>
                                <td>{{$plot->registeredNumber}}</td>
                                <td>{{$plot->plotuse->name}}</td>
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
        <div class="col-md-4 offset-4">
                {{$block->p->links()}}
        </div>
    </div>
@stop
