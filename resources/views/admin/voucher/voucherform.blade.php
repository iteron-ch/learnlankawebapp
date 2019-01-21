@extends('admin.layout._default')
@section('content')
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => $page_heading, 'trait_1' => $trait['trait_1'], 'trait_1_link' => $trait['trait_1_link'], 'trait_2' => $trait['trait_2']])
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN VALIDATION STATES-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    {{ $page_title }}
                </div>
                <div class="actions">
                    <a href="{{ route('voucher.index') }}" class="btn btn-default btn-sm">
                        <i class="glyphicon glyphicon-chevron-left"></i> {!! trans('admin/admin.back') !!} </a>
                </div> 
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                @yield('form')
                <div class="form-body">
                    @include('admin.partials.form_error')
                    <div class="form-group">
                        {!! Form::labelControl('voucher_code',trans('admin/voucher.voucher_code'),['class'=>'control-label col-md-2'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::text('voucher_code',null,['class'=>'form-control'])  !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('discount',trans('admin/voucher.discount'),['class'=>'control-label col-md-2'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::text('discount',null,['class'=>'form-control'])  !!}
                            {!! Form::select('discount_type',$discount_type,  null, ['id' => 'discount_type', 'class' => 'form-control']) !!}   
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('start_date',trans('admin/voucher.valid_from'),['class'=>'control-label col-md-2'], TRUE)  !!}
                        <div class="col-md-4">
                            <div class="input-group date date-picker" data-date-format="dd/mm/yyyy" id="validFrom">
                                {!! Form::text('start_date',null,['class'=>'form-control','readonly'=>'']  )  !!}
                                
                            </div>
                            <!-- /input-group -->
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('end_date',trans('admin/voucher.validTo'),['class'=>'control-label col-md-2'], TRUE)  !!}
                        <div class="col-md-4">
                            <div class="input-group date date-picker" data-date-format="dd/mm/yyyy">
                                {!! Form::text('end_date',null,['class'=>'form-control','readonly'=>'']  )  !!}
                                
                            </div>
                            <!-- /input-group -->
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::labelControl('user_type',trans('admin/voucher.user_type'),['class'=>'control-label col-md-2'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('user_type',$user_type,  null, ['id' => 'user_type', 'class' => 'form-control']) !!}   
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('status',trans('admin/voucher.status'),['class'=>'control-label col-md-2'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('status',$status,  null, ['id' => 'status', 'class' => 'form-control']) !!}   
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                {!! Form::button('Submit', array('type' => 'submit', 'class' => 'btn green')) !!}
                                {!! HTML::link('/voucher', trans('admin/voucher.cancel'), array('class' => 'btn default')) !!}
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
</div>
<!-- END PAGE CONTENT-->
@stop
@section('pagescripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->
{!! JsValidator::formRequest($JsValidator, '#voucherfrm'); !!}
<!-- END PAGE LEVEL SCRIPTS -->

<script>
    $(document).ready(function () {
        // initialize select2 tags
        $("#discount_type, #status, #user_type ").select2();
        $('#start_date').datepicker({
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
            //maxDate: new Date,
            // yearRange: "-100:+0"
        });
        $('#end_date').datepicker({
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
//            maxDate: new Date,
//            yearRange: "-100:+0"
        });
    });
</script>
@stop

