@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/student.manage_student'), 'trait_1' => 'Classes', 'trait_1_link' => route('manageclass.index'), 'trait_2' => trans('admin/student.student')])
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
                    <a href="{{ route('student.create') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-plus"></i>{!! trans('admin/student.add_student') !!} </a>

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
                            <th>{!! trans('admin/admin.email') !!}</th>
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
    var id = '<?php echo $id ?>';
    var vars = {
        dataTable: "#users-table",
        listUrl: "/manageclass/classstudentslistrecord/"+id,
        addUrl: "{{ route('student.create') }}",
        deleteUrl: "{{ route('student.delete') }}",
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
        successMsg: "{!! trans('admin/admin.delete-success') !!}",
    };
    $(document).ready(function () {
        $("#schoolclasses_id, #teacher_name, #status").select2();
    });
</script>
{!! HTML::script('js/student.js') !!}

@stop