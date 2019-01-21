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
                    <a href="{{ route('school.index') }}" class="btn btn-default btn-sm">
                        <i class="glyphicon glyphicon-chevron-left"></i> {!! trans('admin/admin.back') !!} </a>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                @yield('form')
                <div class="form-body">
                    @include('admin.partials.form_error')

                    <div class="form-group">
                        {!! Form::labelControl('school_name',trans('admin/school.school_name'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::text('school_name',null,['class'=>'form-control'])  !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::labelControl('school_type',trans('admin/school.school_type'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('school_type', $school_type, null, ['id' => 'school_type', 'class' => 'form-control select2me']) !!}   
                        </div>
                    </div>
                    <div class="form-group" id="other-school_type" style="display: {{ Input::old('school_type') == OTHER_VALUE ? 'display' : ( isset($user['school_type']) && $user['school_type'] == OTHER_VALUE ? 'display': 'none')}};">
                        {!! Form::labelControl('','',['class'=>'control-label col-md-3'])  !!}
                        <div class="col-md-4">
                            {!! Form::text('school_type_other',null,['class'=>'form-control'])  !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('image',trans('admin/admin.upload_photo'),['class'=>'control-label col-md-3'])  !!}

                        <div class="col-md-4 fileinput fileinput-{{ isset($fileinput_preview) && !empty($fileinput_preview) ? 'exists': 'new' }}" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                {!! HTML::image(route('image','no-image.png')) !!}
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;">
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

                    <div class="form-group">
                        {!! Form::labelControl('password',trans('admin/admin.password'),['class'=>'control-label col-md-3'], isset($user['id'])?FALSE:TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::password('password',['class'=>'form-control']  )  !!}
                        </div>
                    </div>                      
                    <div class="form-group">
                        {!! Form::labelControl('confirm_password',trans('admin/admin.confirm_password'),['class'=>'control-label col-md-3'], isset($user['id'])?FALSE:TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::password('confirm_password',['class'=>'form-control']  )  !!}
                        </div>
                    </div>   

                    <div class="form-group">
                        {!! Form::labelControl('telephone_no',trans('admin/admin.telephone_no'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::text('telephone_no',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div> 
                    <div class="form-group">
                        {!! Form::labelControl('whoyous_id',trans('admin/school.whoyous_id'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('whoyous_id', $who_you,null, ['id' => 'whoyous_id', 'class' => 'form-control select2me']) !!}   
                        </div>
                    </div> 
                    <div class="form-group" id="other-whoyous_id" style="display: {{ Input::old('whoyous_id') == OTHER_VALUE ? 'display' : ( isset($user['whoyous_id']) && $user['whoyous_id'] == OTHER_VALUE ? 'display': 'none')}};">
                        {!! Form::labelControl('','',['class'=>'control-label col-md-3'])  !!}
                        <div class="col-md-4">
                            {!! Form::text('whoyous_other',null,['class'=>'form-control'])  !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('postal_code',trans('admin/admin.postal_code'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::text('postal_code',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div> 
                    <div class="form-group">
                        {!! Form::labelControl('address',trans('admin/admin.address'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::textarea('address',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('country',trans('admin/admin.country'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('country', $country,UKCOUNTRYCODE, ['id' => 'country', 'class' => 'form-control select2me']) !!}   
                        </div>
                    </div> 
                    <div class="form-group">
                        {!! Form::labelControl('city',trans('admin/admin.city'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::text('city',null,['class'=>'form-control']  )  !!}
                            <ul class="citysuggetion" style="display:block; border-bottom:1px solid #e5e5e5; border-right:1px solid #e5e5e5; border-left:1px solid #e5e5e5; padding-left:0px;" id="city_list_id"></ul>
                        </div>
                    </div> 


                    <div class="form-group">
                        {!! Form::labelControl('county',trans('admin/admin.county'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('county', $county,null, ['id' => 'county', 'class' => 'form-control select2me']) !!}   
                        </div>
                    </div> 
                    
                    <div class="form-group">
                        <label for="is_traffic_light" class="control-label col-md-3">{!! trans('admin/admin.is_traffic_light') !!}</label>
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="icheck-inline">
                                    <label>
                                        {!! Form::checkbox('is_traffic_light', 1, 1, ['class' => 'field','id' => 'is_traffic_light']) !!}
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                   
                    
                    <div class="form-group">
                        {!! Form::labelControl('howfinds_id',trans('admin/tutor.how_you_find'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('howfinds_id', $howfind, null, ['id' => 'howfinds_id', 'class' => 'form-control select2me' ]) !!}   
                        </div>
                    </div> 

                    <div class="form-group" id="other-howfinds_id" style="display: {{ Input::old('howfinds_id') == OTHER_VALUE ? 'display' : ( isset($user['howfinds_id']) && $user['howfinds_id'] == OTHER_VALUE ? 'display': 'none')}};">
                        {!! Form::labelControl('','',['class'=>'control-label col-md-3'])  !!}
                        <div class="col-md-4">
                            {!! Form::text('howfinds_other',null,['class'=>'form-control'])  !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('dfe_number',trans('admin/school.dfe_number'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::text('dfe_number',null,['class'=>'form-control'])  !!}
                        </div>
                    </div>
                    
                    
                    @if(isset($user['id']))
                    <div class="form-group">
                        {!! Form::labelControl('status',trans('admin/admin.status'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('status', $status, null,['id' => 'status', 'class' => 'form-control select2me']) !!} 
                        </div>
                    </div> 
                    @endif
                    <div class="form-group">
                        <label class="control-label col-md-3">&nbsp;</label>
                        <div class="col-md-4">
                            {!! Form::checkbox('do_not_receive_email', 1, null, ['class' => 'field']) !!} {!! trans('admin/admin.do_not_receive_email') !!}
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                {!! Form::button(trans('admin/admin.submit'), array('type' => 'submit', 'class' => 'btn green')) !!}
                                {!! HTML::link(route('school.index'), trans('admin/admin.cancel'), array('class' => 'btn default')) !!}
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
{!! JsValidator::formRequest($JsValidator, '#schoolfrm'); !!}   
<!-- END PAGE LEVEL SCRIPTS -->
<script>
    jsMain = new Main();
            $(document).ready(function() {
    jsMain.toggleOther(['howfinds_id', 'howfinds_other'], {{ OTHER_VALUE }});
            jsMain.toggleOther(['school_type', 'whoyous_id'], {{ OTHER_VALUE }});
    });

</script>
@stop