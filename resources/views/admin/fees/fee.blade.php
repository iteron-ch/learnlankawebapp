@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => $manage_fees, 'trait_1' => $title])

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
                {!! Form::model($fees, ['route' => ['fees.update', $fees->id], 'method' => 'put', 'class' => 'form-horizontal','id'=>'fee_save']) !!}
                <div class="form-body">
                    @include('admin.partials.form_error')

                    <div class="form-group">
                        {!! Form::labelControl('school_sign_up_fee',trans('admin/fee.school_label'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        </label>
                        <div class="col-md-4">

                            {!! Form::text('school_sign_up_fee',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('parent_sign_up_fee',trans('admin/fee.parent_label'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">

                            {!! Form::text('parent_sign_up_fee',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::labelControl('per_student_fee',trans('admin/fee.per_student_label'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">

                            {!! Form::text('per_student_fee',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::labelControl('per_5_student_fee',trans('admin/fee.parent_student_label'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">

                            {!! Form::text('per_5_student_fee',null,['class'=>'form-control']  )  !!}

                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">

                            {!! Form::button('Save', array('type' => 'submit', 'class' => 'btn green')) !!}
                            <a href="{{  action('HomeController@dashboard')   }}" class="btn default">Cancel</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END FORM-->
        {!! Form::close() !!}
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    {{ $updated_title }}
                </div>
            </div>
            <div class="portlet-body form">
                <table id="fees-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/fee.school_label') !!}</th>
                            <th>{!! trans('admin/fee.parent_label') !!}</th>
                            <th>{!! trans('admin/fee.per_student_label') !!}</th>
                            <th>{!! trans('admin/fee.parent_student_label') !!}</th>
                            <th>{!! trans('admin/fee.updated_by') !!}</th>
                            <th>{!! trans('admin/fee.updated_on') !!}</th>
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
<script>
    var vars = {
        dataTable: "#fees-table",
        listUrl: "{{ route('fees.listrecord') }}",
    };
</script>
{!! HTML::script('js/fee.js') !!}

<!-- END PAGE LEVEL SCRIPTS -->
{!! JsValidator::formRequest('App\Http\Requests\FeesSaveRequest', '#fee_save'); !!}
@stop