@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->

@if(isset($trait['trait_2']) && !empty($trait['trait_2']))
@include('admin.layout._page_header', ['title' => trans('admin/student.manage_student'), 'trait_1' => $trait['trait_1'], 'trait_1_link' => $trait['trait_1_link'], 'trait_2' => $trait['trait_2']])
@else    
@include('admin.layout._page_header', ['title' => trans('admin/student.manage_student'), 'trait_1' => $trait['trait_1'], 'trait_1_link' => $trait['trait_1_link']])
@endif    
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>
                    {!! trans('admin/student.student') !!}
                </div>
                <div class="actions">
                    @if(session()->get('user')['user_type'] != TUTOR && $user_type!=TUTOR)
                        <a href="javascript:void(0);" data-remote="/importstudent/{{ $school_record_id }}" id="import_data" class="import_data btn btn-default btn-sm">Import Pupils</a>
                    @endif 
                    @if($remaining_no_of_student>0)
                    <a href="{{ route('student.create') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-plus"></i>{!! trans('admin/student.add_student') !!} </a>
                    @endif    
                </div>
            </div>
            <div class="portlet-body">
                {!! Form::open(array('url' => 'student','class'=>'form-horizontal','id' =>'search-form' )) !!}
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('first_name',trans('admin/student.first_name'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-9">
                                    {!! Form::text('first_name',null,['placeholder'=>trans('admin/student.first_name'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('last_name',trans('admin/student.last_name'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-9">
                                    {!! Form::text('last_name',null,['placeholder'=>trans('admin/student.last_name'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('email',trans('admin/student.email_address'),['class'=>'control-label col-md-5'], FALSE )  !!}
                                <div class="col-md-7">
                                    {!! Form::text('email',null,['placeholder'=>trans('admin/student.search_email'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        @if($user_type != TUTOR && $user_type != TEACHER)
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('teacher_name',trans('admin/student.teacher_name'),['class'=>'control-label col-md-5'], FALSE )  !!}
                                <div class="col-md-7">
                                    {!! Form::select('teacher_name', $user_array, isset($teacher_id)?$teacher_id:'', ['id' => 'teacher_name', 'class' => 'form-control']) !!}   
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('status',trans('admin/admin.status'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-9">
                                    {!! Form::select('status', $status, null ,['id' => 'status', 'class' => 'form-control']) !!} 
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
                    </div>
                    <hr style="border-top: 1px solid #26a69a;" >
                </div>
                {!! Form::close() !!}
                <table id="users-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/student.first_name') !!}</th>
                            <th>{!! trans('admin/student.last_name') !!}</th>
                            <th>{!! trans('admin/student.date_of_registration') !!}</th>
                            <th>{!! trans('admin/student.status') !!}</th>
                            <th>{!! trans('admin/student.actions') !!}</th>
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
<!-- END PAGE LEVEL SCRIPTS -->
<script>
    var vars = {
        dataTable: "#users-table",
        listUrl: "{{ route('student.listrecord') }}",
        addUrl: "{{ route('student.create') }}",
        deleteUrl: "{{ route('student.delete') }}",
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
        successMsg: "{!! trans('admin/admin.delete-success') !!}",
    };
    $(document).ready(function () {
        $("#schoolclasses_id, #teacher_name, #status").select2();
    });
    $('#import_data').click(function (e) {
        e.preventDefault();
        var eleObj = $(this);
        jsMain.showModelIframe(eleObj);
    });
</script>
{!! HTML::script('js/student.js') !!}

@stop