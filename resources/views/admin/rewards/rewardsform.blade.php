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
                    <a href="{{ $trait['trait_1_link'] }}" class="btn btn-default btn-sm">
                        <i class="glyphicon glyphicon-chevron-left"></i> {!! trans('admin/helpcentre.back') !!} </a>
                </div> 
            </div>
            <div class="actions">
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                @yield('form')
                <div class="form-body">
                    @include('admin.partials.form_error')

                    <div class="form-group">
                        {!! Form::labelControl('subject',trans('admin/task.subject'),['class'=>'control-label col-md-2'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('subject', $subject ,null, ['id' => 'subject', 'class' => 'form-control select2me']) !!} 
                        </div>
                    </div> 
                    @if($task_type == TEST)
                    <div class="form-group">
                        {!! Form::labelControl('question_set',trans('admin/task.question_set'),['class'=>'control-label col-md-2'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('question_set', array('' => 'select' ) ,null, ['id' => 'question_set', 'class' => 'form-control select2me']) !!} 
                        </div>
                    </div>
                    @endif
                    @if($task_type == REVISION)
                    <div class="form-group">
                        {!! Form::labelControl('strand',trans('admin/task.strand'),['class'=>'control-label col-md-2'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('strand', array('' => 'select' ) ,null, ['id' => 'strand', 'class' => 'form-control select2me']) !!} 
                        </div>
                    </div> 

                    <div class="form-group">
                        {!! Form::labelControl('substrand',trans('admin/task.sub_strand'),['class'=>'control-label col-md-2'])  !!}
                        <div class="col-md-4">
                            {!! Form::select('substrand', array() ,null, ['id' => 'substrand', 'class' => 'form-control select2me']) !!} 
                        </div>
                    </div> 
                    @endif
                    <div class="form-group">
                        {!! Form::labelControl('marks',trans('admin/rewards.percentage'),['class'=>'control-label col-md-2'], TRUE )  !!}
                        <div class="col-md-1">
                            {!! Form::text('percent_min',null,['class'=>'form-control','placeholder' => 'Min'])  !!}
                        </div>
                        <div class="col-md-1">
                            {!! Form::text('percent_max',null,['class'=>'form-control','placeholder' => 'Max'])  !!}
                        </div>
                    </div>
                    @if($user_type == SCHOOL || $user_type == TEACHER) 
                    <div class="form-group">
                        {!! Form::labelControl('student_type',trans('admin/rewards.select_type'),['class'=>'control-label col-md-2'], TRUE )  !!}
                        <div class="col-md-3">
                            <div>
                                <label>
                                    {!! Form::radio('student_source', 'Class',true,['id' => 'class']) !!} Class
                                </label>
                                <label>
                                    {!! Form::radio('student_source', 'Group',false,['id' => 'group']) !!} Group
                                </label>
                                @if(isset($school_array))
                                <label>
                                    {!! Form::radio('student_source', 'School',false,['id' => 'school']) !!} School
                                </label>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group student_sel_div" id="class_div" style="display:{{ !empty(Input::old('student_source')) && Input::old('student_source') == 'Class' ? 'block' : (isset($rewardsData) && $rewardsData['student_source'] == 'Class' ? 'block' : 'none') }};;">
                        {!! Form::labelControl('marks',trans('admin/rewards.select_students'),['class'=>'control-label col-md-2'], TRUE )  !!}
                        <div class="col-md-3" id="student-Class"  >
                            {!! Form::select('students[]', $classes_array, !empty(Input::old('student_source')) ? Input::old('students') : $selected_students_Class, ['id'=>'classstudents','multiple'=>'multiple','class'=>'form-control studentmsele']) !!}
                        </div>
                    </div>    
                    <div class="form-group student_sel_div" id="group_div" style="display:{{ !empty(Input::old('student_source')) && Input::old('student_source') == 'Group' ? 'block' : (isset($rewardsData) && $rewardsData['student_source'] == 'Group' ? 'block' : 'none') }};">

                        {!! Form::labelControl('marks',trans('admin/rewards.select_students'),['class'=>'control-label col-md-2'], TRUE )  !!}
                        <div class="col-md-3" id="student-Group"  >
                            {!! Form::select('students[]', $group_array, !empty(Input::old('student_source')) ? Input::old('students') : $selected_students_Group, ['id'=>'groupstudents','multiple'=>'multiple','class'=>'form-control studentmsele']) !!}
                        </div>
                    </div>   
                    @if(isset($school_array))
                    <div class="form-group student_sel_div" id="school_div" style="display:{{ !empty(Input::old('student_source')) && Input::old('student_source') == 'School' ? 'block' : (isset($rewardsData) && $rewardsData['student_source'] == 'School' ? 'block' : 'none') }};">
                        {!! Form::labelControl('marks',trans('admin/rewards.select_students'),['class'=>'control-label col-md-2'], TRUE )  !!}
                        <div class="col-md-3" id="student-Group"  >
                            {!! Form::select('students[]', $school_array, !empty(Input::old('student_source')) ? Input::old('students') : $selected_students_School, ['id'=>'schoolstudents','multiple'=>'multiple','class'=>'form-control studentmsele']) !!}
                        </div>
                    </div>
                    @endif
                    @endif
                    <div class="form-group">
                        {!! Form::labelControl('studentawards_id',trans('admin/rewards.certificate'),['class'=>'control-label col-md-2'], TRUE )  !!}
                        <div class="col-md-10">
                            <div>
                                @if(count($studentawards))
                                @foreach($studentawards as $key => $value)
                                <div class="col-md-3">
                                    <label>

                                        {!! HTML::image('/uploads/studentawards/'.$value['image'],'',['width' => '150']) !!}<br>
                                        {!! Form::radio('studentawards_id', $value['id'],$key == 0 ? true : false,['id' => 'award-'.$key]) !!}
                                    </label>
                                </div>
                                @endforeach
                                @endif
                            </div>

                        </div>
                    </div>
                    @if(isset($rewardsData['id']))
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
                                {!! HTML::link(route('rewards.index','revision'), trans('admin/admin.cancel'), array('id'=>'marks','class' => 'btn default')) !!}
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
{!! JsValidator::formRequest($JsValidator, '#rewardsfrm'); !!}


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
<?php if ($task_type == REVISION) { ?>
        var strands = <?php echo $strands ?>;
        var substrands = <?php echo $substrands ?>;
        var selectedStrand = "{{ !empty(Input::old('strand')) ? Input::old('strand') : ( !empty($rewardsData['strand']) ? $rewardsData['strand'] : '') }}";
        var selectedSubStrand = "{{ !empty(Input::old('substrand')) ? Input::old('substrand') : ( !empty($rewardsData['substrand']) ? $rewardsData['substrand'] : '') }}";
        $(window).load(function () {
            jsMain.makeDropDownJsonData(strands, $("#strand"), $("#subject").val(), selectedStrand);
            jsMain.makeDropDownJsonData(substrands, $("#substrand"), $("#strand").val(), selectedSubStrand);
            showStudentMultipSelect($("input[name='student_source']:checked").attr('id'));
            //select strand set
            $("#subject").change(function () {
                $("#substrand").html('').select2();
                jsMain.makeDropDownJsonData(strands, $("#strand"), $(this).val(), '');
            });
            $("#strand").change(function () {
                jsMain.makeDropDownJsonData(substrands, $("#substrand"), $(this).val(), '');
            });
        });
<?php } ?>

<?php if ($task_type == TEST) { ?>
        var questionSets = <?php echo $questionSets ?>;
        var selectedquestionSets = "{{ !empty(Input::old('question_set')) ? Input::old('question_set') : ( !empty($rewardsData['question_set']) ? $rewardsData['question_set'] : '') }}";
        $(window).load(function () {
            jsMain.getQuestionSetOptReward(questionSets, selectedquestionSets);
            showStudentMultipSelect($("input[name='student_source']:checked").attr('id'));
            //select question set
            $("#subject").change(function () {
                jsMain.getQuestionSetOptReward(questionSets, '');
            });
        });
<?php } ?>

    $(document).ready(function () {
        //select student radio button
        $("input[name='student_source']").click(function () {
            showStudentMultipSelect($(this).attr('id'));
            $("#classstudents").multiselect("uncheckAll");
            $("#schoolstudents").multiselect("uncheckAll");
            $("#groupstudents").multiselect("uncheckAll");
        });
        $("#schoolstudents ,#groupstudents ,#classstudents").multiselect({
            autoOpen: false,
            noneSelectedText: "Select",
            open: function ()
            {
                $("input[type='search']:first").focus();
            }
        }).multiselectfilter();
        $(".view_row").click(function () {
            jsMain.showModelIframe($(this));
        });
    });
    function showStudentMultipSelect(selectedSource) {
        $(".student_sel_div").hide();
        $("#" + selectedSource + "_div").show();
    }
</script>
@stop


