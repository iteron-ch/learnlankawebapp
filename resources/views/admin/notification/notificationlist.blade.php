@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/notification.manage_notification'), 'trait_1' => trans('admin/notification.template_heading')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! trans('admin/notification.template_heading') !!}
                </div>
                <div class="actions">
                    <a href="{{ route('notification.create') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-plus"></i> {!! trans('admin/notification.add_notification') !!} </a>

                </div>                
            </div>
            <div class="portlet-body">
                {!! Form::open(array('url' => 'notification','class'=>'form-horizontal','id' =>'search-form' )) !!}
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-notification">
                                {!! Form::labelControl('title',trans('admin/notification.title'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-6">
                                    {!! Form::text('title',null,['id' => 'title','class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-notification">
                                {!! Form::labelControl('status',trans('admin/notification.status'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-6">
                                    {!! Form::select('status', $status, null ,['id' => 'status', 'class' => 'form-control select2me']) !!} 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-notification">
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
                <table id="notification-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/notification.title') !!}</th>
                            <th>{!! trans('admin/notification.user_type') !!}</th>
                            <th>{!! trans('admin/notification.description') !!}</th>
                            <th>{!! trans('admin/notification.status') !!}</th>
                            <th>{!! trans('admin/notification.created_at') !!}</th>
                            <th>{!! trans('admin/notification.actions') !!}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop

@section('pagescripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script>
    var vars = {
        dataTable: "#notification-table",
        listUrl: "{{ route('notification.listrecord') }}",
        addUrl: "{{ route('notification.create') }}",
        deleteUrl: "{{ route('notification.delete') }}",
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
        successMsg: "{!! trans('admin/admin.delete-success') !!}"
    };

</script>
{!! HTML::script('js/notification.js') !!}
@stop