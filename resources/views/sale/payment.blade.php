@extends('layouts.main')
@section('title')
    Payment | view
@stop
@section('PlotActive')
    active
@stop

@section('content')
    <style>
        .popover-header{
            color: white;
            background-color: #34444c;
            border-bottom: none;
            }
        .popover-body{
            color: white;
            background-color: #34444c;
        }
</style>
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
            <h2 class="font-weight-light">Payments</h2>
        </div>
            @if($controlNumber->status->id==1)
                @if(auth()->user()->granted('payment_add'))
                    <div class="col-md-3 offset-3">
                        <a href="#receive-payment-modal" data-toggle="modal" class="btn btn-success"><i class="zmdi zmdi-money"></i> Receive Payment</a>
                        <p>
                            @if($errors->has('amount'))
                            <small class="text-danger">Amount is required</small>
                        @endif
                        </p>
                        <p>
                        @if($errors->has('depositor'))
                            <small class="text-danger">Depositor name is required</small>
                        @endif
                        </p>
                    </div>
                @endif
            @else
                <div class="col-md-3 offset-3">
                    @if(auth()->user()->granted('payment_clearance'))
                    <a href="{{route('sale.clearance',$controlNumber->number)}}" target="_blank" class="btn btn-info"><i class="zmdi zmdi-print"></i> Print Clearance</a>
                    @endif
                </div>
            @endif
    </div>
    <div class="row" style="margin-top:2em;">
        <div class="col-md-12">
            <div class="form-content">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-5">
                                <strong style="float:right;">Control Number:</strong>
                            </div>
                            <div class="col-md-7">
                                {{$controlNumber->number}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <strong style="float:right;">Customer:</strong>
                            </div>
                            <div class="col-md-7">
                                {{$controlNumber->customer->name}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <strong style="float:right;">Gender:</strong>
                            </div>
                            <div class="col-md-7">
                                {{ucfirst($controlNumber->customer->gender->name)}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <strong style="float:right;">Created:</strong>
                            </div>
                            <div class="col-md-7">
                                {{$controlNumber->created_at->format('D d M Y')}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <strong style="float:right;">Default_at:</strong>
                            </div>
                            <div class="col-md-7">
                                {{$controlNumber->created_at->addDays($controlNumber->period->duration)->format('D d M Y H:i')}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <strong style="float:right;">Total Amount:</strong>
                            </div>
                            <div class="col-md-7">
                                {{number_format($controlNumber->totalRequiredAcq)}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <strong style="float:right;">Amount Paid:</strong>
                            </div>
                            <div class="col-md-7">
                                {{number_format($controlNumber->paid)}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <strong style="float:right;">Remained Amt:</strong>
                            </div>
                            <div class="col-md-7">
                                @if($controlNumber->remain<0)
                                    <p class="text-success">{{number_format(abs($controlNumber->remain))}} Exceeded</p>
                                @else
                                    {{number_format($controlNumber->remain)}}
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <strong style="float:right;">Payment Progress%</strong>
                            </div>
                            <div class="col-md-5">
                                @if($controlNumber->status->id==2)
                                    <div class="progress" style="height:20px">
                                        <div class="progress-bar progress-bar-striped progress-bar-success progress-bar-animated" style="width:{{$controlNumber->paidPercent}}%;height:20px">{{$controlNumber->paidPercent}}%</div>
                                    </div>
                                @else
                                    @if($controlNumber->paidPercent<50)
                                        <div class="progress" style="height:20px">
                                            <div class="progress-bar progress-bar-striped progress-bar-danger progress-bar-animated" style="width:{{$controlNumber->paidPercent}}%;height:20px">{{$controlNumber->paidPercent}}%</div>
                                        </div>
                                    @else
                                        <div class="progress" style="height:20px">
                                            <div class="progress-bar progress-bar-striped progress-bar-warning progress-bar-animated" style="width:{{$controlNumber->paidPercent}}%;height:20px">{{$controlNumber->paidPercent}}%</div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <strong style="float:right;">Plot details:</strong>
                            </div>
                            <div class="col-md-7">
                                {{$controlNumber->plot->number}}-{{$controlNumber->plot->block->code}}-{{$controlNumber->plot->block->locality->name}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <strong style="float:right;">Payment Period:</strong>
                            </div>
                            <div class="col-md-7">
                                {{$controlNumber->period->name}}
                            </div>
                        </div>
                        @if($controlNumber->status->id==1)
                            <div class="row">
                                <div class="col-md-5">
                                    <strong style="float:right;">Remained period:</strong>
                                </div>
                                <div class="col-md-7">
                                    @if($controlNumber->remainPeriod<=0)
                                        <p class="text-primary">Defaulter</p>
                                    @else
                                        <p>{{$controlNumber->created_at->addDays($controlNumber->period->duration)->diffForHumans()}}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-5">
                                <strong style="float:right;">Status:</strong>
                            </div>
                            <div class="col-md-7 {{$controlNumber->status->id==1?'text-danger':'text-success'}}">
                                {{$controlNumber->status->id==1?'Incomplete':'Complete'}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 2em;">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>R/No.</th>
                                    <th>Amount</th>
                                    <th>Slip</th>
                                    <th>Date</th>
                                    <th>Deposited by</th>
                                    <th>Issuer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($controlNumber->payments()->get())>0)
                                    @php($payments = $controlNumber->payments()->orderBy('created_at','desc')->get())
                                    @php($num=1)
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>{{$payment->id}}</td>
                                            <td>{{number_format($payment->amount)}}</td>
                                            <td>
                                                @if($payment->hasTransfer)
                                                    <a style="cursor: pointer;" title="Transfer From Suspence" data-toggle="popover" data-trigger="hover" data-placement="right" data-content="{{$payment->myTransferInfo}}">{{$payment->slip}}</a>
                                                @else
                                                    {{$payment->slip}}
                                                @endif
                                            </td>
                                            <td>{{$payment->created_at->format('d/m/Y H:i')}}</td>
                                            <td>{{$payment->depositor}}</td>
                                            <td>{{$payment->user->name}}</td>
                                            <td>
                                                <div class="table-data-feature">
                                                    @if(auth()->user()->granted('payment_edit') && !$payment->hasTransfer)
                                                        <a href="#edit-payment-modal" data-id="{{$payment->id}}" data-amount="{{$payment->amount}}" data-depositor="{{$payment->depositor}}" data-slip="{{$payment->slip}}" data-toggle="modal" title="Edit" class="item">
                                                            <i class="zmdi zmdi-edit text-primary"></i>
                                                        </a>
                                                    @endif
                                                    @if(auth()->user()->granted('payment_delete') && !$payment->hasTransfer)
                                                        <a href="#" title="Delete" class="item">
                                                            <i class="zmdi zmdi-delete text-danger"></i>
                                                        </a>
                                                    @endif
                                                    @if(auth()->user()->granted('payment_print'))
                                                        <a href="{{route('sale.receipt',$payment->id)}}" title="Print Receipt" class="item" target="_blank">
                                                            <i class="zmdi zmdi-print text-info"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @php($num++)
                                    @endforeach
                                @else
                                    <h4>No payments made</h4>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('modal')
    @if(auth()->user()->granted('payment_add'))
        <div class="modal" id="receive-payment-modal">
            <div class="modal-dialog">
            <div class="modal-content">
            {!! Form::open(['route'=>['sale.payment.receive',$controlNumber->number]],['id'=>'payment-receive-form']) !!}
              <div class="modal-header">
                <h4 class="modal-title">Receive Payments</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <div class="row">
                    <div class="col-md-10 offset-1">
                        <div class="form-group">
                            <label>Amount</label>
                            {!! Form::number('amount',null,['id'=>'amount','class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label>Depositor name</label>
                            {!! Form::text('depositor',null,['id'=>'depositor','class'=>'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label>Bank Slip (optional)</label>
                            {!! Form::text('slip',null,['id'=>'slip','class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>

              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-success">Submit</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
              </div>
            {!! Form::close() !!}
        </div>
      </div>
        </div>
    @endif
    @if(auth()->user()->granted('payment_edit'))
        <div class="modal" id="edit-payment-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    {!! Form::open(['route'=>['sale.payment.edit','']],['id'=>'payment-edit-form']) !!}
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Payments</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-10 offset-1">
                                <div class="form-group">
                                    <label>Amount</label>
                                    {!! Form::number('amount',null,['id'=>'edit_amount','class'=>'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label>Depositor name</label>
                                    {!! Form::text('depositor',null,['id'=>'edit_depositor','class'=>'form-control']) !!}
                                </div>
                                <div class="form-group">
                                    <label>Bank Slip (optional)</label>
                                    {!! Form::text('slip',null,['id'=>'edit_slip','class'=>'form-control']) !!}
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Update</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    @endif
@stop

@section('script')
    <script>
        $(document).ready(function(){
            $('[data-toggle="popover"]').popover();
        });
        $('#edit-payment-modal').on('show.bs.modal',function (e) {
            var btn = $(e.relatedTarget);
            var modal = $(this);
            modal.find('#edit_amount').val(btn.data('amount'));
            modal.find('#edit_depositor').val(btn.data('depositor'));
            modal.find('#edit_slip').val(btn.data('slip'));
            var url = '{{route('sale.payment.edit','')}}/'+btn.data('id');
            modal.find('form').attr('action',url);
        });
    </script>
@stop
