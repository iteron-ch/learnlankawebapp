@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/helpcentre.manage_helpcentre'), 'trait_1' => trans('admin/helpcentre.template_heading')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! trans('admin/helpcentre.template_heading') !!}
                </div>
                <?php
                $user_type = session()->get('user')['user_type'];
                if ($user_type == ADMIN) {
                    ?>
                    <div class="actions">
                        <a href="{{ route('helpcentre.create') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-plus"></i> {!! trans('admin/helpcentre.add_helpcentre') !!} </a>
                    </div>                
                <?php } ?>
            </div>
            <div class="portlet-body">
                {!! Form::open(array('url' => 'helpcentre','class'=>'form-horizontal','id' =>'search-form' )) !!}
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                {!! Form::labelControl('title',trans('admin/helpcentre.title'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-9">
                                    {!! Form::text('title',null,['id' => 'title','class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('category',trans('admin/helpcentre.category'),['class'=>'control-label col-md-3'])  !!}
                                <div class="col-md-8">
                                    {!! Form::select('category[]', $category, null, ['id'=>'category','multiple'=>'multiple','multiselect'=>'multiselect','class'=>'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">  
                                {!! Form::labelControl('strands_id',trans('admin/questionbuilder.strand'),[ 'class'=>'control-label col-md-3'])  !!}
                                <div class="col-md-8">
                                    {!! Form::select('strands_id[]', $strands, null, ['id'=>'strands_id','multiple'=>'multiple','class'=>'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('subStrands_id',trans('admin/helpcentre.substrand'),[ 'class'=>'control-label col-md-4'])  !!}
                                <div class="col-md-8">
                                    {!! Form::select('sub_strands_id[]', $substrands, null, ['id'=>'sub_strands_id','multiple'=>'multiple','class'=>'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-2">
                            <div class="form-group">
                                {!! Form::labelControl('status',trans('admin/helpcentre.status'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-9">
                                    {!! Form::select('status', $status, null ,['id' => 'status', 'class' => 'form-control select2me']) !!} 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="col-md-12">
                                    {!! Form::button(trans('admin/admin.search'), array('type' => 'submit', 'class' => 'btn btn-primary')) !!} 
                                    {!! Form::button(trans('admin/admin.reset'), array('type' => 'reset', 'id'=>'reset_helpcentre','class' => 'btn btn-primary grey')) !!} 
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr style="border-top: 1px solid #26a69a;" >
                </div>
                {!! Form::close() !!}   
                <table id="helpcentre-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/helpcentre.visible_to') !!}</th>
                            <th>{!! trans('admin/helpcentre.title') !!}</th>
                            <th>{!! trans('admin/helpcentre.category') !!}</th>
                            <th>{!! trans('admin/helpcentre.strand') !!}</th>
                            <th>{!! trans('admin/helpcentre.substrand') !!}</th>
                            <th>{!! trans('admin/helpcentre.help_file') !!}</th>
                            <th>{!! trans('admin/helpcentre.status') !!}</th>
                            <th>{!! trans('admin/helpcentre.updated_at') !!}</th>
                            <th>{!! trans('admin/helpcentre.actions') !!}</th>
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
{!! HTML::style('assets/global/plugins/jquery-ui/jquery-ui.min.css') !!}
{!! HTML::style('css/jquery.multiselect.css') !!}
{!! HTML::style('css/jquery.multiselect.filter.css') !!}
{!! HTML::script('js/jquery.multiselect.js') !!}
{!! HTML::script('js/jquery.multiselect.filter.js') !!}	 
<!-- BEGIN PAGE LEVEL PLUGINS -->
<style>
    div.checker input{opacity:1}
    .ui-multiselect-filter input {
        border: 1px solid #ddd;
        color: #333;
        font-size: 11px;
        height: auto;
    }
</style>
<script>

    jsMain = new Main("{{ trans('admin/admin.select_option') }}");
    $(window).load(function() {
        $("#category").multiselect();
        $("#strands_id").multiselect();
        $("#sub_strands_id").multiselect();
        $("#reset_helpcentre").click(function() {
            setTimeout("$('#search-form').submit();", "400");
        });
    });
    var vars = {
        dataTable: "#helpcentre-table",
        listUrl: "{{ route('helpcentre.listrecord') }}",
        addUrl: "{{ route('helpcentre.create') }}",
        deleteUrl: "{{ route('helpcentre.delete') }}",
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
        successMsg: "{!! trans('admin/admin.delete-success') !!}"
    };


</script>
{!! HTML::script('js/helpcentre.js') !!}
@stop