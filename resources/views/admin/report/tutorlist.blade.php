@extends('admin.layout._default')
<?php
//asd($last_month_registrations);
//foreach($last_month_registrations as $key=>$val){
//    asd($val['stdClass Object']);
//}
//
// 
?>
@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/report.manage_parent_report'), 'trait_1' => trans('admin/report.parent_reports')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    @if(isset($userStats[TUTOR]))
                    {{ $userStats[TUTOR] }}
                    @else
                    {{ '0' }}
                    @endif
                </div>
                <div class="desc">
                    {!! trans('admin/admin.total_parents') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    @if(isset($last_month_registrations))
                    {{ $last_month_registrations }}
                    @else
                    {{ '0' }}
                    @endif
                </div>
                <div class="desc">
                    {!! trans('admin/admin.last_month_reg') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! trans('admin/report.parent_reports') !!}
                </div>
            </div>

            <div class="portlet-body">
                {!! Form::open(array('url' => 'tutor','class'=>'form-horizontal','id' =>'search-form' )) !!}
                <div class="form-body">
                    <div class="row">   
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('first_name',trans('admin/tutor.tutor_name'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-4">
                                    {!! Form::text('first_name',null,['id'=>'first_name','placeholder'=>trans('admin/tutor.search_tutor_name'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">    
                                {!! Form::labelControl('username',trans('admin/tutor.username'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-6">
                                    {!! Form::text('username',null,['id'=>'username','placeholder'=>trans('admin/tutor.search_username'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('email',trans('admin/tutor.email'),['class'=>'control-label col-md-5'], FALSE )  !!}
                                <div class="col-md-7">
                                    {!! Form::text('email',null,['id'=>'email','placeholder'=>trans('admin/tutor.search_email'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('status',trans('admin/school.status'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-6">
                                    {!! Form::select('status', $status, null ,['id' => 'status', 'class' => 'form-control']) !!} 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-12">
                                    {!! Form::button(trans('admin/admin.search'), array('type' => 'submit', 'class' => 'btn btn-primary')) !!} 
                                    {!! Form::button(trans('admin/admin.reset'), array('type' => 'reset', 'id'=>'reset','class' => 'btn btn-primary grey')) !!} 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
            <hr style="border-top: 1px solid #26a69a;" >
        </div>
        {!! Form::close() !!}
        <div class="info"></div>
        <table id="users-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr class="heading">
                    <th>{!! trans('admin/tutor.tutor_name') !!}</th>
                    <th>{!! trans('admin/tutor.email') !!}</th>
                    <th>{!! trans('admin/tutor.username') !!}</th>
                    <th>{!! trans('admin/tutor.date_of_registration') !!}</th>
                    <th>{!! trans('admin/admin.county') !!}</th>                    
                    <th>{!! trans('admin/tutor.status') !!}</th>
                    <th>{!! trans('admin/report.where_hear') !!}</th>
                    <th>{!! trans('admin/report.additional_purchases') !!}</th>
                    <th>{!! trans('admin/report.no_of_students') !!}</th>
                    <th>Renewal due date</th>
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
<!-- END PAGE LEVEL SCRIPTS -->

<script>
    var vars = {
        dataTable: "#users-table",
        listUrl: "{{ route('report.listparent') }}",
    };
    $(document).ready(function () {
        $("#status").select2();
    });
</script>
{!! HTML::script('js/dataTables.tableTools.js') !!}
{!! HTML::script('js/tutorreport.js') !!}

@stop