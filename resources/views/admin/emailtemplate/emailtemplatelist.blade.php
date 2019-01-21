@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/emailtemplate.manage_email_template'), 'trait_1' => trans('admin/emailtemplate.template_heading')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption"
                     <i class="fa"></i>Email Template
                </div>
               
            </div>
            <div class="portlet-body">
                <table id="emailtemplate-table" class="table table-condensed">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/emailtemplate.title_label') !!}</th>
                            <th>{!! trans('admin/emailtemplate.subject_label') !!}</th>
                            <th>{!! trans('admin/emailtemplate.status') !!}</th>
                            <th>{!! trans('admin/emailtemplate.actions') !!}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop

@section('pagescripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script>
    var vars = {
        dataTable: "#emailtemplate-table",
        listUrl: "{{ route('emailtemplate.listrecord') }}",
        addUrl: "{{ route('emailtemplate.create') }}",
        deleteUrl: "{{ route('emailtemplate.delete') }}",
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
        successMsg: "{!! trans('admin/admin.delete-success') !!}"
    };
</script>
{!! HTML::script('js/emailtemplate.js') !!}

<!-- END PAGE LEVEL SCRIPTS -->
@stop