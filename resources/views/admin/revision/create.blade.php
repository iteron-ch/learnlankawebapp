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
                {!! Form::open(['route' => ['managerevision.store'], 'method' => 'post', 'class' => 'form-horizontal','id' =>'taskfrm']) !!}
                <div class="form-body">
                    @include('admin.partials.form_error')
                    <div class="form-group">
                        {!! Form::labelControl('subject',trans('admin/task.subject'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('subject', $subject ,null, ['id' => 'subject', 'class' => 'form-control select2me']) !!} 
                        </div>
                    </div> 
                    <div class="form-group">
                        {!! Form::labelControl('key_stage',trans('admin/task.key_stage'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('key_stage', $keyStage, null,['id' => 'key_stage','class' => 'form-control select2me']) !!} 
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::labelControl('year_group',trans('admin/task.year_group'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('year_group', array('' => 'select' ), null,['id' => 'year_group', 'disabled' => 'disabled','class' => 'form-control select2me']) !!} 
                        </div>
                    </div> 
                    <div class="form-group">
                        {!! Form::labelControl('difficulty',trans('admin/task.difficulty'),['class'=>'control-label col-md-3'])  !!}
                        <div class="col-md-4">
                            {!! Form::select('difficulty[]', $difficulty ,null, ['id' => 'difficulty','class' => 'form-control select2me','multiple'=>'multiple']) !!} 
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::labelControl('strand',trans('admin/task.strand'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('strand', array('' => 'select' ) ,null, ['id' => 'strand','disabled' => 'disabled', 'class' => 'form-control select2me']) !!} 
                        </div>
                    </div> 

                    <div class="form-group">
                        {!! Form::labelControl('substrand',trans('admin/task.sub_strand'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('substrand[]', array() ,null, ['id' => 'substrand','disabled' => 'disabled', 'class' => 'form-control select2me','multiple'=>'multiple']) !!} 
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('assign_date',trans('admin/task.assign_date'),['class'=>'control-label col-md-3'], TRUE)  !!}
                        <div class="col-md-4">
                            <div class="input-group date" id="date_assign_date" data-date-format="dd/mm/yyyy">
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
                            <div class="radio-list">
                                <label>
                                    {!! Form::radio('selection_type', 'Class',(isset($taskassignment['student_source']) && $taskassignment['student_source']=='Class')?'selected':'',['disabled' => 'disabled','class' => 'student_selection_type']) !!}
                                    {!! trans('admin/taskassignment.class_students') !!} </label>
                                <label>
                                    {!! Form::radio('selection_type', 'Group',(isset($taskassignment['student_source']) && $taskassignment['student_source']=='Group')?'selected':'',['disabled' => 'disabled','class' => 'student_selection_type']) !!}
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
                    <div class="form-group">
                        {!! Form::labelControl('','',['class'=>'control-label col-md-3'])  !!}
                        <div class="col-md-4">
                            {!! Form::text('classstudents_selected',null,['id' => 'classstudents_selected' ,'class'=>'form-control'])  !!}
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
    var frmObj = $("#taskfrm");
    $(document).ready(function() {
        $("#classstudents").multiselect({
            autoOpen: false,
            noneSelectedText: "Select Student"
        });
        $("#subject").change(function() {
            getValidStrand();
        });
        $("#key_stage").change(function() {
            getValidStrand();
            if ($(this).val() != '') {
                jsMain.makeDropDownJsonData(yearDD, $("#year_group"), $(this).val(), '');
                $("#year_group").attr('disabled', false);
            } else {
                $("#year_group").attr('disabled', true).val('').select2();
            }
            setTimeout(function() {
                getGroupClassStudentForTask();
            }, 1000)

        });
        $("#year_group").change(function() {
            getGroupClassStudentForTask();
            getValidStrand();
        });
        $("#difficulty").on("change", function() {
            getValidStrand();
        });
        $("#strand").change(function() {
            getValidStrand($(this).val());
        });
        $("#substrand").change(function() {
            $("#btn_print").attr('disabled', false);
        });

        $(".student_selection_type").on('click', function() { 
            jsMain.getGroupClassStudentForTask({
                urlClassStudent: "{{ route('manageclass.teacherclassstudent') }}",
                urlGroupStudent: "{{ route('managegroup.teachergroupstudent') }}",
                key_stage: $("#key_stage").val(),
                year_group: $("#year_group").val(),
                selection: $("input:radio[name=selection_type]:checked").val(),
                selectedStudent: "{{ json_encode($selectedStudent) }}"
            });
        });

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
        $("#btn_print").click(function(e){
            e.preventDefault();
            var data = {
                subject: $("#subject").val(),
                key_stage: $("#key_stage").val(),
                year_group: $("#year_group").val(),
                difficulty: $("#difficulty").val(),
                strand: $("#strand").val(),
                substrand: $("#substrand").val(),
            };
            if (data.subject != '' && data.key_stage != '' && data.year_group != '' && data.strand != '' && data.substrand != '')
            {
                var diffc = data.difficulty == null ? '' : data.difficulty;
                var frameSrc = "printform?subject="+data.subject+"&key_stage="+data.key_stage+"&year_group="+data.year_group+"&strand="+data.strand+"&substrand="+data.substrand+"&difficulty="+diffc;
                var modal_iframe = $("#modal-iframe");
                modal_iframe.on('show.bs.modal', function() {
                    $(this).find('.modal-body body').css({
                        'background-color': 'white !important'
                    });
                    $('iframe').attr({src: frameSrc, allowfullscreen: true, webkitallowfullscreen: true, mozallowfullscreen: true});
                });
                modal_iframe.on('hide.bs.modal', function() {
                    $('iframe').attr("src", "");
                });
                modal_iframe.modal({show: true})
            }
            
        });
    });
    function getGroupClassStudentForTask() {
        var key_stage = $("#key_stage").val();
        var year_group = $("#year_group").val();
        var selection_type = $("input:radio[name=selection_type]");
        selection_type.parent().removeClass('checked');
        $("#student_container").hide();
        $("#classstudents").html('').multiselect();
        if (key_stage != '' && year_group != '') {
            $(".radio").removeClass('disabled');
            selection_type.attr('disabled', false);
        } else {
            $(".radio").addClass('disabled');
            selection_type.attr('disabled', true);
        }
    }
    function getValidStrand(strand) {
        var data = {
            subject: $("#subject").val(),
            key_stage: $("#key_stage").val(),
            year_group: $("#year_group").val(),
            difficulty: $("#difficulty").val(),
        };
        if (typeof strand !== "undefined") {
            data.strand = strand;
            $("#substrand").attr('disabled', true).html('').select2();
        } else {
            $("#strand,#substrand").attr('disabled', true).html('').select2();
        }
        $("#btn_print").attr('disabled', true);
        if (data.subject != '' && data.key_stage != '' && data.year_group != '')
        {
            $.ajax({
                url: "{{ route('question.questionstrandsforcreaterevision') }}",
                type: 'POST',
                data: data,
                success: function(data) {
                    if (typeof strand != "undefined") {
                        jsMain.makeDropDownJsonDataMultiple(data.substrand, $("#substrand"), strand, '');
                        $("#substrand").attr('disabled', false);
                    } else {
                        jsMain.jsonDropDown(data.strand, $("#strand"), '');
                        $("#strand").attr('disabled', false);
                    }
                }
            });
        }
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