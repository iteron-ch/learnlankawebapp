@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/topic.manage_topic'), 'trait_1' => trans('admin/topic.topic')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! trans('admin/topic.topic') !!}
                </div>
                <div class="actions">
                    <a href="{{ route('topic.create') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-plus"></i> {!! trans('admin/topic.add_topic') !!} </a>

                </div>                
            </div>
            <div class="portlet-body">
                {!! Form::open(array('url' => 'topic','class'=>'form-horizontal','id' =>'search-form' )) !!}
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                {!! Form::labelControl('student_name',trans('admin/topic.student_name'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::text('student_name',null,['id' => 'student_name','placeholder'=>trans('admin/topic.student_name'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('subject',trans('admin/topic.subject'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-7">
                                    {!! Form::select('subject', $subject, null ,['id' => 'subject', 'class' => 'form-control select2me ']) !!} 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('strands_id',trans('admin/topic.strand'),['class'=>'control-label col-md-4'])  !!}

                                <div class="col-md-8">
                                    {!! Form::select('strands_id', array('' => trans('admin/admin.select_option') ) ,null, ['id' => 'strands_id', 'class' => 'form-control select2me']) !!} 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('substrands_id',trans('admin/topic.sub_strand'),['class'=>'control-label col-md-5'], FALSE )  !!}
                                <div class="col-md-6">
                                    {!! Form::select('substrands_id', array() ,null, ['id' => 'substrands_id', 'class' => 'form-control select2me']) !!} 
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
                        
                    </div>
                    {!! Form::close() !!}   <hr style="border-top: 1px solid #26a69a;" >
                    <table id="topic-table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr class="heading">
                                <th>{!! trans('admin/topic.student_name') !!}</th>
                                <th>{!! trans('admin/topic.subject') !!}</th>
                                <th>{!! trans('admin/topic.strand') !!}</th>
                                <th>{!! trans('admin/topic.sub_strand') !!}</th>
                                <th>{!! trans('admin/topic.assigned_date') !!}</th>
                                <th>{!! trans('admin/topic.attempted_date') !!}</th>
                                <th>{!! trans('admin/topic.created_at') !!}</th>
                                <th>{!! trans('admin/topic.action') !!}</th>

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
    jsMain = new Main();
    var strands = <?php echo $strands ?>;
    var substrands = <?php echo $substrands ?>;
    var vars = {
        dataTable: "#topic-table",
        listUrl: "{{ route('topic.listrecord') }}",
        addUrl: "{{ route('topic.create') }}",
        deleteUrl: "{{ route('topic.delete') }}",
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
        successMsg: "{!! trans('admin/admin.delete-success') !!}"
    };
    $(document).ready(function () {
        $("#subject").change(function () {
            $("#substrands_id").html('').select2();
            jsMain.makeDropDownJsonData(strands, $("#strands_id"), $(this).val(), '');
        });
        $("#strands_id").change(function () {
            jsMain.makeDropDownJsonData(substrands, $("#substrands_id"), $(this).val(), '');
        });
    });
</script>
{!! HTML::script('js/topic.js') !!}
@stop