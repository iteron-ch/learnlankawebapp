@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/admin.manageaccount'), 'trait_1' => trans('admin/admin.manageaccount')])
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    {{ trans('admin/admin.manageaccount') }}
                </div>
            </div>
            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li {!! Request::get('action') == ('') ? 'class="active"' : '' !!}>
                        <a href="#tab_1_1" data-toggle="tab">
                            {{ trans('admin/admin.basic_details') }} </a>
                    </li>
                    <li {!! Request::get('action') == ('changepassword') ? 'class="active"' : '' !!}>
                        <a href="#tab_1_2" data-toggle="tab">
                            {{ trans('admin/admin.change_password') }} </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade {!! Request::get('action') == ('') ? 'active in' : '' !!}" id="tab_1_1">
                        <!-- BEGIN FORM-->
                        {!! Form::model($user, ['route' => ['admin.updateprofile'], 'method' => 'post','files' => true, 'class' => 'form-horizontal panel','id' =>'schoolfrm']) !!}
                        <div class="form-body">
                            @include('admin.partials.form_error')
                            <div class="form-group">
                                {!! Form::labelControl('first_name',trans('admin/admin.first_name'),['class'=>'control-label col-md-3'], TRUE )  !!}
                                <div class="col-md-4">
                                    {!! Form::text('first_name',null,['class'=>'form-control']  )  !!}
                                </div>
                            </div>                     
                            <div class="form-group">
                                {!! Form::labelControl('last_name',trans('admin/admin.last_name'),['class'=>'control-label col-md-3'], TRUE )  !!}
                                <div class="col-md-4">
                                    {!! Form::text('last_name',null,['class'=>'form-control']  )  !!}
                                </div>
                            </div>  
                            <div class="form-group">
                                {!! Form::labelControl('image',trans('admin/admin.upload_photo'),['class'=>'control-label col-md-3'])  !!}

                                <div class="col-md-4 fileinput fileinput-{{ isset($fileinput_preview) && !empty($fileinput_preview) ? 'exists': 'new' }}" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                        {!! HTML::image(route('image','no-image.png')) !!}
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;">
                                        @if(isset($fileinput_preview) && !empty($fileinput_preview))
                                        {!! HTML::image($fileinput_preview) !!}
                                        @endif
                                    </div>
                                    {!! Form::hidden('image',null,['id'=>'edit_image'])  !!}
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
                                {!! Form::labelControl('username',trans('admin/admin.username'),['class'=>'control-label col-md-3'], TRUE )  !!}
                                <div class="col-md-4">
                                    {!! Form::text('username',null,['class'=>'form-control']  )  !!}
                                </div>
                            </div>                      
                            <div class="form-group">
                                {!! Form::labelControl('email',trans('admin/admin.email'),['class'=>'control-label col-md-3'], TRUE )  !!}
                                <div class="col-md-4">
                                    {!! Form::text('email',null,['class'=>'form-control']  )  !!}
                                </div>
                            </div>  

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        {!! Form::button('Submit', array('type' => 'submit', 'class' => 'btn green')) !!}
                                        {!! HTML::link(route('dashboard'), trans('admin/admin.cancel'), array('class' => 'btn default')) !!}
                                    </div>
                                </div>
                            </div>
                            <!--	</form>-->
                            {!! Form::close() !!}
                            <!-- END FORM-->
                        </div>
                    </div>
                    <div class="tab-pane fade {!! Request::get('action') == ('changepassword') ? 'active in' : '' !!}" id="tab_1_2">
                        @include('admin.user.changepassword')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop
@section('pagescripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->
{!! JsValidator::formRequest('App\Http\Requests\Admin\AdminUpdateProfileRequest', '#schoolfrm'); !!} 
{!! JsValidator::formRequest('App\Http\Requests\UserPasswordUpdateRequest', '#changePassfrm'); !!}   
<!-- END PAGE LEVEL SCRIPTS -->

<script>
    var frmObj = $("#schoolfrm");
</script>
@stop