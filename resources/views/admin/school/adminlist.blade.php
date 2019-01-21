@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/school.manage_school_admin'), 'trait_1' => trans('admin/school.school_admin')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! trans('admin/school.school_admin') !!}
                </div>
                <div class="actions">
                    <a href="{{ route('teacher.create')}}" class="btn btn-default btn-sm">
                        <i class="fa fa-plus"></i> {!! trans('admin/school.add_school_admin') !!} </a>

                </div>
            </div>
            <div class="portlet-body">
                {!! Form::open(array('url' => 'teacher','class'=>'form-horizontal','id' =>'search-form' )) !!}
                <div class="form-body">
                    <div class="row">

                        @if(session()->get('user')['user_type'] == ADMIN)                        
                        <!--<div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('school',trans('admin/teacher.school'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-9">
                                    {!! Form::select('school', $school,isset($parent_id)?$parent_id:null, ['id' => 'school', 'class' => 'form-control']) !!}   
                                </div>
                            </div>
                        </div>-->
                        @endif
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('first_name',trans('admin/teacher.teacher_name'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-9">
                                    {!! Form::text('first_name',null,['placeholder'=>trans('admin/teacher.search_teacher_name'),'class'=>'form-control'])  !!}
                                </div>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('email',trans('admin/teacher.email'),['class'=>'control-label col-md-5'], FALSE )  !!}
                                <div class="col-md-7">
                                    {!! Form::text('email',null,['placeholder'=>trans('admin/teacher.search_email'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('username',trans('admin/teacher.username'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::text('username',null,['placeholder'=>trans('admin/teacher.search_username'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('status',trans('admin/school.status'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-9">
                                    {!! Form::select('status', $status, null ,['id' => 'status', 'class' => 'form-control']) !!} 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="col-md-12">
                                    {!! Form::button(trans('admin/admin.search'), array('type' => 'submit', 'class' => 'btn btn-primary')) !!} 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
                <table id="users-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/teacher.teacher_name') !!}</th>
                            <th>{!! trans('admin/teacher.email') !!}</th>
                            <th>{!! trans('admin/teacher.username') !!}</th>
                            <th>{!! trans('admin/teacher.date_of_registration') !!}</th>
                            <th>{!! trans('admin/teacher.status') !!}</th>
                            <th>{!! trans('admin/teacher.actions') !!}</th>
                        </tr>
                    </thead>
                </table>

            </div>

        </div>
    </div>

    <!-- END EXAMPLE TABLE PORTLET-->
</div>
</div>
<!-- END PAGE CONTENT-->
@stop

@section('pagescripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<!-- END PAGE LEVEL SCRIPTS -->

    <script>
        var vars = {
            dataTable: "#users-table",
            listUrl: "{{ route('school.schooladminlist') }}",
            addUrl: "{{ route('school.schooladmincreate') }}",
            deleteUrl: "{{ route('school.schooladmindelete') }}",
            confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
            successMsg: "{!! trans('admin/admin.delete-success') !!}"
        };
        $(document).ready(function() {
            $("#status").select2();
        });
    </script>
    {!! HTML::script('js/schooladmin.js') !!}
    @stop