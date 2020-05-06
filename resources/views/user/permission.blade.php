@extends('layouts.main')
@section('title')
    User | Permission
@stop
@section('UserActive')
    class="active"
@stop

@section('content')
    <div class="row">
        <div class="col-md-5 offset-3">
            @if(Session('pmessage'))
                <div class="sufee-alert alert with-close alert-success alert-dismissible fade show">
                    {{Session('pmessage')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            @endif
        </div>
    </div>
    @if($user)
        <div class="row m-t-30">
            <div class="col-md-11 offset-1">
                <div class="form-content" style="margin-bottom: 2em;">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>User Permissions</h3>
                        </div>
                        <div class="col-md-6">
                            <div class="table-data-feature">
                                <a href="{{route('user.permission.print',$user->slug)}}" title="Print Receipt" class="item" target="_blank">
                                    <i class="zmdi zmdi-print text-info"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 1em;">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4">
                                    <p style="float: right;">Name:</p>
                                </div>
                                <div class="col-md-8">
                                    <p>{{$user->name}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <p style="float: right;">Email:</p>
                                </div>
                                <div class="col-md-8">
                                    <p>{{$user->email}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <p style="float: right;">Role:</p>
                                </div>
                                <div class="col-md-8">
                                    <p>{{$user->role->name}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <p style="float: right;">Gender:</p>
                                </div>
                                <div class="col-md-8">
                                    <p>{{$user->gender->name}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <p style="float: right;">Phone#:</p>
                                </div>
                                <div class="col-md-8">
                                    <p>{{$user->phone}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <p style="float: right;">Status:</p>
                                </div>
                                <div class="col-md-8">
                                    <p>{{$user->status->id==1?'Active':'Inactive'}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::model($user,['route'=>['user.grant',$user->slug]],['id'=>'user-permission-form']) !!}
                        <div class="row" style="margin-top: 2em;">
                            <div class="col-md-12">
                                <div class="row" style="margin-bottom: 1em;">
                                    <div class="col-md-5">
                                        <button id="selectAll" type="button" class="btn btn-info">Select All</button>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <button id="unselectAll" type="button" class="btn btn-secondary">UnSelect All</button>&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <div class="col-md-7">
                                        <small class="text-danger">{{Session('message')?Session('message'):''}}</small>
                                    </div>
                                </div>
                                <table id="permission-table" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Module</th>
                                            <th>Permission</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1.</td>
                                            <td>PROJECT</td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="project_add" {{$user->granted('project_add')?'checked':''}}> Add</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="project_edit" {{$user->granted('project_edit')?'checked':''}}> Edit</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label><input class="view" type="checkbox" name="project_view" {{$user->granted('project_view')?'checked':''}}> View</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="project_delete" {{$user->granted('project_delete')?'checked':''}}> Delete</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2.</td>
                                            <td>LOCALITY</td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="locality_add" {{$user->granted('locality_add')?'checked':''}}> Add</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="locality_edit" {{$user->granted('locality_edit')?'checked':''}}> Edit</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label><input class="view" type="checkbox" name="locality_view" {{$user->granted('locality_view')?'checked':''}}> View</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="locality_delete" {{$user->granted('locality_delete')?'checked':''}}> Delete</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3.</td>
                                            <td>BLOCK</td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="block_add" {{$user->granted('block_add')?'checked':''}}> Add</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="block_edit" {{$user->granted('block_edit')?'checked':''}}> Edit</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label><input class="view" type="checkbox" name="block_view" {{$user->granted('block_view')?'checked':''}}> View</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="block_delete" {{$user->granted('block_delete')?'checked':''}}> Delete</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4.</td>
                                            <td>PLOT</td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="plot_add" {{$user->granted('plot_add')?'checked':''}}> Add</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="plot_edit" {{$user->granted('plot_edit')?'checked':''}}> Edit</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label><input class="view" type="checkbox" name="plot_view" {{$user->granted('plot_view')?'checked':''}}> View</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="plot_delete" {{$user->granted('plot_delete')?'checked':''}}> Delete</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>5.</td>
                                            <td>SALE</td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="control_add" {{$user->granted('control_add')?'checked':''}}> Add</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="control_edit" {{$user->granted('control_edit')?'checked':''}}> Edit</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="control_invoice" {{$user->granted('control_invoice')?'checked':''}}> Print Invoice</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label><input class="view" type="checkbox" name="control_view" {{$user->granted('control_view')?'checked':''}}> View</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="control_delete" {{$user->granted('control_delete')?'checked':''}}> Delete</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>6.</td>
                                            <td>DEFAULTER</td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label><input class="view" type="checkbox" name="defaulter_view" {{$user->granted('defaulter_view')?'checked':''}}> View</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="defaulter_revoke" {{$user->granted('defaulter_revoke')?'checked':''}}> Revoke</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="defaulter_torelate" {{$user->granted('defaulter_torelate')?'checked':''}}> Add Days</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>7.</td>
                                            <td>USER</td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="user_add" {{$user->granted('user_add')?'checked':''}}> Add</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="user_edit" {{$user->granted('user_edit')?'checked':''}}> Edit</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="user_permission" {{$user->granted('user_permission')?'checked':''}}> Permissions</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label><input class="view" type="checkbox" name="user_view" {{$user->granted('user_view')?'checked':''}}> View</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="user_delete" {{$user->granted('user_delete')?'checked':''}}> Delete</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>8.</td>
                                            <td>CONSTANTS</td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="constant_add" {{$user->granted('constant_add')?'checked':''}}> Add</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="constant_edit" {{$user->granted('constant_edit')?'checked':''}}> Edit</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label><input class="view" type="checkbox" name="constant_view" {{$user->granted('constant_view')?'checked':''}}> View</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="constant_delete" {{$user->granted('constant_delete')?'checked':''}}> Delete</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>9.</td>
                                            <td>PAYMENT</td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="payment_add" {{$user->granted('payment_add')?'checked':''}}> Add</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="payment_edit" {{$user->granted('payment_edit')?'checked':''}}> Edit</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="payment_print" {{$user->granted('payment_print')?'checked':''}}> Print Receipt</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label><input class="view" type="checkbox" name="payment_view" {{$user->granted('payment_view')?'checked':''}}> View</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="payment_delete" {{$user->granted('payment_delete')?'checked':''}}> Delete</label>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="payment_clearance" {{$user->granted('payment_clearance')?'checked':''}}> Issue Clearance</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label><input class="view-checker" type="checkbox" name="payment_transfer" {{$user->granted('payment_transfer')?'checked':''}}> Transfer Suspence</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 2em;">
                            <div class="col-md-3 offset-9">
                                <button type="submit" class="btn btn-success btn-block btn-lg">Grant</button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="text-muted" style="margin-top: 10em;">Select user first!</h2>
            </div>
        </div>
    @endif
@stop

@section('script')
    <script>
        $('.view-checker').change(function () {
            if($(this).is(':checked')){
                var td = $(this).closest('td');
                var checkbox = td.find('.view');
                checkbox.prop('checked',true);
            }
        });

        $('.view').change(function () {
            if(!$(this).is(':checked')){
                var td = $(this).closest('td');
                var checkboxes = td.find('.view-checker');
                checkboxes.prop('checked',false);
            }
        });

        $('#selectAll').click(function () {
            $('#permission-table input:checkbox').prop('checked',true);
        });
        $('#unselectAll').click(function () {
            $('#permission-table input:checkbox').prop('checked',false);
        });
    </script>
@stop
