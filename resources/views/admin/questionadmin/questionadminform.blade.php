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
                    <a href="{{ route('questionadmin.index') }}" class="btn btn-default btn-sm">
                        <i class="glyphicon glyphicon-chevron-left"></i> {!! trans('admin/admin.back') !!} </a>
                </div> 
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                @yield('form')
                <div class="form-body">
                    @include('admin.partials.form_error')

                    <div class="form-group">
                        {!! Form::labelControl('first_name',trans('admin/questionadmin.first_name'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-3">
                            {!! Form::text('first_name',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div>                     
                    <div class="form-group">
                        {!! Form::labelControl('last_name',trans('admin/questionadmin.last_name'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-3">
                            {!! Form::text('last_name',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div>  
                    <div class="form-group">
                        {!! Form::labelControl('email',trans('admin/questionadmin.email'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-3">
                            {!! Form::text('email',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div>  
                    <div class="form-group">
                        {!! Form::labelControl('image',trans('admin/questionadmin.upload_photo'),['class'=>'control-label col-md-3'])  !!}

                        <div class="col-md-3 fileinput fileinput-{{ isset($fileinput_preview) && !empty($fileinput_preview) ? 'exists': 'new' }}" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                {!! HTML::image(route('image','no-image.png')) !!}
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;">
                                @if(isset($fileinput_preview) && !empty($fileinput_preview))
                                {!! HTML::image($fileinput_preview) !!}
                                @endif
                            </div>
                            <div>
                                <span class="btn default btn-file">
                                    <span class="fileinput-new">
                                        {{ trans('admin/admin.select_image') }}</span>
                                    <span class="fileinput-exists">
                                        {{ trans('admin/admin.change') }}</span>
                                    {!! Form::file('image', null) !!}
                                </span>
                                <a href="javascript:;" id class="btn default fileinput-exists" data-dismiss="fileinput">
                                    {{ trans('admin/admin.remove') }}</a>
                            </div>
                        </div>
                    </div>                     
                    <div class="form-group">
                        {!! Form::labelControl('username',trans('admin/questionadmin.username'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-3">
                            {!! Form::text('username',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div>                    

                    <div class="form-group">
                        {!! Form::labelControl('password',trans('admin/questionadmin.password'),['class'=>'control-label col-md-3'], isset($user['id'])?FALSE:TRUE )  !!}
                        <div class="col-md-3">
                            {!! Form::password('password',['class'=>'form-control']  )  !!}
                        </div>
                    </div>                      
                    <div class="form-group">
                        {!! Form::labelControl('confirm_password',trans('admin/questionadmin.confirm_password'),['class'=>'control-label col-md-3'], isset($user['id'])?FALSE:TRUE )  !!}
                        <div class="col-md-3">
                            {!! Form::password('confirm_password',['class'=>'form-control']  )  !!}
                        </div>
                    </div>  
                    @if(isset($QuestionAdminRepo))
                    <div class="form-group last">
                        {!! Form::labelControl('status',trans('admin/schoolclass.status'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-3">
                            {!! Form::select('status',$status, null, ['id'=>'status','class'=>'form-control select2me']) !!}
                        </div>
                    </div>
                    @endif
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                {!! Form::button('Submit', array('type' => 'submit', 'class' => 'btn green')) !!}
                                {!! HTML::link(route('questionadmin.index'), trans('admin/questionadmin.cancel'), array('class' => 'btn default')) !!}
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
{!! JsValidator::formRequest($JsValidator, '#questionadminfrm'); !!}     
<script>
    $(document).ready(function () {
//             $('.date-picker').datepicker({
//                autoclose: true
//            });
    });
</script>
@stop