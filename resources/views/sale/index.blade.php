@extends('layouts.main')
@section('title')
    Sale | view
@stop
@section('SaleActive')
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
        <div class="col-md-6">
            <h2 class="font-weight-light">Sales</h2>
        </div>
        <div class="col-md-5">
            <form class="form-header" action="{{route('sale.search')}}" method="POST">
                {{csrf_field()}}
                <input class="form-control" type="text" name="search" placeholder="Search control Number">
                <button class="btn btn-secondary" type="submit">
                     <i class="zmdi zmdi-search"></i>
                </button>
            </form>
        </div>
    </div>
    <div class="row m-t-30">
        <div class="col-md-12">
            <div class="table-responsive m-b-40">
                <table class="table table-borderless table-data3">
                    <thead>
                        <tr>
                            <th>NO.</th>
                            <th>Control No.</th>
                            <th>Amount</th>
                            <th>Remain</th>
                            <th>Plot details</th>
                            <th>Created By</th>
                            <th>Remain Period</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @if(count($sales)>0)
                        <h5 class="text-muted" style="margin-bottom: 2em;">
                            @if(!empty($number))
                                '{{$number}}' search result
                            @endif
                            ({{$sales->total()}}) Found</h5>

                            @if(\Illuminate\Support\Facades\Session::exists('suspence'))
                                <a href="{{route('sale.suspence.transfer.cancel')}}" class="btn btn-warning" style="margin-bottom: 1em;">Cancel Transfer</a>
                            @endif
                        @php($num=$sales->firstItem())
                        @foreach($sales as $sale)
                            <tr>
                                <td>{{$num}}</td>
                                <td>
                                    @if(Session('suspence')!=null && $sale->status->id==1)
                                        @if(!empty($number))
                                            <?php echo "<a class='transfer-link' data-control='".$sale->number."' href='".route('sale.suspence.transfer.to',$sale->number)."'>".str_replace(strtoupper($number),"<span class='bg-warning'>".strtoupper($number)."</span>",$sale->number)."</a>" ?>
                                        @else
                                            <a class='transfer-link' data-control="{{$sale->number}}" href="{{route('sale.suspence.transfer.to',$sale->number)}}">{{$sale->number}}</a>
                                        @endif
                                    @else
                                        @if(!empty($number))
                                            <?php echo str_replace(strtoupper($number),"<span class='bg-warning'>".strtoupper($number)."</span>",$sale->number) ?>
                                        @else
                                            {{$sale->number}}
                                        @endif
                                    @endif
                                </td>
                                <td>{{number_format($sale->totalRequiredAcq)}}</td>
                                <td>{{$sale->remain>0?number_format($sale->remain):'none'}}</td>
                                <td>{{$sale->plotDetails}}</td>
                                <td>{{$sale->user->name}}</td>
                                <td>
                                    @if($sale->status->id==2)
                                        <span class="text-success font-weight-bold">Complete</span>
                                    @else
                                        @if($sale->remainPeriod<=0)
                                            <p class="text-primary">Defaulter</p>
                                        @else
                                            <p>{{$sale->created_at->addDays($sale->period->duration)->diffForHumans()}}</p>
                                        @endif
                                    @endif
                                </td>
                                <td class="font-weight-bold">
                                    @if($sale->status->id==2)
                                        <div class="progress" style="height:10px">
                                            <div class="progress-bar progress-bar-striped progress-bar-success progress-bar-animated" style="width:{{$sale->paidPercent}}%;height:10px">{{$sale->paidPercent}}%</div>
                                        </div>
                                    @else
                                        @if($sale->paidPercent<50)
                                            <div class="progress" style="height:10px">
                                                <div class="progress-bar progress-bar-striped progress-bar-danger progress-bar-animated" style="width:{{$sale->paidPercent}}%;height:10px">{{$sale->paidPercent}}%</div>
                                            </div>
                                        @else
                                            <div class="progress" style="height:10px">
                                                <div class="progress-bar progress-bar-striped progress-bar-warning progress-bar-animated" style="width:{{$sale->paidPercent}}%;height:10px">{{$sale->paidPercent}}%</div>
                                            </div>
                                        @endif
                                    @endif
                                </td>
                                <td class="table-action">
                                    <div class="dropdown">
                                        <a class="action-icon text-secondary" style="width: 100%;" data-toggle="dropdown" href="javascript:"><i class="fa fa-ellipsis-v"></i></a>
                                        <ul _ class="dropdown-menu pull-right">
                                            @if(auth()->user()->granted('payment_view'))
                                                <li>
                                                    <a href="{{route('sale.payment',$sale->number)}}" title="view payments" class="item">
                                                        <i class="zmdi zmdi-money"></i> Payments
                                                    </a>
                                                </li>
                                            @endif
                                            @if(auth()->user()->granted('control_invoice'))
                                                <li>
                                                    <a href="{{route('sale.show',$sale->number)}}" target="_blank" title="View Invoice" class="item">
                                                        <i class="zmdi zmdi-eye"></i> Invoice
                                                    </a>
                                                </li>
                                            @endif
                                            @if(!$sale->payments()->count()>0)
                                                @if(auth()->user()->granted('control_edit'))
                                                    <li>
                                                        <a href="{{route('sale.edit',$sale->number)}}" title="edit" class="item">
                                                            <i class="zmdi zmdi-edit"></i> Edit
                                                        </a>
                                                    </li>
                                                @endif
                                                @if(auth()->user()->granted('control_delete'))
                                                    <li>
                                                        {!! Form::open(['route'=>['sale.destroy',$sale->number],'method'=>'DELETE']) !!}
                                                        <button type="submit" class="btn-link" data-toggle="modal" title="delete sale">
                                                            <i class="zmdi zmdi-delete"></i> Delete
                                                        </button>
                                                        {!! Form::close() !!}
                                                    </li>
                                                @endif
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
            {{$sales->links()}}
        </div>
    </div>
@stop

@section('script')
    <script>
        $('.transfer-link').click(function (e) {
            // e.preventDefault();
            var rs = confirm('You are about to transfer money from suspence account to this control number ('+$(this).data('control')+') \n\n\n Do you want to Continue?','Yes','No');
            return rs;
        });
    </script>
@stop
