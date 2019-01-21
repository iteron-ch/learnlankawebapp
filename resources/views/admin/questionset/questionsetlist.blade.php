@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/questionset.manage_questionset'), 'trait_1' => trans('admin/questionset.question_set')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! trans('admin/questionset.question_set') !!}
                </div>
                <div class="actions">
                    <a href="{{ route('questionset.create') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-plus"></i> {!! trans('admin/questionset.add_questionset') !!} </a>

                </div>                
            </div>
            <div class="portlet-body">
                {!! Form::open(array('url' => 'questionset','class'=>'form-horizontal','id' =>'search-form' )) !!}
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('set_name',trans('admin/questionset.set_name'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::text('set_name',null,['placeholder'=>trans('admin/questionset.enter_name'),'id'=>'set_name','class'=>'form-control'])  !!}  
                                </div>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('key_stage',trans('admin/questionset.key_stage'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::select('key_stage', $keyStage,null, ['id' => 'key_stage', 'class' => 'form-control select2me']) !!}   
                                </div>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('year_group',trans('admin/questionset.year_group'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::select('year_group', [],null, ['id' => 'year_group', 'class' => 'form-control select2me']) !!}   
                                </div>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('subject',trans('admin/questionset.subject'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::select('subject', $setSubject,null, ['id' => 'subject', 'class' => 'form-control select2me']) !!}    
                                </div>

                            </div>
                        </div>
                    </div>    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('set_group',trans('admin/questionset.set_group'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::select('set_group', $setGroup, null ,['id' => 'set_group', 'class' => 'form-control select2me']) !!} 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('status',trans('admin/questionset.set_status'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::select('status', $status, null ,['id' => 'status', 'class' => 'form-control select2me']) !!} 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="col-md-12">
                                    {!! Form::button(trans('admin/questionset.search'), array('type' => 'submit', 'class' => 'btn btn-primary')) !!} 
                                    {!! Form::button(trans('admin/admin.reset'), array('type' => 'reset', 'id'=>'reset','class' => 'btn btn-primary grey')) !!} 
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr style="border-top: 1px solid #26a69a;" >
                </div>
                {!! Form::close() !!}
                <table id="questionset-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/questionset.name') !!}</th>
                            <th>{!! trans('admin/questionset.total') !!}</th>
                            <th>{!! trans('admin/questionset.published') !!}</th>
                            <th>{!! trans('admin/questionset.draft') !!}</th>
                            <th>{!! trans('admin/questionset.contributers') !!}</th>
                            <th>{!! trans('admin/questionset.created_at') !!}</th>
                            <th>{!! trans('admin/questionset.set_group') !!}</th>
                            <th>{!! trans('admin/questionset.allow_print') !!}</th>
                            <th>{!! trans('admin/questionset.status') !!}</th>
                            <th>{!! trans('admin/questionset.actions') !!}</th>
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
    jsMain = new Main("{{ trans('admin/admin.select_option') }}");
    var vars = {
        dataTable: "#questionset-table",
        listUrl: "{{ route('questionset.listrecord') }}",
        addUrl: "{{ route('questionset.create') }}",
        deleteUrl: "{{ route('questionset.delete') }}",
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
        successMsg: "{!! trans('admin/admin.delete-success') !!}",
        yearDD: <?php echo $yearKeysJson ?>
    };
</script>
{!! HTML::script('js/questionset.js') !!}
@stop