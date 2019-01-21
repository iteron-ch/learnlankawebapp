@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/report.manage_school'), 'trait_1' => trans('admin/report.school_reports')])
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
                    @if(isset($userStats[SCHOOL]))
                    {{ $userStats[SCHOOL] }}
                    @else
                    {{ '0' }}
                    @endif
                </div>
                <div class="desc">
                    {!! trans('admin/admin.total_schools') !!}
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
                    @if(isset($userStats[SCHOOL]))
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
                    <i class="fa"></i>{!! trans('admin/report.school_reports') !!}
                </div>
            </div>
            <div class="portlet-body">
                {!! Form::open(array('url' => 'report','class'=>'form-horizontal','id' =>'search-form' )) !!}
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('school_name',trans('admin/report.school_name'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::text('school_name',null,['placeholder'=>trans('admin/report.search_school_name'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('email',trans('admin/report.email'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::text('email',null,['placeholder'=>trans('admin/report.search_email'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('status',trans('admin/report.status'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::select('status', $status, null ,['id' => 'status', 'class' => 'form-control']) !!} 
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
                <div class="info"></div>
                {!! Form::close() !!}   
                <table id="school-report" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/report.school_name') !!}</th>
                            <th>{!! trans('admin/report.type_of_school') !!}</th>
                            <th>{!! trans('admin/report.email') !!}</th>
                            <th>{!! trans('admin/report.date_of_registration') !!}</th>
                            <th>{!! trans('admin/report.county') !!}</th>
                            <th>{!! trans('admin/report.no_of_students') !!}</th>
                            <th>{!! trans('admin/report.where_hear') !!}</th>
                            <th>{!! trans('admin/report.status') !!}</th>
                            <th>{!! trans('admin/report.no_of_teachers') !!}</th>
                            <th>{!! trans('admin/report.sub_end_date') !!}</th>
                            <th>Renewal Due Date</th>
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
        dataTable: "#school-report",
        listUrl: "{{ route('report.listschool') }}",
    };
    $(document).ready(function () {
        $("#status").select2();
    });
</script>
{!! HTML::script('js/dataTables.tableTools.js') !!}
{!! HTML::script('js/report.js') !!}
@stop