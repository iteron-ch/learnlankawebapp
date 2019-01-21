@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/schoolclass.manage_classes'), 'trait_1' => trans('admin/schoolclass.template_heading')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! trans('admin/schoolclass.template_heading') !!}
                </div>
                <div class="actions">
                    <a href="{{ route('manageclass.create') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-plus"></i> {!! trans('admin/schoolclass.add_template') !!} </a>
                </div>  
            </div>
            <div class="portlet-body">
                {!! Form::open(array('url' => 'group','class'=>'form-horizontal','id' =>'search-form' )) !!}
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('class_name',trans('admin/schoolclass.class_label'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::text('class_name',null,['id' => 'class_name','class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('year',trans('admin/schoolclass.year'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::text('year',null,['id' => 'year','class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('status',trans('admin/schoolclass.status'),['class'=>'control-label col-md-4'], FALSE )  !!}
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
                <table id="schoolclass-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/schoolclass.class_label') !!}</th>
                            <th>{!! trans('admin/schoolclass.no_of_student') !!}</th>
                            <th>{!! trans('admin/schoolclass.year') !!}</th>
                            <th>{!! trans('admin/schoolclass.status') !!}</th>
                            <th>{!! trans('admin/schoolclass.actions') !!}</th>
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
        dataTable: '#schoolclass-table',
        listUrl: "{{ route('manageclass.listrecord') }}",
        addUrl: "{{ route('manageclass.create') }}",
        deleteUrl: "{{ route('manageclass.delete') }}",
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
        successMsg: "{!! trans('admin/admin.delete-success') !!}"
    };
</script>
{!! HTML::script('js/schoolclass.js') !!}

<!-- END PAGE LEVEL SCRIPTS -->
@stop