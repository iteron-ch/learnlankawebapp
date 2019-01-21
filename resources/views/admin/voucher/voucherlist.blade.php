@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/voucher.manage_voucher'), 'trait_1' => trans('admin/voucher.voucher')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! trans('admin/voucher.voucher') !!}
                </div>
                <div class="actions">
                    <a href="{{ route('voucher.create') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-plus"></i> {!! trans('admin/voucher.add_voucher') !!} </a>

                </div>                
            </div>
            <div class="portlet-body">
                {!! Form::open(array('url' => 'voucher','class'=>'form-horizontal','id' =>'search-form' )) !!}
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('voucher_code',trans('admin/voucher.voucher_code'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::text('voucher_code',null,['id' => 'voucher_code','placeholder'=>trans('admin/voucher.search_voucher_code'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('user_type',trans('admin/voucher.user_type'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::select('user_type', $user_type, null ,['id' => 'user_type', 'class' => 'form-control']) !!} 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('status',trans('admin/voucher.status'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::select('status', $status, null ,['id' => 'status', 'class' => 'form-control']) !!} 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="col-md-12">
                                    {!! Form::button(trans('admin/admin.search'), array('type' => 'submit', 'class' => 'btn btn-primary')) !!} 
                                    {!! Form::button(trans('admin/admin.reset'), array('type' => 'reset', 'id'=>'reset','class' => 'btn btn-primary grey')) !!} 
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr style="border-top: 1px solid #26a69a;" >
                </div>
                {!! Form::close() !!}   
                <table id="vouchers-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/voucher.voucher_code') !!}</th>
                            <th>{!! trans('admin/voucher.discount') !!}</th>
                            <th>{!! trans('admin/voucher.discount_type') !!}</th>
                            <th>{!! trans('admin/voucher.valid_from') !!}</th>
                            <th>{!! trans('admin/voucher.validTo') !!}</th>
                            <th>{!! trans('admin/voucher.user_type') !!}</th>
                            <th>{!! trans('admin/voucher.status') !!}</th>
                            <th>{!! trans('admin/voucher.actions') !!}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
    <!-- END PAGE CONTENT-->
    @stop

    @section('pagescripts')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script>
        var vars = {
            dataTable: "#vouchers-table",
            listUrl: "{{ route('voucher.listrecord') }}",
            addUrl: "{{ route('voucher.create') }}",
            deleteUrl: "{{ route('voucher.delete') }}",
            confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
            successMsg: "{!! trans('admin/admin.delete-success') !!}"
        };
        $(document).ready(function () {
            $("#status").select2();
            $("#user_type").select2();
        });
    </script>
    {!! HTML::script('js/voucher.js') !!}
    @stop