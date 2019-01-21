@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/school.manage_school'), 'trait_1' => trans('admin/school.school')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! trans('admin/school.school') !!}
                </div>
                <div class="actions">
                    <a href="{{ route('school.create') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-plus"></i> {!! trans('admin/school.add_school') !!} </a>

                </div>                
            </div>
            <div class="portlet-body">
                {!! Form::open(array('url' => 'school','class'=>'form-horizontal','id' =>'search-form' )) !!}
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('school_name',trans('admin/school.school_name'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::text('school_name',null,['placeholder'=>trans('admin/school.search_school_name'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('email',trans('admin/school.email'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::text('email',null,['placeholder'=>trans('admin/school.search_email'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('status',trans('admin/school.status'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
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
                            <th>{!! trans('admin/school.school_name') !!}</th>
                            <th>{!! trans('admin/school.no_of_students') !!}</th>
                            <th>{!! trans('admin/school.email') !!}</th>
                            <th>{!! trans('admin/school.date_of_registration') !!}</th>
                            <th>{!! trans('admin/school.status') !!}</th>
                            <th>{!! trans('admin/school.no_of_teachers') !!}</th>
                            <th>{!! trans('admin/school.actions') !!}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
    <!-- END PAGE CONTENT-->
</div>
@stop

@section('pagescripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script>
    var vars = {
        dataTable: "#users-table",
        listUrl: "{{ route('school.listrecord') }}",
        addUrl: "{{ route('school.create') }}",
        deleteUrl: "{{ route('school.delete') }}",
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
        successMsg: "{!! trans('admin/admin.delete-success') !!}"
    };
    $(document).ready(function () {
        $("#status").select2();
    });
</script>
{!! HTML::script('js/school.js') !!}
@stop