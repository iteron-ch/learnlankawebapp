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
                    <a href="{{ route('managetest.index') }}" class="btn btn-default btn-sm">
                        <i class="glyphicon glyphicon-chevron-left"></i> {!! trans('admin/admin.back') !!} </a>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                {!! Form::model($test, ['route' => ['managetest.update', encryptParam($test['id'])], 'files' => true, 'method' => 'put', 'class' => 'form-horizontal panel','id' =>'taskfrm']) !!}
                <div class="form-body">
                    @include('admin.partials.form_error')
                    <div class="form-group">
                        <label class="control-label col-md-3">{{ trans('admin/task.key_stage') }} :</label>
                        <div class="col-md-4">
                            <p class="form-control-static">
                                {{ $test['key_stage_name'] }}
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">{{ trans('admin/task.year_group') }} :</label>
                        <div class="col-md-4">
                            <p class="form-control-static">
                                {{ $test['year_group_name'] }}
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">{{ trans('admin/task.subject') }} :</label>
                        <div class="col-md-4">
                            <p class="form-control-static">
                                {{ $test['subject'] }}
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">{{ trans('admin/task.question_set') }} :</label>
                        <div class="col-md-4">
                            <p class="form-control-static">
                                {{ $test['set_name'] }}
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('assign_date',trans('admin/task.assign_date'),['class'=>'control-label col-md-3'], TRUE)  !!}
                        <div class="col-md-4">
                            <div class="input-group date date-picker" data-date-format="dd/mm/yyyy" data-date-start-date="+0d">
                                {!! Form::text('assign_date',null,['class'=>'form-control','id' => 'assign_date']  )  !!}

                            </div>
                            <!-- /input-group -->
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('completion_date',trans('admin/task.completion_date'),['class'=>'control-label col-md-3'], TRUE)  !!}
                        <div class="col-md-4">
                            <div class="input-group date" id="date_completion_date" data-date-format="dd/mm/yyyy">
                                {!! Form::text('completion_date',null,['class'=>'form-control','id' => 'completion_date']  )  !!}
                            </div>
                            <!-- /input-group -->
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('gender',trans('admin/taskassignment.students'),['class'=>'control-label col-md-3'], TRUE)  !!}
                        <div class="col-md-4">
                            <div class="radio-list" data-error-container="#form_2_membership_error">
                                <label>
                                    {!! Form::radio('selection_type', 'Class',(isset($test['student_source']) && $test['student_source']=='Class')?'selected':'',['class' => 'student_selection_type']) !!}
                                    {!! trans('admin/taskassignment.class_students') !!} </label>
                                <label>
                                    {!! Form::radio('selection_type', 'Group',(isset($test['student_source']) && $test['student_source']=='Group')?'selected':'',['class' => 'student_selection_type']) !!}
                                    {!! trans('admin/taskassignment.group_students') !!} </label>
                            </div>
                        </div>
                    </div>  
                    <div class="form-group" id="student_container" style="display:none;">
                        {!! Form::labelControl('','',['class'=>'control-label col-md-3'])  !!}
                        <div class="col-md-3 div-student" id="student-Class">
                            {!! Form::select('students[]', $studentArray, null, ['id'=>'classstudents','multiple'=>'multiple','class'=>'form-control']) !!}
                        </div>
                    </div> 
                    <div class="form-group">
                        {!! Form::labelControl('','',['class'=>'control-label col-md-3'])  !!}
                        <div class="col-md-4">
                            {!! Form::text('classstudents_selected',count($selectedStudent) ? count($selectedStudent) : '',['id' => 'classstudents_selected' ,'class'=>'form-control'])  !!}
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                {!! Form::button('Submit', array('type' => 'submit', 'class' => 'btn green')) !!}
                                {!! HTML::link(route('managetest.index'), trans('admin/admin.cancel'), array('class' => 'btn default')) !!}
                            </div>
                        </div>
                    </div>

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
{!! JsValidator::formRequest($JsValidator, '#taskfrm'); !!}
<!-- END PAGE LEVEL SCRIPTS -->
{!! HTML::style('assets/global/plugins/jquery-ui/jquery-ui.min.css') !!}
{!! HTML::style('css/jquery.multiselect.css') !!}
{!! HTML::style('css/jquery.multiselect.filter.css') !!}
{!! HTML::script('js/jquery.multiselect.js') !!}
{!! HTML::script('js/jquery.multiselect.filter.js') !!}	
<script>
    jsMain = new Main("{{ trans('admin/admin.select_option') }}");
    var frmObj = $("#taskfrm");
    $(document).ready(function() {
        $('#assign_date').datepicker({
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
            minDate: new Date,
            onClose: function(selectedDate) {
                $("#completion_date").datepicker("option", "minDate", selectedDate);
            }
        });
        $('#completion_date').datepicker({
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            minDate: new Date,
            dateFormat: 'dd/mm/yy'
        });
        $('#assign_date,#completion_date', frmObj).change(function () {
            frmObj.validate().element($(this)); //
        });
        $(".student_selection_type").on('click', function() {
            getGroupClassStudentForTask();
        });
    });
    $(window).load(function() {
        <?php if($test['assign_id']){?>
            getGroupClassStudentForTask();
        <?php } ?>
    });
    function getGroupClassStudentForTask(selection) {
        jsMain.getGroupClassStudentForTask({
            urlClassStudent: "{{ route('manageclass.teacherclassstudent') }}",
            urlGroupStudent: "{{ route('managegroup.teachergroupstudent') }}",
            key_stage: "{{ $test['key_stage'] }}",
            year_group: "{{ $test['year_group'] }}",
            selection: $("input:radio[name=selection_type]:checked").val(),
            selectedStudent: "{{ json_encode($selectedStudent) }}"
        });     
    }
</script>
<style>
    div.checker input{opacity:1}
    .ui-multiselect-filter input {
        border: 1px solid #ddd;
        color: #333;
        font-size: 11px;
        height: auto;
    }
    #classstudents_selected{
        visibility: hidden!Important;
        height:1px;
        width:1px;
        padding: 0;
    }
</style>
@stop