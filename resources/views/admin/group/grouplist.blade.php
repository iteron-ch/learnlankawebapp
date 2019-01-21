@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/group.manage_groups'), 'trait_1' => trans('admin/group.template_heading')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! trans('admin/group.template_heading') !!}
                </div>
                <div class="actions">
                    <a href="{{ route('managegroup.create') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-plus"></i> {!! trans('admin/group.add_group') !!} </a>
                </div>                
            </div>
            <div class="portlet-body">
                {!! Form::open(array('url' => 'group','class'=>'form-horizontal','id' =>'search-form' )) !!}
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('group_name',trans('admin/group.group_name'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::text('group_name',null,['id' => 'group_name','class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('status',trans('admin/group.status'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::select('status', $status, null ,['id' => 'status', 'class' => 'form-control select2me']) !!} 
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
                <table id="groups-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/group.group_name') !!}</th>
                            <th>{!! trans('admin/group.no_of_students') !!}</th>
                            <th>{!! trans('admin/group.class_status') !!}</th>
                            <th>{!! trans('admin/group.actions') !!}</th>
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
        dataTable: "#groups-table",
        listUrl: "{{ route('managegroup.listrecord') }}",
        addUrl: "{{ route('managegroup.create') }}",
        deleteUrl: "{{ route('managegroup.delete') }}",
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
        successMsg: "{!! trans('admin/admin.delete-success') !!}"
    };

</script>
{!! HTML::script('js/group.js') !!}
@stop