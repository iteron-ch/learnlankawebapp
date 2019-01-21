@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/cmspage.template_heading'), 'trait_1' => trans('admin/cmspage.template_heading')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! trans('admin/cmspage.template_heading') !!}
                </div>
				<div class="actions">
                    <a href="{{ route('cmspage.create') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-plus"></i> {!! trans('admin/cmspage.add_template') !!} </a>

                </div>  
				
            </div>
            <div class="portlet-body">
               
                <table id="cmspage-table" class="table table-striped table-bordered table-hover">
                    <thead>
                         <tr class="heading">
                            <th>{!! trans('admin/cmspage.title_label') !!}</th>
                            <th>{!! trans('admin/cmspage.sub_title_label') !!}</th>
                            <th>{!! trans('admin/cmspage.content_label') !!}</th>
							<th>{!! trans('admin/cmspage.meta_title_label') !!}</th>
							<th>{!! trans('admin/cmspage.meta_keyword_label') !!}</th>
							<th>{!! trans('admin/cmspage.meta_description_label') !!}</th>
                            <th>{!! trans('admin/cmspage.status') !!}</th>
                            <th>{!! trans('admin/cmspage.actions') !!}</th>
							
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
        dataTable: "#cmspage-table",
        listUrl: "{{ route('cmspage.listrecord') }}", 
        addUrl: "{{ route('cmspage.create') }}", 
        deleteUrl: "{{ route('cmspage.delete') }}", 
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}" ,
        successMsg: "{!! trans('admin/admin.delete-success') !!}" 
    };
</script>
{!! HTML::script('js/cmspage.js') !!}

<!-- END PAGE LEVEL SCRIPTS -->
@stop