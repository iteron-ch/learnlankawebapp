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
                    @if(isset($userStats[TUTOR]))
                    {{ $userStats[TUTOR] }}
                    @else
                    {{ '0' }}
                    @endif
                </div>
                <div class="desc">
                    Total Parents / Tutors
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
                    @if(isset($userStats[TEACHER]))
                    {{ $userStats[TEACHER] }}
                    @else
                    {{ '0' }}
                    @endif
                </div>
                <div class="desc">
                    Total Teachers
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
                    Total Students
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
                    {{ $userStats[SCHOOL] }}
                    @else
                    {{ '0' }}
                    @endif
                </div>
                <div class="desc">
                    Total Schools
                </div>
            </div>

        </div>
    </div>

</div>
<!-- END DASHBOARD STATS -->

<div class="clearfix">
</div>
<div class="row">
    <div class="col-md-6 col-sm-6">
        <div class="portlet light bordered">
            <div class="portlet-title ">
                <div class="caption">
                    <i class="font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp ">Latest 10 Schools</span>
                </div>
            </div>
            <div class="portlet-body">
                <table id="school-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/school.school_name') !!}</th>
                            <th>{!! trans('admin/school.no_of_students') !!}</th>
                            <th>{!! trans('admin/school.date_of_registration') !!}</th>
                            <th>{!! trans('admin/school.no_of_teachers') !!}</th>
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
                    <span class="caption-subject font-green-sharp ">Latest 10 Parents / Tutors</span>
                </div>
            </div>
            <div class="portlet-body">

                <table id="tutor-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/tutor.tutor_name') !!}</th>
                            <th>{!! trans('admin/tutor.date_of_registration') !!}</th>
                            <th>{!! trans('admin/school.no_of_students') !!}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>  
    </div>
</div>
<div class="clearfix">
</div>
<div class="row">
    <div class="col-md-6 col-sm-6">
        <div class="portlet light bordered">
            <div class="portlet-title ">
                <div class="caption">
                    <i class="font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp ">Latest 10 Deactivated Schools</span>
                </div>
            </div>
            <div class="portlet-body">
                <table id="deactive-school-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/school.school_name') !!}</th>
                            <th>{!! trans('admin/school.no_of_students') !!}</th>
                            <th>{!! trans('admin/admin.deactivation_at') !!}</th>
                            <th>{!! trans('admin/school.date_of_registration') !!}</th>
                            <th>{!! trans('admin/school.no_of_teachers') !!}</th>
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
                    <span class="caption-subject font-green-sharp ">Latest 10 Deactivated Tutors / Parents</span>
                </div>
            </div>
            <div class="portlet-body">
                <table id="deactive-tutor-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('admin/tutor.tutor_name') !!}</th>
                            <th>{!! trans('admin/admin.deactivation_at') !!}</th>                            
                            <th>{!! trans('admin/tutor.date_of_registration') !!}</th>
                            <th>{!! trans('admin/school.no_of_students') !!}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    
</div>
<div class="clearfix">
</div>
<div class="row">
    <div class="col-md-6 col-sm-6">
        <!-- BEGIN PORTLET-->
        <div class="portlet light calendar bordered">
            <div class="portlet-title ">
                <div class="caption">
                    <i class="icon-calendar font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp ">Feeds</span>
                </div>
            </div>
            <div class="portlet-body">
                <div id="calendar">
                </div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>

</div>
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


<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support -->
{!! HTML::script('assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js') !!}
{!! HTML::script('assets/global/plugins/jquery.sparkline.min.js') !!}
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
{!! HTML::script('assets/admin/pages/scripts/index.js') !!}
{!! HTML::script('assets/admin/pages/scripts/tasks.js') !!}
<script>
    jQuery(document).ready(function () {
        Index.init();
        Index.initDashboardDaterange();
        // Index.initJQVMAP(); // init index page's custom scripts
        Index.initCalendar(); // init index page's custom scripts
        Index.initCharts(); // init index page's custom scripts
        //Index.initMiniCharts();
        Tasks.initDashboardWidget();
    });

    var vars = {
        schoolListUrl: "{{ route('school.listrecord') }}",
        tutorListUrl: "{{ route('tutor.listrecord') }}",
    };
</script>
@if(session()->get('user')['user_type'] == ADMIN))
{!! HTML::script('js/dashboard.admin.js') !!}
@endif
@stop