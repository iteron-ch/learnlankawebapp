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
                        {!! Form::model($user, ['route' => ['tutor.updateprofile'], 'method' => 'psot', 'files' => true, 'class' => 'form-horizontal panel','id' =>'schoolfrm']) !!}
                        <div class="form-body">
                            @include('admin.partials.form_error')
                            <div class="form-group">
                                {!! Form::labelControl('first_name',trans('admin/tutor.first_name'),['class'=>'control-label col-md-3'], TRUE )  !!}
                                <div class="col-md-4">
                                    {!! Form::text('first_name',null,['class'=>'form-control']  )  !!}
                                </div>
                            </div>                     
                            <div class="form-group">
                                {!! Form::labelControl('last_name',trans('admin/tutor.last_name'),['class'=>'control-label col-md-3'], TRUE )  !!}
                                <div class="col-md-4">
                                    {!! Form::text('last_name',null,['class'=>'form-control']  )  !!}
                                </div>
                            </div>  
                            <div class="form-group">
                                {!! Form::labelControl('image',trans('admin/tutor.upload_photo'),['class'=>'control-label col-md-3'])  !!}

                                <div class="col-md-4 fileinput fileinput-{{ isset($fileinput_preview) && !empty($fileinput_preview) ? 'exists': 'new' }}" data-provides="fileinput">
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
                                {!! Form::labelControl('gender',trans('admin/tutor.gender'),['class'=>'control-label col-md-3'], TRUE)  !!}
                                <div class="col-md-4">
                                    <div class="radio-list" data-error-container="#form_2_membership_error">
                                        <label>
                                            {!! Form::radio('gender', 'Male', true) !!}
                                            {!! trans('admin/tutor.male') !!} </label>
                                        <label>
                                            {!! Form::radio('gender', 'Female') !!}
                                            {!! trans('admin/tutor.female') !!} </label>
                                    </div>
                                </div>
                            </div>  
                            <div class="form-group">
                                {!! Form::labelControl('date_of_birth',trans('admin/tutor.dob_date'),['class'=>'control-label col-md-3'], TRUE)  !!}
                                <div class="col-md-4">
                                    <div class="input-group date date-picker" data-date-format="dd-mm-yyyy">
                                        {!! Form::text('date_of_birth',null,['class'=>'form-control','readonly'=>'']  )  !!}
                                        <span class="input-group-btn">
                                            <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                    </div>
                                    <!-- /input-group -->
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::labelControl('username',trans('admin/tutor.username'),['class'=>'control-label col-md-3'], TRUE )  !!}
                                <div class="col-md-4">
                                    {!! Form::text('username',null,['class'=>'form-control']  )  !!}
                                </div>
                            </div>                    
                            <div class="form-group">
                                {!! Form::labelControl('email',trans('admin/tutor.email'),['class'=>'control-label col-md-3'], TRUE )  !!}
                                <div class="col-md-4">
                                    {!! Form::text('email',null,['class'=>'form-control']  )  !!}
                                </div>
                            </div>                  

                            <div class="form-group">
                                {!! Form::labelControl('telephone_no',trans('admin/tutor.telephone_no'),['class'=>'control-label col-md-3'], TRUE )  !!}
                                <div class="col-md-4">
                                    {!! Form::text('telephone_no',null,['class'=>'form-control']  )  !!}
                                </div>
                            </div> 
                            <div class="form-group">
                                {!! Form::labelControl('postal_code',trans('admin/tutor.postal_code'),['class'=>'control-label col-md-3'], TRUE )  !!}
                                <div class="col-md-4">
                                    {!! Form::text('postal_code',null,['class'=>'form-control']  )  !!}
                                </div>
                            </div> 
                            <div class="form-group">
                                {!! Form::labelControl('address',trans('admin/tutor.address'),['class'=>'control-label col-md-3'], TRUE )  !!}
                                <div class="col-md-4">
                                    {!! Form::textarea('address',null,['class'=>'form-control']  )  !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::labelControl('country',trans('admin/tutor.country'),['class'=>'control-label col-md-3'], TRUE )  !!}
                                <div class="col-md-4">
                                    {!! Form::select('country', $country,null, ['id' => 'country', 'class' => 'form-control select2me']) !!}   
                                </div>
                            </div> 
                            <div class="form-group">
                                {!! Form::labelControl('city',trans('admin/tutor.city'),['class'=>'control-label col-md-3'], TRUE )  !!}
                                <div class="col-md-4">
                                    {!! Form::text('city',null,['class'=>'form-control']  )  !!}
                                </div>
                            </div> 

                            <div class="form-group">
                                {!! Form::labelControl('county',trans('admin/tutor.county'),['class'=>'control-label col-md-3'], TRUE )  !!}
                                <div class="col-md-4">
                                    {!! Form::select('county', $county,null, ['id' => 'county', 'class' => 'form-control select2me']) !!}   
                                </div>
                            </div> 
                            
                            <div class="form-group">
                                {!! Form::labelControl('howfinds_id',trans('admin/tutor.how_you_find'),['class'=>'control-label col-md-3'], TRUE )  !!}
                                <div class="col-md-4">
                                    {!! Form::select('howfinds_id', $howfind, null, ['id' => 'howfinds_id', 'class' => 'form-control']) !!}   
                                </div>
                            </div>  

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        {!! Form::button('Submit', array('type' => 'submit', 'class' => 'btn green')) !!}
                                        {!! HTML::link(route('dashboard'), trans('admin/school.cancel'), array('class' => 'btn default')) !!}
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
{!! JsValidator::formRequest('App\Http\Requests\Tutor\TutorUpdateProfileRequest', '#schoolfrm'); !!} 
{!! JsValidator::formRequest('App\Http\Requests\UserPasswordUpdateRequest', '#changePassfrm'); !!}   
<!-- END PAGE LEVEL SCRIPTS -->
@stop