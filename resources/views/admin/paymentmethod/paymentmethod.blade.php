@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/paymentmethod.template_top_heading'), 'trait_1' => $title])
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN VALIDATION STATES-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    {{ $title }}
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <!-- <form action="{{ url("user/store") }}" method="post" id="form_sample_3" class="form-horizontal">-->
                {!! Form::model($paymentmethod, ['route' => ['paymentmethod.update', $paymentmethod->id], 'method' => 'put', 'class' => 'form-horizontal','id'=>'paymentmethod_save']) !!}
                <div class="form-body">
                    @include('admin.partials.form_error')

                    <div class="form-group">
                        {!! Form::labelControl('paypal_method',trans('admin/paymentmethod.type'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        </label>
                        <div class="col-md-4">
                            {!!	Form::checkbox('paypal_method', 'Paypal Standard',null,['class' => 'form-control'] ) !!}
                            Paypal Standard
                            {!!	Form::checkbox('paypal_method', 'Paypal Pro',null,['class' => 'form-control']) !!}
                            Paypal Pro
                        </div>
                    </div>


                    <div class="form-group">
                        {!! Form::labelControl('paypal_email',trans('admin/paymentmethod.email_label'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        </label>
                        <div class="col-md-4">

                            {!! Form::text('paypal_email',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('transaction_key',trans('admin/paymentmethod.transaction_key'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">

                            {!! Form::text('transaction_key',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::labelControl('transaction_user_id',trans('admin/paymentmethod.user_id_label'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">

                            {!! Form::text('transaction_user_id',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div>

                    <div class="form-group last">
                        {!! Form::labelControl('transaction_password',trans('admin/paymentmethod.password_label'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">

                            {!! Form::text('transaction_password',null,['class'=>'form-control']  )  !!}

                        </div>
                    </div>

                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">

                            {!! Form::button('Save', array('type' => 'submit', 'class' => 'btn green')) !!}
                            <a href="{{  action('CmsPagesController@index')   }}" class="btn default">Cancel</a>

                        </div>
                    </div>
                </div>
                <!--	</form>-->
                {!! Form::close() !!}
                <!-- END FORM-->
            </div>
            <!-- END VALIDATION STATES-->
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    {{ $title }}
                </div>
            </div>
            <div class="portlet-body form">
                <table id="payment-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/paymentmethod.transaction_key') !!}</th>
                            <th>{!! trans('admin/paymentmethod.user_id_label') !!}</th>
                            <th>{!! trans('admin/paymentmethod.password_label') !!}</th>
                            <th>{!! trans('admin/paymentmethod.email_label') !!}</th>
                            <th>{!! trans('admin/paymentmethod.type') !!}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>    
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop

@section('pagescripts')

<!-- BEGIN PAGE LEVEL PLUGINS -->
<!-- END PAGE LEVEL SCRIPTS -->
<script>
    var vars = {
        dataTable: "#payment-table",
        listUrl: "{{ route('paymentmethod.listrecord') }}",
    };
</script>
{!! HTML::script('js/payment.js') !!}
{!! JsValidator::formRequest('App\Http\Requests\PaymentmethodSaveRequest', '#paymentmethod_save'); !!}
@stop