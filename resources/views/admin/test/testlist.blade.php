@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/task.manage_test'), 'trait_1' => trans('admin/task.test')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! trans('admin/task.test') !!}
                </div>               
            </div>
            <div class="portlet-body">
                {!! Form::open(array('url' => 'testset','class'=>'form-horizontal','id' =>'search-form' )) !!}
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('key_stage',trans('admin/task.key_stage'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::select('key_stage', $keyStage, null ,['id' => 'key_stage', 'class' => 'form-control select2me ']) !!} 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('year_group',trans('admin/task.year_group'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::select('year_group', array('' => trans('admin/admin.select_option') ), null ,['id' => 'year_group', 'class' => 'form-control select2me ']) !!} 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('subject',trans('admin/task.subject'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::select('subject', $subject, null ,['id' => 'subject', 'class' => 'form-control select2me ']) !!} 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('question_set',trans('admin/task.question_set'),['class'=>'control-label col-md-4'])  !!}
                                <div class="col-md-8">
                                    {!! Form::select('question_set', array('' => trans('admin/admin.select_option') ) ,null, ['id' => 'question_set', 'class' => 'form-control select2me']) !!} 
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="col-md-12">
                                    {!! Form::button(trans('admin/admin.search'), array('type' => 'submit', 'class' => 'btn btn-primary')) !!} 
                                    {!! Form::button(trans('admin/admin.reset'), array('type' => 'reset', 'id'=>'reset','class' => 'btn btn-primary grey')) !!} 
                                </div>
                            </div>
                        </div>
                       
                    </div> <hr style="border-top: 1px solid #26a69a;" >
                    {!! Form::close() !!}   
                    <table id="testset-table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr class="heading">
                                <th>{!! trans('admin/task.key_stage') !!}</th>
                                <th>{!! trans('admin/task.year_group') !!}</th>
                                <th>{!! trans('admin/task.subject') !!}</th>
                                <th>{!! trans('admin/task.question_set') !!}</th>
                                <th>{!! trans('admin/admin.classes') !!}</th>
                                <th>{!! trans('admin/admin.group') !!}</th>
                                <th>{!! trans('admin/task.assigned_date') !!}</th>
                                <th>{!! trans('admin/task.completion_date') !!}</th>
                                <th>{!! trans('admin/admin.completed') !!}</th>
                                <th>{!! trans('admin/task.actions') !!}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
        <!-- END PAGE CONTENT-->
    </div>

    <!-- END EXAMPLE TABLE PORTLET-->
</div>
@stop

@section('pagescripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script>
    jsMain = new Main("{{ trans('admin/admin.select_option') }}");
    var questionSets = <?php echo $questionSets ?>;
    var yearDD = <?php echo $yearKeysJson ?>;
    $(window).load(function() {
        jsMain.makeDropDownJsonData(yearDD, $("#year_group"), $("#key_stage").val(), '');
        jsMain.getQuestionSetOpt(questionSets, '');
    });
    $(document).ready(function() {
        $("#key_stage").change(function() {
            jsMain.makeDropDownJsonData(yearDD, $("#year_group"), $(this).val(), '');
            $("#subject").val('').select2();
            jsMain.getQuestionSetOpt(questionSets, '');
        });
        $("#year_group").change(function() {
            $("#subject").val('').select2();
            jsMain.getQuestionSetOpt(questionSets, '');
        });
        $("#subject").change(function() {
            jsMain.getQuestionSetOpt(questionSets, '');
        });
        $('#date_assign_date').datepicker({
            autoclose: true,
            format: '{{ CALENDER_DATE_FORMAT }}',
        });
        $('#date_completion_date').datepicker({
            autoclose: true,
            format: '{{ CALENDER_DATE_FORMAT }}',
        });
    });
    var vars = {
        dataTable: "#testset-table",
        listUrl: "{{ route('managetest.listrecord') }}",
        addUrl: "{{ route('managetest.create') }}",
        deleteUrl: "{{ route('managetest.delete') }}",
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
        successMsg: "{!! trans('admin/admin.delete-success') !!}"
    };
</script>
{!! HTML::script('js/tasktest.js') !!}
@stop