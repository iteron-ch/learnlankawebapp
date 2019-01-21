@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/tutor.manage_tutor'), 'trait_1' => trans('admin/tutor.tutor')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! trans('admin/tutor.tutor') !!}
                </div>
                <div class="actions">
                    <a href="{{ route('tutor.create') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-plus"></i> {!! trans('admin/tutor.add_tutor') !!} </a>

                </div>                 
            </div>

            <div class="portlet-body">
                {!! Form::open(array('url' => 'tutor','class'=>'form-horizontal','id' =>'search-form' )) !!}
                <div class="form-body">
                    <div class="row">   
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('first_name',trans('admin/tutor.first_name'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-6">
                                    {!! Form::text('first_name',null,['id'=>'first_name','placeholder'=>trans('admin/tutor.first_name'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('last_name',trans('admin/tutor.last_name'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-6">
                                    {!! Form::text('last_name',null,['id'=>'last_name','placeholder'=>trans('admin/tutor.last_name'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('username',trans('admin/tutor.username'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-6">
                                    {!! Form::text('username',null,['id'=>'username','placeholder'=>trans('admin/tutor.search_username'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('email',trans('admin/tutor.email'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-6">
                                    {!! Form::text('email',null,['id'=>'email','placeholder'=>trans('admin/tutor.search_email'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('status',trans('admin/school.status'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-6">
                                    {!! Form::select('status', $status, null ,['id' => 'status', 'class' => 'form-control']) !!} 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-12">
                                    {!! Form::button(trans('admin/admin.search'), array('type' => 'submit', 'class' => 'btn btn-primary')) !!} 
                                    {!! Form::button(trans('admin/admin.reset'), array('type' => 'reset', 'id'=>'reset','class' => 'btn btn-primary grey')) !!} 
                                </div>
                            </div>
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
                    <th>{!! trans('admin/tutor.first_name') !!}</th>
                    <th>{!! trans('admin/tutor.last_name') !!}</th>
                    <th>{!! trans('admin/tutor.email') !!}</th>
                    <th>{!! trans('admin/tutor.username') !!}</th>
                    <th>{!! trans('admin/tutor.date_of_registration') !!}</th>
                    <th>{!! trans('admin/tutor.status') !!}</th>
                    <th>{!! trans('admin/tutor.no_of_students') !!}</th>
                    <th>{!! trans('admin/tutor.actions') !!}</th>
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
        listUrl: "{{ route('tutor.listrecord') }}",
        addUrl: "{{ route('tutor.create') }}",
        deleteUrl: "{{ route('tutor.delete') }}",
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
        successMsg: "{!! trans('admin/admin.delete-success') !!}"
    };
    $(document).ready(function () {
        $("#status").select2();
    });
</script>
{!! HTML::script('js/tutor.js') !!}

@stop