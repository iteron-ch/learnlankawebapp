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
                    <a href="{{ route('notification.index') }}" class="btn btn-default btn-sm">
                        <i class="glyphicon glyphicon-chevron-left"></i> {!! trans('admin/notification.back') !!} </a>
                </div> 
            </div>
            <div class="actions">
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                @yield('form')
                <div class="form-body">
                    <div class="row">
                        @include('admin.partials.form_error')
                        <div class="form-group">
                            {!! Form::labelControl('title',trans('admin/notification.title'),['class'=>'control-label col-md-2'], TRUE )  !!}
                            <div class="col-md-3">
                                {!! Form::text('title',null,['id'=>'title','class'=>'form-control'])  !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::labelControl('description',trans('admin/notification.description'),['class'=>'control-label col-md-2'], TRUE )  !!}
                            <div class="col-md-3">
                                {!! Form::textarea('description',null,['id'=>'description','class'=>'form-control'])  !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::labelControl('user_type',trans('admin/notification.user_type'),['class'=>'control-label col-md-2'], TRUE )  !!}
                            <div class="col-md-3">
                                {!! Form::select('user_type[]', $selected_visible, null, ['id'=>'user_type','multiple'=>'multiple','multiselect'=>'multiselect','class'=>'form-control']) !!}
                            </div>
                        </div>
                        @if(isset($notification_list['id']))
                        <div class="form-group">
                            {!! Form::labelControl('status',trans('admin/admin.status'),['class'=>'control-label col-md-2'], TRUE )  !!}
                            <div class="col-md-2">
                                {!! Form::select('status', $status, null,['id' => 'status', 'class' => 'form-control select2me']) !!} 
                            </div>
                        </div> 
                        @endif
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    {!! Form::button('Submit', array('type' => 'submit', 'class' => 'btn green')) !!}
                                    {!! HTML::link('/notification', trans('admin/notification.cancel'), array('class' => 'btn default')) !!}
                                </div>
                            </div>
                        </div>
                        <!--	</form>-->
                        {!! Form::close() !!}
                        <!-- END FORM-->
                    </div>
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
{!! HTML::style('assets/global/plugins/jquery-ui/jquery-ui.min.css') !!}
{!! HTML::style('css/jquery.multiselect.css') !!}
{!! HTML::style('css/jquery.multiselect.filter.css') !!}
{!! HTML::script('js/jquery.multiselect.js') !!}
{!! HTML::script('js/jquery.multiselect.filter.js') !!}	 
{!! JsValidator::formRequest($JsValidator, '#notificationfrm'); !!}
<!-- END PAGE LEVEL SCRIPTS -->
<style>
    div.checker input{opacity:1}
    .ui-multiselect-filter input {
        border: 1px solid #ddd;
        color: #333;
        font-size: 11px;
        height: auto;
    }
</style>
<script>
    jsMain = new Main("{{ trans('admin/admin.select_option') }}");
    $(document).ready(function () {
        $("#user_type").multiselect();
        var max_fields = 20; //maximum input boxes allowed
        var wrapper = $(".input_fields_wrap"); //Fields wrapper
        var add_button = $(".add_field_button"); //Add button ID
    });


</script>
@stop
