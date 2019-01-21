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
                    {!! trans('admin/admin.total_tutor') !!}
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
                    {!! trans('admin/admin.total_teachers') !!}
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

</div>
<!-- END DASHBOARD STATS -->
<div class="clearfix">
</div>

<div class="row">
    <div class="col-md-6">
        <div class="portlet box blue-madison">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>{!! trans('admin/admin.type_of_school') !!}
                </div>
            </div>
            <div class="portlet-body">
                <div id="placeholder_type_of_school" class="chart"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="portlet box blue-madison">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>{!! trans('admin/admin.how_did_you_hear_tutor') !!}
                </div>
            </div>
            <div class="portlet-body">
                <div id="placeholder_how_here_parent" class="chart"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="portlet box blue-madison">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>{!! trans('admin/admin.top_five_school_counties') !!}
                </div>
            </div>
            <div class="portlet-body">
                <div id="placeholder_top_five_school_county" class="chart"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="portlet box blue-madison">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>{!! trans('admin/admin.top_five_parent_counties') !!}
                </div>
            </div>
            <div class="portlet-body">
                <div id="placeholder_top_five_parent_county" class="chart"></div>
            </div>
        </div>
        <!--<div class="portlet box blue-madison">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>How did you hear about us (School)
                </div>
            </div>
            <div class="portlet-body">
                <div id="flot-placeholder" class="chart"></div>
            </div>
        </div>-->
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="portlet box blue-madison">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>{!! trans('admin/admin.how_did_you_hear_school') !!}
                </div>
            </div>
            <div class="portlet-body">
                <div id="placeholder_how_here_school" class="chart"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="portlet box blue-madison">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>{!! trans('admin/admin.reg_per_month') !!}
                </div>
            </div>
            <div class="portlet-body">
                <div id="graph_div" style="height:300px;"></div>
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
                        <span class="caption-subject font-green-sharp ">{!! trans('admin/admin.latest_ten_schools') !!}</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table id="school-table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr class="heading">
                                <th>{!! trans('admin/school.school_name') !!}</th>
                                <th>{!! trans('admin/admin.no_of_students') !!}</th>
                                <th>{!! trans('admin/school.date_of_registration') !!}</th>
                                <th>{!! trans('admin/school.no_of_teachers') !!}</th>
                                <th>{!! trans('admin/school.renewal_due_date') !!}</th>
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
                        <span class="caption-subject font-green-sharp ">{!! trans('admin/admin.latest_ten_activated_tutors') !!}</span>
                    </div>
                </div>
                <div class="portlet-body">

                    <table id="tutor-table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr class="heading">
                                <th>{!! trans('admin/tutor.tutor_name') !!}</th>
                                <th>{!! trans('admin/tutor.date_of_registration') !!}</th>
                                <th>{!! trans('admin/admin.no_of_students') !!}</th>
                                <th>{!! trans('admin/school.renewal_due_date') !!}</th>
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
                        <span class="caption-subject font-green-sharp ">{!! trans('admin/admin.latest_ten_deactivated_schools') !!}</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table id="deactive-school-table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr class="heading">
                                <th>{!! trans('admin/school.school_name') !!}</th>
                                <th>{!! trans('admin/admin.no_of_students') !!}</th>
                                <th>{!! trans('admin/school.deactivation_at') !!}</th>
                                <th>{!! trans('admin/school.registration_date') !!}</th>
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
                        <span class="caption-subject font-green-sharp ">{!! trans('admin/admin.latest_ten_deactivated_tutors') !!}</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table id="deactive-tutor-table" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr class="heading">
                                <th>{!! trans('admin/tutor.tutor_name') !!}</th>
                                <th>{!! trans('admin/school.deactivation_at') !!}</th>                            
                                <th>{!! trans('admin/school.registration_date') !!}</th>
                                <th>{!! trans('admin/admin.no_of_students') !!}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
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
{!! HTML::script('assets/global/plugins/flot/jquery.flot.pie.js') !!}
{!! HTML::script('assets/global/plugins/flot/jquery.flot.tooltip.js') !!}

<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support -->
{!! HTML::script('assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js') !!}
{!! HTML::script('assets/global/plugins/jquery.sparkline.min.js') !!}
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
{!! HTML::script('assets/admin/pages/scripts/index.js') !!}
{!! HTML::script('assets/admin/pages/scripts/tasks.js') !!}
<?php
?>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['bar']}]}"></script>
<script>
        var dataSetTypeOfSchool = [<?php echo implode(',', $typeOfSchoolStats) ?>];
        var countiesSchoolUsersArray = [<?php echo implode(',', $countiesSchoolUsersArray) ?>];
        var countiesParentUsersStats = [<?php echo implode(',', $countiesParentUsersStats) ?>];
        var datahowHearParant = [<?php echo implode(',', $howHearParantArray) ?>];
        var datahowHearSchool = [<?php echo implode(',', $howHearSchoolArray) ?>];
        var options = {
        series: {
        pie: {
        show: true,
                innerRadius: 2,
                label: {
                show: true
                }
        }
        }
        };
        google.setOnLoadCallback(drawStuff);
        function drawStuff() {
        var data = new google.visualization.arrayToDataTable(
                [<?php echo $recordRegistredArray ?>
                ]
                );
                var options = {
                        vAxis: {title:'No of Registrations'},
                        hAxis: {title:'Months'},
                        chart: {
                        //title: 'Progress',
                        },
                        bars: 'verticals', // Required for Material Bar Charts.

                };
                var chart = new google.charts.Bar(document.getElementById('graph_div'));
                chart.draw(data, google.charts.Bar.convertOptions(options));
        };
        jQuery(document).ready(function () {
        
        
        var options = {
        series: {
                    pie: {
                        show: true
                    }
                },
                grid: {
                    hoverable: true
                },
                tooltip: true,
                tooltipOpts: {
                    content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
                    shifts: {
                        x: 20,
                        y: 0
                    },
        
                }      
        };
        
Index.init();
        Index.initDashboardDaterange();
        // Index.initJQVMAP(); // init index page's custom scripts
        Index.initCalendar(); // init index page's custom scripts
        Index.initCharts(); // init index page's custom scripts
        Index.initMiniCharts();
        Tasks.initDashboardWidget();
        $.plot($("#placeholder_type_of_school"), dataSetTypeOfSchool, options);
        $.plot($("#placeholder_top_five_school_county"), countiesSchoolUsersArray, options);
        $.plot($("#placeholder_top_five_parent_county"), countiesParentUsersStats, options);
        $.plot($("#placeholder_how_here_parent"), datahowHearParant, options);
        $.plot($("#placeholder_how_here_school"), datahowHearSchool, options);
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