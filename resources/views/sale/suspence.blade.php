@extends('layouts.main')
@section('title')
    Sale | Suspence
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
            <h2 class="font-weight-light">Suspences</h2>
        </div>
        <div class="col-md-6">
            <form class="form-header" action="{{route('sale.suspence.search')}}" method="GET">
                <input class="au-input au-input--xl" type="text" name="search" placeholder="Search control Number">
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
                            <th>Control No.</th>
                            <th>Customer</th>
                            <th>Amount Paid</th>
                            <th>Plot details</th>
                            <th>Issuer</th>
                            <th>Created</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <h4>{{!empty($search)?"Search '$search' ":''}}({{$suspences->count()}}) Found</h4>
                    @foreach($suspences as $suspence)
                        <tr>
                            <td>
                                @if(!empty($search))
                                    <?php echo str_replace(strtoupper($search),"<span class='bg-warning'>".strtoupper($search)."</span>",$suspence->control) ?>
                                @else
                                    {{$suspence->control}}
                                @endif
                            </td>
                            <td>{{$suspence->customer}}</td>
                            <td>{{number_format($suspence->remain)}}</td>
                            <td>{{$suspence->plot}}</td>
                            <td>{{$suspence->user->name}}</td>
                            <td>{{$suspence->created_at->format('d/m/Y')}}</td>
                            <td>
                                <div class="table-data-feature">
                                    @if(auth()->user()->granted('payment_transfer'))
                                        <a href="{{route('sale.suspence.transfer.from',$suspence->id)}}" class="item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Transfer Money">
                                            <i class="fa fa-recycle"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-4 offset-4">
            {{$suspences->links()}}
        </div>
    </div>
@stop
