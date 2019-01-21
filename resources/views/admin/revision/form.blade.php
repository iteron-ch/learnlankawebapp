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
                    <a href="{{ route('managerevision.index') }}" class="btn btn-default btn-sm">
                        <i class="glyphicon glyphicon-chevron-left"></i> {!! trans('admin/admin.back') !!} </a>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                @yield('form')
                <div class="form-body">
                    @include('admin.partials.form_error')
                    <div class="form-group">
                        {!! Form::labelControl('key_stage',trans('admin/task.key_stage'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('key_stage', $keyStage, null,['id' => 'key_stage', 'class' => 'form-control select2me']) !!} 
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::labelControl('year_group',trans('admin/task.year_group'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('year_group', array(), null,['id' => 'year_group', 'class' => 'form-control select2me']) !!} 
                        </div>
                    </div> 

                    <div class="form-group">
                        {!! Form::labelControl('subject',trans('admin/task.subject'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('subject', $subject ,null, ['id' => 'subject', 'class' => 'form-control select2me']) !!} 
                        </div>
                    </div> 
                    <div class="form-group">
                        {!! Form::labelControl('strand',trans('admin/task.strand'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('strand', array('' => 'select' ) ,null, ['id' => 'strand', 'class' => 'form-control select2me']) !!} 
                        </div>
                    </div> 

                    <div class="form-group">
                        {!! Form::labelControl('substrand',trans('admin/task.sub_strand'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('substrand[]', array() ,null, ['id' => 'substrand', 'class' => 'form-control select2me','multiple'=>'multiple']) !!} 
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('difficulty',trans('admin/task.difficulty'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('difficulty[]', $difficulty ,null, ['id' => 'difficulty', 'class' => 'form-control select2me','multiple'=>'multiple']) !!} 
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('assign_date',trans('admin/task.assign_date'),['class'=>'control-label col-md-3'], TRUE)  !!}
                        <div class="col-md-4">
                            <div class="input-group date" id="date_assign_date" data-date-format="dd/mm/yyyy">
                                {!! Form::text('assign_date',null,['class'=>'form-control','id' => 'assign_date','readonly'=>'']  )  !!}
                            </div>
                            <!-- /input-group -->
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('completion_date',trans('admin/task.completion_date'),['class'=>'control-label col-md-3'], TRUE)  !!}
                        <div class="col-md-4">
                            <div class="input-group date" id="date_completion_date" data-date-format="dd/mm/yyyy">
                                {!! Form::text('completion_date',null,['class'=>'form-control','id' => 'completion_date','readonly'=>'']  )  !!}
                            </div>
                            <!-- /input-group -->
                        </div>

                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('gender',trans('admin/taskassignment.students'),['class'=>'control-label col-md-3'], TRUE)  !!}
                        <div class="col-md-4">
                            <div class="radio-list" data-error-container="#form_2_membership_error">
                                <label>
                                    {!! Form::radio('selection_type', 'Class',(isset($taskassignment['student_source']) && $taskassignment['student_source']=='Class')?'selected':'',['class' => 'student_selection_type']) !!}
                                    {!! trans('admin/taskassignment.class_students') !!} </label>
                                <label>
                                    {!! Form::radio('selection_type', 'Group',(isset($taskassignment['student_source']) && $taskassignment['student_source']=='Group')?'selected':'',['class' => 'student_selection_type']) !!}
                                    {!! trans('admin/taskassignment.group_students') !!} </label>
                            </div>
                        </div>
                    </div>  
                    <div class="form-group" id="student_container" style="display:none;">
                        {!! Form::labelControl('','',['class'=>'control-label col-md-3'])  !!}
                        <div class="col-md-4 div-student" id="student-Class">
                            {!! Form::select('students[]', array(), null, ['id'=>'classstudents','multiple'=>'multiple','class'=>'form-control']) !!}
                        </div>
                    </div>


                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                {!! Form::button('Submit', array('type' => 'submit', 'class' => 'btn green')) !!}
                                {!! HTML::link(route('managerevision.index'), trans('admin/admin.cancel'), array('class' => 'btn default')) !!}
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
{!! HTML::style('css/jquery.multiselect.css') !!}
{!! HTML::style('css/jquery.multiselect.filter.css') !!}
{!! HTML::script('js/jquery.multiselect.js') !!}
{!! HTML::script('js/jquery.multiselect.filter.js') !!}	
<script>
    jsMain = new Main("{{ trans('admin/admin.select_option') }}");
    var yearDD = <?php echo $yearKeysJson ?>;
    var selected = "{{ !empty(Input::old('year_group')) ? Input::old('year_group') : ( !empty($task['year_group']) ? $task['year_group'] : '') }}";
    var strands = <?php echo $strands ?>;
    var substrands = <?php echo $substrands ?>;
    var selectedStrand = "{{ !empty(Input::old('strand')) ? Input::old('strand') : ( !empty($task['strand']) ? $task['strand'] : '') }}";
    var selectedSubStrand = "{{ !empty(Input::old('substrand')) ? Input::old('substrand') : ( !empty($task['substrand']) ? $task['substrand'] : '') }}";
    $(window).load(function() {
        jsMain.makeDropDownJsonData(yearDD, $("#year_group"), $("#key_stage").val(), selected);
        jsMain.makeDropDownJsonData(strands, $("#strand"), $("#subject").val(), selectedStrand);
        jsMain.makeDropDownJsonDataMultiple(substrands, $("#substrand"), $("#strand").val(), selectedSubStrand);
    });
    $(document).ready(function() {
        $("#key_stage").change(function() {
            jsMain.makeDropDownJsonData(yearDD, $("#year_group"), $(this).val(), '');
        });
        $("#subject").change(function() {
            $("#substrand").html('').select2();
            jsMain.makeDropDownJsonData(strands, $("#strand"), $(this).val(), '');
        });
        $("#strand").change(function() {
            jsMain.makeDropDownJsonDataMultiple(substrands, $("#substrand"), $(this).val(), '');
        });

        $(".student_selection_type").on('click', function() {
            var url = $(this).val() == 'Group' ? "{{ route('managegroup.teachergroupstudent') }}" : "{{ route('manageclass.teacherclassstudent') }}";
            $.ajax({
                url: url,
                type: 'POST',
                data: {key_stage: $("#key_stage").val(), year_group: $("#year_group").val()},
                success: function(data) { 
                    $("#student_container").show();
                    var html = '';
                    $.each(data, function(key, val) {console.log(val);
                        html += '<optgroup label="' + val.name + '">';
                        $.each(val.student, function(childkey, childval) {
                            html += '<option value="[' + key + '][' + childkey + ']">' + childval + '</option>';
                        });
                        html += '</optgroup>';
                    });
                    $("#classstudents").html(html).multiselect({
                        autoOpen: false,
                        noneSelectedText: "Select",
                        open: function()
                        {
                            $("input[type='search']:first").focus();
                        }
                    }).multiselectfilter();
                     $("#classstudents").multiselect('refresh');
                }
            });
        });

        $('#assign_date').datepicker({
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
//            maxDate: new Date,
//            yearRange: "-100:+0"
        });
        $('#completion_date').datepicker({
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
//            maxDate: new Date,
//            yearRange: "-100:+0"
        });
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
@stop