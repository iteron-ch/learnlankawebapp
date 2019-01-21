@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/questionadmin.questionadmin'), 'trait_1' => trans('admin/questionadmin.questionadmin')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! trans('admin/questionadmin.questionadmin') !!}
                </div>
                <div class="actions">
                    <a href="{{ route('questionadmin.create') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-plus"></i> {!! trans('admin/questionadmin.add_questionadmin') !!} </a>

                </div>                 
            </div>
            <div class="portlet-body">
                {!! Form::open(array('url' => 'tutor','class'=>'form-horizontal','id' =>'search-form' )) !!}
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('first_name',trans('admin/questionadmin.first_name'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::text('first_name',null,['placeholder'=>trans('admin/questionadmin.first_name'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('email',trans('admin/questionadmin.email'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::text('email',null,['placeholder'=>trans('admin/questionadmin.email'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('username',trans('admin/questionadmin.username'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::text('username',null,['placeholder'=>trans('admin/questionadmin.search_username'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
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
                <table id="admin-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/questionadmin.first_name') !!}</th>
                            <th>{!! trans('admin/questionadmin.last_name') !!}</th>
                            <th>{!! trans('admin/questionadmin.username') !!}</th>
                            <th>{!! trans('admin/questionadmin.email') !!}</th>
                            <th>{!! trans('admin/questionadmin.total_question') !!}</th>
                            <th>{!! trans('admin/questionadmin.last_login') !!}</th>
                            <th>{!! trans('admin/admin.status') !!}</th>
                            <th>{!! trans('admin/questionadmin.actions') !!}</th>
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
        dataTable: "#admin-table",
        listUrl: "{{ route('questionadmin.listrecord') }}",
        addUrl: "{{ route('questionadmin.create') }}",
        deleteUrl: "{{ route('questionadmin.delete') }}",
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
        successMsg: "{!! trans('admin/admin.delete-success') !!}"
    };
</script>
{!! HTML::script('js/questionadmin.js') !!}

@stop