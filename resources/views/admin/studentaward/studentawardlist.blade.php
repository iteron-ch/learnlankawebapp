@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/studentaward.template_heading'), 'trait_1' => trans('admin/studentaward.student_awards')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! trans('admin/studentaward.student_awards') !!}
                </div>
				<div class="actions">
                    <a href="{{ route('studentaward.create') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-plus"></i> {!! trans('admin/studentaward.add_template') !!} </a>

                </div>   
            </div>
            <div class="portlet-body">
                <table id="studentaward-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/studentaward.certificate_label') !!}</th>
                            <th>{!! trans('admin/studentaward.status') !!}</th>
                            <th>{!! trans('admin/studentaward.actions') !!}</th>
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
<script>
    var vars = {
		dataTable:'#studentaward-table',
        listUrl: "{{ route('studentaward.listrecord') }}", 
        addUrl: "{{ route('studentaward.create') }}", 
        deleteUrl: "{{ route('studentaward.delete') }}", 
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}" ,
        successMsg: "{!! trans('admin/admin.delete-success') !!}" 
    };
</script>
{!! HTML::script('js/studentaward.js') !!}

<!-- END PAGE LEVEL SCRIPTS -->
@stop