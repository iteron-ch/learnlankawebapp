@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/admin.dashboard') ])
<!-- END PAGE HEADER-->
<!-- BEGIN DASHBOARD STATS -->
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                     <a href='{{ route('messages.inbox') }}' style="color:white">{{ Auth::user()->newMessagesCount() }}</a>
                </div>
                <div class="desc">
                    {!! trans('admin/admin.new_messages') !!}
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
                    @if(isset($userStats[STUDENT]))
                    {{ $userStats[STUDENT] }}
                    @else
                    {{ '0' }}
                    @endif
                </div>
                <div class="desc">
                    {!! trans('admin/admin.total_students') !!}
                </div>
            </div>

        </div>
    </div>
    @if(isset($notificationArray[0]))
    <div class="col-lg-6 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">Notifications</div>
                <div class="desc">
                    @if(isset($notificationArray[0]))
                    {{ $notificationArray[0]['description'] }}
                    @else
                    {{ 'Not Avaliable' }}
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<!-- END DASHBOARD STATS -->
<div class="clearfix"></div>

<div class="clearfix"></div>
<!--<div class="row">
   <div class="col-md-6 col-sm-6">
        <div class="portlet light bordered">
            <div class="portlet-title ">
                <div class="caption">
                    <i class="font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp ">Recent Activity</span>
                </div>
            </div>
            <div class="portlet-body">
                <table id="activity-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>Activity</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>  
    </div> 
</div>-->
<!--<div class="row">
    <div class="col-md-6 col-sm-6">
        <div class="portlet light bordered">
            <div class="portlet-title ">
                <div class="caption">
                    <i class="font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp ">{!! trans('admin/admin.latest_ten_teachers') !!}</span>
                </div>
            </div>
            <div class="portlet-body">
                <table id="school-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/teacher.teacher_name') !!}</th>
                            <th>{!! trans('admin/teacher.date_of_registration') !!}</th>
                            <th>{!! trans('admin/teacher.no_of_students') !!}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6">
        <div class="portlet light bordered">
            <div class="portlet-title ">
                <div class="caption">
                    <i class="font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp ">{!! trans('admin/admin.latest_ten_students') !!}</span>
                </div>
            </div>
            <div class="portlet-body">

                <table id="tutor-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/student.student_name') !!}</th>
                            <th>{!! trans('admin/student.date_of_registration') !!}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>  
    </div>
</div>-->
<!--<div class="clearfix"></div>
<div class="row">
    <div class="col-md-6 col-sm-6">
        <div class="portlet light bordered">
            <div class="portlet-title ">
                <div class="caption">
                    <i class="font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp ">{!! trans('admin/admin.latest_ten_deactivated_teachers') !!}</span>
                </div>
            </div>
            <div class="portlet-body">
                <table id="deactive-school-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/teacher.teacher_name') !!}</th>
                            <th>{!! trans('admin/teacher.date_of_registration') !!}</th>
                            <th>{!! trans('admin/teacher.no_of_students') !!}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6">
        <div class="portlet light bordered">
            <div class="portlet-title ">
                <div class="caption">
                    <i class="font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp">{!! trans('admin/admin.latest_ten_deactivated_students') !!}</span>
                </div>
            </div>
            <div class="portlet-body">
                <table id="deactive-tutor-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/student.student_name') !!}</th>
                            <th>{!! trans('admin/student.date_of_registration') !!}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>-->
</div>
</div>
<!-- END CONTENT -->
@stop

@section('pagecss')
<link href="../../assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
{!! HTML::style('assets/global/plugins/jqvmap/jqvmap/jqvmap.css') !!}
@stop

@section('pagescripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->
{!! HTML::script('assets/global/plugins/flot/jquery.flot.min.js') !!}
{!! HTML::script('assets/global/plugins/flot/jquery.flot.resize.min.js') !!}
{!! HTML::script('assets/global/plugins/flot/jquery.flot.categories.min.js') !!}
{!! HTML::script('assets/global/plugins/jquery.pulsate.min.js') !!}
{!! HTML::script('assets/global/plugins/flot//jquery.flot.pie.js') !!}

<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support -->
{!! HTML::script('assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js') !!}
{!! HTML::script('assets/global/plugins/jquery.sparkline.min.js') !!}
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
{!! HTML::script('assets/admin/pages/scripts/index.js') !!}
{!! HTML::script('assets/admin/pages/scripts/tasks.js') !!}
<?php
?>
<script>

    jQuery(document).ready(function () {
        Index.init();
        Index.initDashboardDaterange();
        Tasks.initDashboardWidget();

    });
    var vars = {
        teacherListUrl: "{{ route('teacher.listrecord') }}",
        studentListUrl: "{{ route('student.listrecord') }}",
        interventionTopicListUrl: "{{ route('task.interventiontopics') }}",
        activitytopicsUrl: "{{ route('task.activitytopics') }}",
        dashboardtestresultUrl: "{{ route('task.dashboardtestresult') }}",
    };
</script>
{!! HTML::script('js/dashboard.school.admin.js') !!}
@stop