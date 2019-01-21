@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/messages.manage_message_centre'), 'trait_1' => trans('admin/messages.inbox')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! trans('admin/messages.inbox') !!}
                </div>
                <div class="actions">
                    <a href="{{ route('messages.create') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-plus"></i> {!! trans('admin/messages.create_message') !!} </a>

                </div>                
            </div>
            <div class="portlet-body">
                
                <table id="inbox-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/messages.subject') !!}</th>
                            <th>{!! trans('admin/messages.sender_name') !!}</th>
                            <th>{!! trans('admin/messages.date') !!}</th>
                            <th>{!! trans('admin/messages.actions') !!}</th>
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
        dataTable: "#inbox-table",
        listUrl: "{{ route('messages.listrecordinbox') }}",
        deleteUrl: "{{ route('messages.delete') }}",
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
        successMsg: "{!! trans('admin/admin.delete-success') !!}"
    };
</script>
{!! HTML::script('js/messages.js') !!}
@stop