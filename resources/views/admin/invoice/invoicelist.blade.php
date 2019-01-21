@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/invoice.view_invoice'), 'trait_1' => trans('admin/invoice.your_invoice')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>Invoice
                </div>
            </div>
            <div class="portlet-body">
                {!! Form::open(array('url' => 'tutor','class'=>'form-horizontal','id' =>'search-form' )) !!}
                <div class="form-body">
                    <div class="row">   
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('payment_id',trans('admin/invoice.invoice_no'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-6">
                                    {!! Form::text('payment_id',null,['id'=>'payment_id','placeholder'=>trans('admin/invoice.invoice_no'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('no_of_students',trans('admin/invoice.no_of_students'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-6">
                                    {!! Form::text('no_of_students',null,['id'=>'no_of_students','placeholder'=>trans('admin/invoice.no_of_students'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('amount',trans('admin/invoice.amount'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-6">
                                    {!! Form::text('amount',null,['id'=>'amount','placeholder'=>trans('admin/invoice.amount'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-12">
                                    {!! Form::button(trans('admin/admin.search'), array('type' => 'submit', 'class' => 'btn btn-primary')) !!} 
                                    {!! Form::button(trans('admin/admin.reset'), array('type' => 'reset', 'id'=>'reset','class' => 'btn btn-primary grey')) !!} 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
            <hr style="border-top: 1px solid #26a69a;" >
        </div>
        {!! Form::close() !!}
        <table id="users-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr class="heading">
                    <th>{!! trans('admin/invoice.invoice_no') !!}</th>
                    <th>{!! trans('admin/admin.username') !!}</th>
                    <th>{!! trans('admin/admin.email') !!}</th>
                    <!--<th>{!! trans('admin/invoice.start_date') !!}</th>
                    <th>{!! trans('admin/invoice.end_date') !!}</th>-->
                    <th>{!! trans('admin/invoice.no_of_students') !!}</th>
                    <th>{!! trans('admin/invoice.amount_gbp') !!}</th>
                    <th>{!! trans('admin/invoice.payment_type') !!}</th>
                    <th>{!! trans('admin/admin.status') !!}</th>
                    <th>{!! trans('admin/invoice.action') !!}</th>
                </tr>
            </thead>
        </table>

    </div>

</div>
</div>

<!-- END EXAMPLE TABLE PORTLET-->
</div>
<!-- END PAGE CONTENT-->
@stop

@section('pagescripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<!-- END PAGE LEVEL SCRIPTS -->
<script>
    var vars = {
        dataTable: "#users-table",
        listUrl: "{{ route('invoice.listrecord') }}",
    };
    $(document).ready(function () {
        //$("#status").select2();
    });
</script>
{!! HTML::script('js/invoice.js') !!}

@stop