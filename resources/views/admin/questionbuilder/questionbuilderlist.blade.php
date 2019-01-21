@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/questionbuilder.manage_questionbuilder'), 'trait_1' => trans('admin/questionbuilder.question_builder')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! trans('admin/questionbuilder.question_builder') !!}
                </div>
                @if (session()->get('user')['user_type'] != QUESTIONVALIDATOR)
                <div class="actions">
                    <a href="{{ route('questionbuilder.create') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-plus"></i> {!! trans('admin/questionbuilder.add_questionbuilder') !!} </a>
                </div>                
                @endif
            </div>
            <div class="portlet-body">
                {!! Form::open(array('url' => 'questionbuilder','class'=>'form-horizontal','id' =>'search-form' )) !!}
                <div class='dtable_custom_controls'>
                    <div class='dataTables_wrapper'> 
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::labelControl('key_stage',trans('admin/questionset.key_stage'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                        <div class="col-md-8">
                                            {!! Form::select('key_stage', $keyStage,null, ['id' => 'key_stage', 'class' => 'form-control select2me dtable_filter']) !!}   
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::labelControl('year_group',trans('admin/questionset.year_group'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                        <div class="col-md-8">
                                            {!! Form::select('year_group', $yearGroups ,null, ['id' => 'year_group', 'class' => 'form-control select2me dtable_filter']) !!}   
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::labelControl('subject',trans('admin/questionbuilder.subject'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                        <div class="col-md-8">
                                            {!! Form::select('subject', $setSubject,null, ['id' => 'subject', 'class' => 'form-control select2me dtable_filter']) !!}    
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::labelControl('set_group',trans('admin/questionbuilder.set_group'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                        <div class="col-md-8">
                                            {!! Form::select('set_group', $setgroup,null, ['id' => 'set_group', 'class' => 'form-control select2me dtable_filter']) !!}    
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::labelControl('question_set_id',trans('admin/questionbuilder.set_name'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                        <div class="col-md-8">
                                            {!! Form::select('question_set', $questionSetFullArray ,null, ['id' => 'question_set', 'class' => 'form-control select2me dtable_filter']) !!} 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::labelControl('paper',trans('admin/questionbuilder.paper'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                        <div class="col-md-8">
                                            {!! Form::select('paper', array('' => trans('admin/admin.select_option') ) ,null, ['id' => 'paper', 'class' => 'form-control select2me dtable_filter']) !!} 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::labelControl('questionType',trans('admin/questionbuilder.questionType'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                        <div class="col-md-8">
                                            {!! Form::select('questionType', array('' => trans('admin/admin.select_option') ),null, ['id' => 'questionType', 'class' => 'form-control select2me dtable_filter']) !!}   
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::labelControl('strands_id',trans('admin/questionbuilder.strand'),['class'=>'control-label col-md-4'])  !!}
                                        <div class="col-md-8">
                                            {!! Form::select('strands_id', array('' => trans('admin/admin.select_option') ) ,null, ['id' => 'strands_id', 'class' => 'form-control select2me dtable_filter']) !!} 
                                        </div>
                                    </div>
                                </div>
                            </div>    
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::labelControl('substrands_id',trans('admin/questionbuilder.sub_strand'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                        <div class="col-md-8">
                                            {!! Form::select('substrands_id', array('' => trans('admin/admin.select_option') ) ,null, ['id' => 'substrands_id', 'class' => 'form-control select2me dtable_filter']) !!} 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::labelControl('difficulty',trans('admin/questionbuilder.difficulty'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                        <div class="col-md-8">
                                            {!! Form::select('difficulty', $questionDifficulty ,null, ['id' => 'difficulty', 'class' => 'form-control select2me dtable_filter']) !!} 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::labelControl('status',trans('admin/questionbuilder.status'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                        <div class="col-md-8">
                                            {!! Form::select('status', $status, null ,['id' => 'status', 'class' => 'form-control select2me dtable_filter']) !!} 
                                        </div>
                                    </div>
                                </div>
                                @if (session()->get('user')['user_type'] == ADMIN)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::labelControl('user_type',trans('admin/questionbuilder.user_type'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                        <div class="col-md-8">
                                            {!! Form::select('user_type', $userType ,null, ['id' => 'user_type', 'class' => 'form-control select2me dtable_filter']) !!} 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::labelControl('created_by',trans('admin/questionbuilder.created_by'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                        <div class="col-md-8">
                                            {!! Form::select('created_by', $userArray ,null, ['id' => 'created_by', 'class' => 'form-control select2me dtable_filter']) !!} 
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::labelControl('question_id',"Question Id",['class'=>'control-label col-md-4'], FALSE )  !!}
                                        <div class="col-md-8">
                                            {!! Form::text('question_id',null,['id'=>'question_id','class'=>'form-control dtable_filter'])  !!} 
                                        </div>

                                    </div>
                                </div>
                                @if (session()->get('user')['user_type'] == ADMIN)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::labelControl('validate_stage','Validate Stage',['class'=>'control-label col-md-4'], FALSE )  !!}
                                        <div class="col-md-8">
                                            {!! Form::select('validate_stage', $validateStage, null ,['id' => 'validate_stage', 'class' => 'form-control select2me dtable_filter']) !!} 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::labelControl('validater1',trans('admin/questionbuilder.validator1'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                        <div class="col-md-8">
                                            {!! Form::select('validater1', $userArrayValidator, null ,['id' => 'validater1', 'class' => 'form-control select2me dtable_filter']) !!} 
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::labelControl('validateReason1',trans('admin/questionbuilder.validate_reason1'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                        <div class="col-md-8">
                                            {!! Form::select('validateReason1', $validateReason, null ,['id' => 'validateReason1', 'class' => 'form-control select2me dtable_filter']) !!} 
                                        </div>
                                    </div>
                                </div>
                                @if (session()->get('user')['user_type'] == ADMIN)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::labelControl('validater2',trans('admin/questionbuilder.validator2'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                        <div class="col-md-8">
                                            {!! Form::select('validater2', $userArrayValidator, null ,['id' => 'validater2', 'class' => 'form-control select2me dtable_filter']) !!} 
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        {!! Form::labelControl('validateReason2',trans('admin/questionbuilder.validate_reason2'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                        <div class="col-md-8">
                                            {!! Form::select('validateReason2', $validateReason, null ,['id' => 'validateReason2', 'class' => 'form-control select2me dtable_filter']) !!} 
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label col-md-4" for="set_name">&nbsp;</label>
                                        <div class="col-md-8">
                                            {!! Form::button(trans('admin/questionbuilder.search'), array('type' => 'submit', 'class' => 'btn btn-primary')) !!} 
                                            {!! Form::button(trans('admin/admin.reset'), array('type' => 'reset', 'id'=>'reset','class' => 'btn btn-primary grey')) !!} 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr style="border-top: 1px solid #26a69a;" >
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
                <table id="questionbuilder-table" class="dtable table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>Question No.</th>
                            <th>{!! trans('admin/questionbuilder.subject') !!}</th>
                            <th>{!! trans('admin/questionbuilder.key_stage') !!}</th>
                            <th>{!! trans('admin/questionbuilder.year_group') !!}</th>
                            <th>{!! trans('admin/questionbuilder.set_group') !!}</th>
                            <th>{!! trans('admin/questionbuilder.set_name') !!}</th>
                            <th>{!! trans('admin/questionbuilder.paper') !!}</th>
                            <th>{!! trans('admin/questionbuilder.difficulty') !!}</th>
                            <th>{!! trans('admin/questionbuilder.strands') !!}</th>
                            <th>{!! trans('admin/questionbuilder.substrands') !!}</th>
                            <th>{!! trans('admin/questionbuilder.question_type') !!}</th>
                            <th>{!! trans('admin/questionbuilder.question_id') !!}</th>
                            <th>{!! trans('admin/questionbuilder.status') !!}</th>
                            <th>{!! trans('admin/questionset.actions') !!}</th>       
                            <th>{!! trans('admin/questionbuilder.created_at') !!}</th>
                            <th>{!! trans('admin/questionbuilder.validated_date') !!}</th>
                            <th>{!! trans('admin/questionbuilder.published_date') !!}</th>
                            <th>{!! trans('admin/questionbuilder.created_by') !!}</th>

                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
</div>
<!-- END PAGE CONTENT-->


@stop

@section('pagescripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script>
    var paperJson = <?php echo $paperJson ?>;
    var questionSets = <?php echo $questionSets ?>;
    var strands = <?php echo $strands ?>;
    var questionType = <?php echo $questionType ?>;
    var substrands = <?php echo $substrands ?>;     
     
    var vars = {
        dataTable: "#questionbuilder-table",
        listUrl: "{{ route('questionbuilder.listrecord') }}",
        addUrl: "{{ route('questionbuilder.create') }}",
        deleteUrl: "{{ route('questionbuilder.delete') }}",
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
        successMsg: "{!! trans('admin/admin.delete-success') !!}",
        inReviewConfirmMsg: "{!! trans('admin/questionbuilder.inreview_confirm') !!}",
        publishConfirmMsg: "{!! trans('admin/questionbuilder.publish_confirm') !!}",
        unpublishConfirmMsg: "{!! trans('admin/questionbuilder.unpublish_confirm') !!}",
        rejectConfirmMsg: "{!! trans('admin/questionbuilder.reject_confirm') !!}",
        changeStatusUrl: "{{ route('questionbuilder.updatestatus') }}",
        publishSuccessMessage: "{!! trans('admin/questionbuilder.publish_success_message') !!}",
        paperJson : paperJson,
        questionSets : questionSets,
        strands : strands,
        questionType : questionType,
        substrands : substrands,
    };
      jsMain = new Main("{{ trans('admin/admin.select_option') }}");
    
    var yearDD = <?php echo $yearKeysJson ?>;
    var yearGroups = <?php echo json_encode($yearGroups) ?>;
    var questionSetFullArray = <?php echo json_encode($questionSetFullArray) ?>;

   
    //console.log(paperJson);


    $(window).load(function () {
        // jsMain.makeDropDownJsonData(yearDD, $("#year_group"), $("#key_stage").val(), '');
        
        //jsMain.getQuestionSetOpt(questionSets, '');
       // jsMain.makeDropDownJsonData(questionType, $("#questionType"), '', '');
    });
    function showSetName(questionSet) {
        $("#question_set").html('').select2();
        var opt = '';
        if (questionSet) {
            jsonDataObj = jsonData(questionSet);
            var opt = '<option value="">Select</option>';
            $.each(jsonDataObj, function (key, obj) {
                opt += '<option value="' + obj.k + '" >' + obj.v + '</option>';
            });
        }
        $("#question_set").html(opt).select2();
    }
    function jsonData(jsonObj) {
        var temp = [];
        $.each(jsonObj, function (key, value) {
            if (value != 'Select')
                temp.push({v: value, k: key});
        });
        return temp;
    }

    $(document).ready(function () {
        
        $("#key_stage").change(function () {
            if ($("#key_stage").val() == '') {
                $("#year_group").html('');
                var opt = '';
                if (yearGroups) {
                    jsonDataObj = jsonData(yearGroups);
                    var opt = '<option value="">Select</option>';
                    $.each(jsonDataObj, function (key, obj) {
                        opt += '<option value="' + obj.k + '" >' + obj.v + '</option>';
                    });
                }
                $("#year_group").html(opt).select2();
                showSetName(questionSetFullArray);
            }
            else {
                jsMain.makeDropDownJsonData(yearDD, $("#year_group"), $(this).val(), '');
                $("#subject").val('').select2();
                jsMain.getQuestionSetOpt(questionSets, '');
            }

        });
        $("#year_group").change(function () {
            $("#subject").val('').select2();
            jsMain.getQuestionSetOpt(questionSets, '');
        });
        $("#subject").change(function () {
            $("#set_group").val('').select2();
            jsMain.getQuestionSetOpt(questionSets, '');
            $("#substrands_id").html('<option value="" selected>Select</option>').select2();
            jsMain.makeDropDownJsonData(strands, $("#strands_id"), $(this).val(), '');
            jsMain.getPaperOpt(paperJson, $("#paper"), $(this).val(), '');
            jsMain.makeDropDownJsonData(questionType, $("#questionType"), $(this).val(), '');
        });
        $("#strands_id").change(function () {
            jsMain.makeDropDownJsonData(substrands, $("#substrands_id"), $(this).val(), '');
        });
        $("#set_group").change(function () {
            $("#question_set").val('').select2();
            $("#paper").val('').select2();
            if ($(this).val() == 'Revision') {
                $("#question_set").attr('disabled', true);
                $("#paper").attr('disabled', true);
            } else {
                $("#question_set").attr('disabled', false);
                $("#paper").attr('disabled', false);
            }
        });
        
          
    });
</script>
{!! HTML::script('js/questionbuilder.js?v=2') !!}
@stop