@extends('admin.layout._default')
@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/rewards.manage_test_rewards'), 'trait_1' => trans('admin/rewards.test_rewards')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! trans('admin/rewards.test_rewards') !!}
                </div>
                <div class="actions">
                    <a href="{{ route('rewards.create','test') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-plus"></i> {!! trans('admin/rewards.add_rewards') !!} </a>
                </div>                
            </div>
            <div class="portlet-body">
                {!! Form::open(array('url' => 'rewards','class'=>'form-horizontal','id' =>'search-form' )) !!}
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-helpcentre">
                                {!! Form::labelControl('percentage',trans('admin/rewards.percentage'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-6">
                                    {!! Form::text('percentage',null,['id' => 'percentage','class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('subject',trans('admin/questionset.subject'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-6">
                                    {!! Form::select('subject', $subject, null,['id' => 'subject', 'class' => 'form-control select2me']) !!} 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('questionset',trans('admin/rewards.questionset'),['class'=>'control-label col-md-6'], FALSE )  !!}
                                <div class="col-md-6">
                                    {!! Form::select('questionset', $questionsets, null,['id' => 'questionset', 'class' => 'form-control select2me']) !!} 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('title',trans('admin/rewards.title'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-7">
                                    {!! Form::select('title', $title, null,['id' => 'title', 'class' => 'form-control select2me']) !!} 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-helpcentre">
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
                <table id="test-rewards-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/rewards.percentage') !!} (%)</th>
                            <th>{!! trans('admin/rewards.title') !!}</th>
                            <th>{!! trans('admin/task.subject') !!}</th>
                            <th>{!! trans('admin/task.question_set') !!}</th>
                            <th>{!! trans('admin/admin.created_at') !!}</th>
                            <th>{!! trans('admin/admin.status') !!}</th>
                            <th>{!! trans('admin/rewards.actions') !!}</th> 
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
        dataTable: "#test-rewards-table",
        listUrl: "{{ route('rewards.listrecord','test') }}",
        deleteUrl: "{{ route('rewards.delete') }}",
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
        successMsg: "{!! trans('admin/admin.delete-success') !!}"

    };
</script>
{!! HTML::script('js/rewardstest.js') !!}
@stop