@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/task.manage_revision'), 'trait_1' => trans('admin/task.revision')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! trans('admin/task.revision') !!}
                </div>
                <div class="actions">
                    <a href="{{ route('managerevision.create') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-plus"></i> {!! trans('admin/task.add_revision') !!} </a>

                </div>                
            </div>
            <div class="portlet-body">
                {!! Form::open(array('url' => 'revisionset','class'=>'form-horizontal','id' =>'search-form' )) !!}
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
                                {!! Form::labelControl('strand',trans('admin/task.strand'),['class'=>'control-label col-md-4'])  !!}
                                <div class="col-md-8">
                                    {!! Form::select('strand', array('' => trans('admin/admin.select_option') ) ,null, ['id' => 'strand', 'class' => 'form-control select2me']) !!} 
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('substrand',trans('admin/task.sub_strand'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::select('substrand', array() ,null, ['id' => 'substrand', 'class' => 'form-control select2me']) !!} 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="col-md-12">
                                    {!! Form::button(trans('admin/admin.search'), array('type' => 'submit', 'class' => 'btn btn-primary')) !!} 
                                    {!! Form::button(trans('admin/admin.reset'), array('type' => 'reset', 'id'=>'reset','class' => 'btn btn-primary grey')) !!} 
                                </div>
                            </div>
                        </div>
                    </div><hr style="border-top: 1px solid #26a69a;" >
                    {!! Form::close() !!}   
                    <table id="revisionset-table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr class="heading">
                                <th>{!! trans('admin/task.key_stage') !!}</th>
                                <th>{!! trans('admin/task.year_group') !!}</th>
                                <th>{!! trans('admin/task.subject') !!}</th>
                                <th>{!! trans('admin/task.strand') !!}</th>
                                <th>{!! trans('admin/task.substrand') !!}</th>
                                <th>{!! trans('admin/admin.classes') !!}</th>
                                <th>{!! trans('admin/admin.group') !!}</th>
                                <th>{!! trans('admin/task.assigned_date') !!}</th>
                                <th>{!! trans('admin/task.completion_date') !!}</th>
                                <th>{!! trans('admin/admin.completed') !!}</th>

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
    var yearDD = <?php echo $yearKeysJson ?>;
    var strands = <?php echo $strands ?>;
    var substrands = <?php echo $substrands ?>;
    $(window).load(function() {
        jsMain.makeDropDownJsonData(yearDD, $("#year_group"), $("#key_stage").val(), '');
        jsMain.makeDropDownJsonData(strands, $("#strand"), $("#subject").val(), '');
        jsMain.makeDropDownJsonData(substrands, $("#substrand"), $("#strand").val(), '');

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
            jsMain.makeDropDownJsonData(substrands, $("#substrand"), $(this).val(), '');
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
        dataTable: "#revisionset-table",
        listUrl: "{{ route('managerevision.listrecord') }}",
        addUrl: "{{ route('managerevision.create') }}",
        deleteUrl: "{{ route('managerevision.delete') }}",
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
        successMsg: "{!! trans('admin/admin.delete-success') !!}"
    };
</script>
{!! HTML::script('js/taskrevision.js') !!}
@stop