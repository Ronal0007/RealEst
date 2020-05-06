@extends('layouts.main')
@section('title')
    Defaulters
@stop
@section('DefaultActive')
    active
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
            <h2 class="font-weight-light">{{$period}} Days Defaulters</h2>
        </div>
    </div>
    <div class="row m-t-30">
        <div class="col-md-12">
            <div class="table-responsive m-b-40">
                <table class="table table-borderless table-data3">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Customer</th>
                        <th>Control#</th>
                        <th>Amount</th>
                        <th>Amount Remained</th>
                        <th>Progress</th>
                        <th>Plot details</th>
                        <th>Payment Period</th>
                        <th>Added/Remain Days</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($controlNumbers->count()>0)
                        <h5 class="text-muted" style="margin-bottom: 2em;">({{$controlNumbers->total()}}) Found</h5>
                        @php($num=$controlNumbers->firstItem())
                        @foreach($controlNumbers as $control)
                            <tr>
                                <td>{{$num}}</td>
                                <td>{{$control->customer->name}}</td>
                                <td><a href="{{route('sale.payment',$control->number)}}">{{$control->number}}</a></td>
                                <td>{{number_format($control->totalRequiredAcq)}}</td>
                                <td>{{number_format($control->remain)}}</td>

                                <td class="font-weight-bold">
                                    @if($control->status->id==2)
                                        <div class="progress" style="height:10px">
                                            <div class="progress-bar progress-bar-striped progress-bar-success progress-bar-animated" style="width:{{$sale->paidPercent}}%;height:10px">{{$control->paidPercent}}%</div>
                                        </div>
                                    @else
                                        @if($control->paidPercent<50)
                                            <div class="progress" style="height:10px">
                                                <div class="progress-bar progress-bar-striped progress-bar-danger progress-bar-animated" style="width:{{$control->paidPercent}}%;height:10px">{{$control->paidPercent}}%</div>
                                            </div>
                                        @else
                                            <div class="progress" style="height:10px">
                                                <div class="progress-bar progress-bar-striped progress-bar-warning progress-bar-animated" style="width:{{$control->paidPercent}}%;height:10px">{{$control->paidPercent}}%</div>
                                            </div>
                                        @endif
                                    @endif
                                </td>
                                <td>{{$control->plotDetails}}</td>
                                <td>{{$control->period->name}}</td>
                                <td>
                                    <p>{{$control->addedDays}}</p>

                                </td>
                                <td class="table-action">
                                        <div class="dropdown">
                                            <a class="action-icon text-secondary" style="width: 100%;" data-toggle="dropdown" href="javascript:"><i class="fa fa-ellipsis-v"></i></a>
                                            <ul class="dropdown-menu pull-right">
                                                @if(auth()->user()->granted('defaulter_torelate'))
                                                    <li>
                                                        <a href="#add-day-modal" data-toggle="modal" data-number="{{$control->number}}" title="Add days" class="item">
                                                            <i class="zmdi zmdi-plus"></i> Add Days
                                                        </a>
                                                    </li>
                                                @endif
                                                @if(auth()->user()->granted('defaulter_revoke'))
                                                    <li>
                                                        <a href="{{route('sale.revoke',$control->number)}}" title="Revoke Defaulter" class="item">
                                                            <i class="zmdi zmdi-redo"></i> Revoke
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                </td>
                            </tr>
                            @php($num++)
                        @endforeach
                    @else
                        <h4 class="text-muted">No result found</h4>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6 offset-3">
                {{$controlNumbers->links()}}
        </div>
    </div>
@stop

@section('modal')
    @if(auth()->user()->granted('defaulter_torelate'))
        {{--    Add modal--}}
        <div class="modal" id="add-day-modal" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['route'=>['sale.torelate',''],'id'=>'add-day-form']) !!}
                    <div class="modal-header">
                        <h4 class="modal-title">Add Days</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-5 offset-3">
                                <div class="form-group">
                                    {!! Form::number('days',1,['class'=>'form-control','id'=>'days','placeholder'=>'Choose days','min'=>1,'required']) !!}
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
@stop

@section('script')
    <script>
        //Delete project
        $('#add-day-modal').on('show.bs.modal',function (e) {
            var number = $(e.relatedTarget).data('number');
            $('#add-day-form').attr('action','{{route('sale.torelate','')}}'+'/'+number);
        });
    </script>
@stop
