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
                    <a href="{{ route('student.index') }}" class="btn btn-default btn-sm">
                        <i class="glyphicon glyphicon-chevron-left"></i> {!! trans('admin/admin.back') !!} </a>
                </div> 
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                @yield('form')
                <div class="form-body">

                    @include('admin.partials.form_error')
                    @if($sess_user_type != TUTOR)
                    <div class="form-group">
                        {!! Form::hidden('school', isset($school_id)?$school_id:'', ['id' => 'school', 'class' => 'form-control']) !!}  
                        @if(!empty($teacher_id))
                        {!! Form::hidden('teacher_id', isset($teacher_id)?$teacher_id:'', ['id' => 'teacher_id', 'class' => 'form-control']) !!}  
                        @else  
                        {!! Form::labelControl('teacher_id',trans('admin/student.teacher_name'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('teacher_id', $teacher,null, ['id' => 'teacher_id', 'class' => 'form-control']) !!}   
                        </div>                        
                        @endif
                    </div>
                    @else
                    {!! Form::hidden('tutor_id', isset($tutor_id)?$tutor_id:'', ['id' => 'tutor_id', 'class' => 'form-control']) !!}  
                    @endif
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
                        {!! Form::labelControl('key_stage',trans('admin/questionset.key_stage'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('key_stage', $keyStage, '2',['id' => 'key_stage', 'class' => 'form-control select2me']) !!} 
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::labelControl('year_group',trans('admin/questionset.year_group'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">

                            {!! Form::select('year_group', $yearKeys, isset($user['year_group'])?$user['year_group']:6,['id' => 'year_group', 'class' => 'form-control select2me']) !!} 

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
                    @if (session()->get('user')['user_type'] != TUTOR && isset($stClass) && isset($all_groups)) 
                    <div class="form-group">
                        {!! Form::labelControl('schoolclasses_id',trans('admin/student.schoolclasses_id'),['class'=>'control-label col-md-3'])  !!}
                        <div class="col-md-4">
                            {!! Form::select('schoolclasses_id', $stClass,null, ['id' => 'schoolclasses_id', 'class' => 'form-control select2me']) !!}   
                        </div>
                    </div> 
                    <div class="form-group">
                        {!! Form::labelControl('groups',trans('admin/admin.group'),['class'=>'control-label col-md-3'], FALSE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('groups[]', $all_groups, null, ['id'=>'groups','multiple'=>'multiple','class'=>'form-control select2me']) !!}
                        </div>
                    </div>
                    @endif       
                    <div class="form-group">
                        {!! Form::labelControl('username',trans('admin/admin.username'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::text('username',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div>                      
                    <div class="form-group">
                        {!! Form::labelControl('email',trans('admin/admin.email'),['class'=>'control-label col-md-3'], FALSE )  !!}
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
                        {!! Form::labelControl('telephone_no',trans('admin/admin.telephone_no'),['class'=>'control-label col-md-3'], FALSE )  !!}
                        <div class="col-md-4">
                            {!! Form::text('telephone_no',null,['class'=>'form-control']  )  !!}
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
                            {!! Form::select('country', $country, null , ['id' => 'country', 'class' => 'form-control select2me']) !!}   
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
                        {!! Form::labelControl('date_of_birth',trans('admin/student.dob'),['class'=>'control-label col-md-3'], TRUE)  !!}
                        <div class="col-md-4">
                            <div class="input-group date date-picker" data-date-format="dd/mm/yyyy" data-date-end-date="-1d" id="validFrom">
                                {!! Form::text('date_of_birth',null,['class'=>'form-control','readonly'=>'']  )  !!}
                                
                            </div>
                            <!-- /input-group -->
                        </div>
                    </div>
                    
                    <?php
                    if(!isset($tutor_id)) {
                    ?>
                    
                    <div class="form-group">
                        {!! Form::labelControl('ethnicity',trans('admin/student.ethnicity'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('ethnicity', $ethnicity, isset($user['id']) ? $user['ethnicity']:'NULL' , ['id' => 'ethnicity', 'class' => 'form-control select2me']) !!}   
                        </div>
                    </div> 
                    <div class="form-group">
                        {!! Form::labelControl('sen_provision',trans('admin/student.sen'),['class'=>'control-label col-md-3'], TRUE)  !!}
                        <div class="col-md-4">
                            <div class="radio-list" data-error-container="#form_2_membership_error">
                                <label class="radio-inline">
                                    {!! Form::radio('sen_provision', 1, null, ['id' => 'sen_yes', 'class' => 'form-control'] , TRUE) !!}
                                    {!! trans('admin/student.yes') !!} </label>
                                <label class="radio-inline">
                                    {!! Form::radio('sen_provision', 0, isset($user['id'])?$user['sen_provision']:'1', ['id' => 'sen_no', 'class' => 'form-control']) !!}
                                    {!! trans('admin/student.no') !!} </label>
                            </div>
                        </div>
                    </div> 
                    <div class="form-group" id="sub_sen_div" >
                        {!! Form::labelControl('sen_provision_desc',trans('admin/student.sen_provision_desc'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('sen_provision_desc',$sen_details ,null, ['id' => 'sen_provision_desc', 'class' => 'form-control select2me']) !!} 
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('sats_exempt',trans('admin/student.sats_exempt'),['class'=>'control-label col-md-3'], TRUE)  !!}
                        <div class="col-md-4">
                            <div class="radio-list" data-error-container="#form_2_membership_error">
                                <label class="radio-inline">
                                    {!! Form::radio('sats_exempt', 1,null , ['id' => 'sen_yes', 'class' => 'form-control']) !!}
                                    {!! trans('admin/student.yes') !!} </label>
                                <label class="radio-inline">
                                    {!! Form::radio('sats_exempt', 0, isset($user['id'])?$user['sats_exempt']:'1', ['id' => 'sen_no', 'class' => 'form-control']) !!}
                                    {!! trans('admin/student.no') !!} </label>
                            </div>
                        </div>
                    </div> 
                    <div class="form-group">
                        {!! Form::labelControl('ks1_average_point_score',trans('admin/student.ks1_average_point_score'),['class'=>'control-label col-md-3'], FALSE)  !!}
                        <div class="col-md-4">
                            <div class="radio-list" data-error-container="#form_2_membership_error">
                                <label class="radio-inline">
                                    {!! Form::radio('ks1_average_point_score', 1, isset($user['id'])?$user['ks1_average_point_score']:'1', ['id' => 'score', 'class' => 'form-control'] , TRUE) !!}
                                    {!! trans('admin/student.score') !!} </label>
                                <label class="radio-inline">
                                    {!! Form::radio('ks1_average_point_score', 2, null, ['id' => 'percentage', 'class' => 'form-control']) !!}
                                    {!! trans('admin/student.percentage') !!} </label>
                                <label class="radio-inline">
                                    {!! Form::radio('ks1_average_point_score', 3, null, ['id' => 'na', 'class' => 'form-control']) !!}
                                    {!! trans('admin/student.na') !!} </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="sub_ks1_avg" style="display: {{ (isset($user['ks1_average_point_score']) && $user['ks1_average_point_score'] == 3) ? 'none': 'show'}};">
                        {!! Form::labelControl('ks1_average_point_score_value',trans('admin/student.ks1_average_point_score_value'),['class'=>'control-label col-md-3'], FALSE )  !!}
                        <div class="col-md-4">
                            {!! Form::text('ks1_average_point_score_value',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('ks1_maths_baseline',trans('admin/student.ks1_maths_baseline'),['class'=>'control-label col-md-3'])  !!}
                        <div class="col-md-4">
                            <div class="radio-list" data-error-container="#form_2_membership_error">
                                <label class="radio-inline">
                                    {!! Form::radio('ks1_maths_baseline', 1, isset($user['id'])?$user['ks1_maths_baseline']:'1', ['id' => 'score', 'class' => 'form-control'] , TRUE) !!}
                                    {!! trans('admin/student.score') !!} </label>
                                <label class="radio-inline">
                                    {!! Form::radio('ks1_maths_baseline', 2, null, ['id' => 'percentage', 'class' => 'form-control']) !!}
                                    {!! trans('admin/student.percentage') !!} </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="sub_ks1_maths" >
                        {!! Form::labelControl('ks1_maths_baseline_value',trans('admin/student.ks1_maths_baseline_value'),['class'=>'control-label col-md-3'] )  !!}
                        <div class="col-md-4">
                            {!! Form::text('ks1_maths_baseline_value',(isset($users) && $user['ks1_maths_baseline_value']!='0.00')?$user['ks1_maths_baseline_value']:'',['class'=>'form-control']  )  !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('ks1_english_baseline',trans('admin/student.ks1_english_baseline'),['class'=>'control-label col-md-3'])  !!}
                        <div class="col-md-4">
                            <div class="radio-list" data-error-container="#form_2_membership_error">
                                <label class="radio-inline">
                                    {!! Form::radio('ks1_english_baseline', 1, isset($user['id'])?$user['ks1_english_baseline']:'1', ['id' => 'score', 'class' => 'form-control']) !!}
                                    {!! trans('admin/student.score') !!} </label>
                                <label class="radio-inline">
                                    {!! Form::radio('ks1_english_baseline', 2, null, ['id' => 'percentage', 'class' => 'form-control']) !!}
                                    {!! trans('admin/student.percentage') !!} </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="sub_ks1_english" >
                        {!! Form::labelControl('ks1_english_baseline_value',trans('admin/student.ks1_english_baseline_value'),['class'=>'control-label col-md-3'] )  !!}
                        <div class="col-md-4">
                            {!! Form::text('ks1_english_baseline_value',(isset($users) && $user['ks1_english_baseline_value']!='0.00')?$user['ks1_english_baseline_value']:'',['class'=>'form-control']  )  !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('ks2_maths_baseline',trans('admin/student.ks2_maths_baseline'),['class'=>'control-label col-md-3'])  !!}
                        <div class="col-md-4">
                            <div class="radio-list" data-error-container="#form_2_membership_error">
                                <label class="radio-inline">
                                    {!! Form::radio('ks2_maths_baseline', 1, isset($user['id'])?$user['ks2_maths_baseline']:'1', ['id' => 'score', 'class' => 'form-control']) !!}
                                    {!! trans('admin/student.score') !!} </label>
                                <label class="radio-inline">
                                    {!! Form::radio('ks2_maths_baseline', 2, null, ['id' => 'percentage', 'class' => 'form-control']) !!}
                                    {!! trans('admin/student.percentage') !!} </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="sub_ks2_maths" >
                        {!! Form::labelControl('ks2_maths_baseline_value',trans('admin/student.ks2_maths_baseline_value'),['class'=>'control-label col-md-3'] )  !!}
                        <div class="col-md-4">
                            {!! Form::text('ks2_maths_baseline_value',(isset($users) && $user['ks2_maths_baseline_value']!='0.00')?$user['ks2_maths_baseline_value']:'',['class'=>'form-control']  )  !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('ks2_english_baseline',trans('admin/student.ks2_english_baseline'),['class'=>'control-label col-md-3'])  !!}
                        <div class="col-md-4">
                            <div class="radio-list" data-error-container="#form_2_membership_error">
                                <label class="radio-inline">
                                    {!! Form::radio('ks2_english_baseline', 1,  isset($user['id'])?$user['ks2_maths_baseline']:'1', ['id' => 'score', 'class' => 'form-control']) !!}
                                    {!! trans('admin/student.score') !!} </label>
                                <label class="radio-inline">
                                    {!! Form::radio('ks2_english_baseline', 2, null, ['id' => 'percentage', 'class' => 'form-control']) !!}
                                    {!! trans('admin/student.percentage') !!} </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="sub_ks2_english" >
                        {!! Form::labelControl('ks2_english_baseline',trans('admin/student.ks2_english_baseline_value'),['class'=>'control-label col-md-3'] )  !!}
                        <div class="col-md-4">
                            {!! Form::text('ks2_english_baseline_value',(isset($users) && $user['ks2_english_baseline_value']!='0.00')?$user['ks2_english_baseline_value']:'',['class'=>'form-control']  )  !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('maths_target',trans('admin/student.maths_target'),['class'=>'control-label col-md-3'])  !!}
                        <div class="col-md-4">
                            <div class="radio-list" data-error-container="#form_2_membership_error">
                                <label class="radio-inline">
                                    {!! Form::radio('maths_target', 1,  isset($user['id'])?$user['maths_target']:'1', ['id' => 'score', 'class' => 'form-control']) !!}
                                    {!! trans('admin/student.score') !!} </label>
                                <label class="radio-inline">
                                    {!! Form::radio('maths_target', 2, null, ['id' => 'percentage', 'class' => 'form-control']) !!}
                                    {!! trans('admin/student.percentage') !!} </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="sub_maths_target" >
                        {!! Form::labelControl('maths_target',trans('admin/student.maths_target_value'),['class'=>'control-label col-md-3'] )  !!}
                        <div class="col-md-4">
                            {!! Form::text('maths_target_value',(isset($users) && $user['maths_target_value']!='0.00')?$user['maths_target_value']:'',['class'=>'form-control']  )  !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('english_target',trans('admin/student.english_target'),['class'=>'control-label col-md-3'])  !!}
                        <div class="col-md-4">
                            <div class="radio-list" data-error-container="#form_2_membership_error">
                                <label class="radio-inline">
                                        {!! Form::radio('english_target', 1, isset($user['id'])?$user['english_target']:'1', ['id' => 'score', 'class' => 'form-control']) !!}
                                    {!! trans('admin/student.score') !!} </label>
                                <label class="radio-inline">
                                    {!! Form::radio('english_target', 2, null, ['id' => 'percentage', 'class' => 'form-control']) !!}
                                    {!! trans('admin/student.percentage') !!} </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" id="sub_english_target" >
                        {!! Form::labelControl('english_target',trans('admin/student.english_target_value'),['class'=>'control-label col-md-3'] )  !!}
                        <div class="col-md-4">
                            {!! Form::text('english_target_value',(isset($users) && $user['english_target_value']!='0.00')?$user['english_target_value']:'',['class'=>'form-control']  )  !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('upn',trans('admin/student.upn'),['class'=>'control-label col-md-3'] )  !!}
                        <div class="col-md-4">
                            {!! Form::text('UPN',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div> 
                    <div class="form-group">
                        {!! Form::labelControl('date_of_entry',trans('admin/student.date_of_entry'),['class'=>'control-label col-md-3'])  !!}
                        <div class="col-md-4">
                            <div class="input-group date date-picker" data-date-format="dd/mm/yyyy" data-date-end-date="-1d" id="validFrom">
                                {!! Form::text('date_of_entry',null,['class'=>'form-control','readonly'=>'']  )  !!}
                                
                            </div>
                            <!-- /input-group -->
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('fsm',trans('admin/student.fsm'),['class'=>'control-label col-md-3'], TRUE)  !!}
                        <div class="col-md-4">
                            <div class="radio-list" data-error-container="#form_2_membership_error">
                                <label class="radio-inline">
                                    {!! Form::radio('fsm_eligibility', 1, null, ['id' => 'sen_yes', 'class' => 'form-control'], TRUE) !!}
                                    {!! trans('admin/student.yes') !!} </label>
                                <label class="radio-inline">
                                    {!! Form::radio('fsm_eligibility', 0, isset($user['id'])?$user['fsm_eligibility']:'1', ['id' => 'sen_no', 'class' => 'form-control']) !!}
                                    {!! trans('admin/student.no') !!} </label>
                            </div>
                        </div>
                    </div> 
                    <div class="form-group">
                        {!! Form::labelControl('eal',trans('admin/student.eal'),['class'=>'control-label col-md-3'], TRUE)  !!}
                        <div class="col-md-4">
                            <div class="radio-list" data-error-container="#form_2_membership_error">
                                <label class="radio-inline">
                                    {!! Form::radio('eal', 1, null, ['id' => 'sen_yes', 'class' => 'form-control'], TRUE) !!}
                                    {!! trans('admin/student.yes') !!} </label>
                                <label class="radio-inline">
                                    {!! Form::radio('eal', 0, isset($user['id'])?$user['eal']:'1', ['id' => 'sen_no', 'class' => 'form-control']) !!}
                                    {!! trans('admin/student.no') !!} </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('term_of_birth',trans('admin/student.term_of_birth'),['class'=>'control-label col-md-3'] )  !!}
                        <div class="col-md-4">
                            {!! Form::select('term_of_birth', $termOfBirth,null, ['id' => 'term_of_birth', 'class' => 'form-control select2me']) !!}   
                        </div>
                    </div> 
                    <?php
                    }
                    ?>
                    @if(isset($user['id']))
                    <div class="form-group">
                        {!! Form::labelControl('status',trans('admin/admin.status'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('status', $status, null ,['id' => 'status', 'class' => 'form-control select2me']) !!} 
                        </div>
                    </div> 
                    @endif
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                {!! Form::button(trans('admin/admin.submit'), array('type' => 'submit', 'class' => 'btn green')) !!}
                                {!! HTML::link(route('student.index'), trans('admin/admin.cancel'), array('class' => 'btn default')) !!}
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

{!! HTML::style('css/jquery.multiselect.css') !!}
{!! HTML::style('css/jquery.multiselect.filter.css') !!}
{!! HTML::script('js/jquery.multiselect.js') !!}
{!! HTML::script('js/jquery.multiselect.filter.js') !!}	 
{!! JsValidator::formRequest($JsValidator, '#groupfrm'); !!}
<!-- END PAGE LEVEL SCRIPTS -->
<script>
    $(document).ready(function () {

    });
</script>
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
    $('#date_of_birth').datepicker({
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
            maxDate: new Date,
            yearRange: "-100:+0"
    });
    $('#date_of_entry').datepicker({
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
            maxDate: new Date,
            yearRange: "-100:+0"
    });
    $("[name=sen_provision]").click(function () {
        if ($(this).val() == '1') {
            $('#sub_sen_div').show();
        }
        else {
            $('#sub_sen_div').hide();
        }
    });
    $("[name=ks1_average_point_score]").click(function () {
        if ($(this).val() == '3') {
            $('#ks1_average_point_score_value').val('');
            $('#sub_ks1_avg').hide();
        }
        else {
            $('#sub_ks1_avg').show();
        }
    });
   


</script>

{!! JsValidator::formRequest($JsValidator, '#studentfrm'); !!}  
@stop