@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/admin.dashboard') ])
<!-- END PAGE HEADER-->

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
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue-madison">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>{!! trans('admin/admin.reg_per_month') !!}
                </div>
            </div>
            <div class="portlet-body">
                <div id="graph_div" style="height:277px;"></div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix">
</div>
<div class="row">
    <div class="col-md-6">
        <div class="portlet box blue-madison">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>{!! trans('admin/admin.total_registration_parent_tutor') !!}
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-light table-hover">
                    <tr>
                        <td width=30% >
                            <strong>{!! trans('admin/admin.total_registration')!!}</strong>
                        </td>
                        <td>
                              <?php echo ': ' . $userStats[TUTOR]; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width=30% >
                            <strong>{!! trans('admin/admin.active_members')!!}</strong>
                        </td>
                        <td>
                              <?php echo ': ' . $activeTutors[0]->activeTutors; ?><br>
                        </td>
                    </tr>
                    <tr>
                        <td width=30% >
                            <strong> {!! trans('admin/admin.inactive_members')!!}</strong>
                        </td>
                        <td>
                             <?php echo ': ' . $inactiveTutors[0]->inactiveTutors; ?><br>
                        </td>
                    </tr>
                    <tr>
                        <td width=30% >
                            <strong>{!! trans('admin/admin.inactive_members_last')!!} </strong>
                        </td>
                        <td>
                             <?php echo ': ' . $lastMonthInactiveTutors[0]->lastMonthInactiveTutors; ?><br>
                        </td>
                    </tr>
                    <tr>
                        <td width=30% >
                            <strong>{!! trans('admin/admin.total_registration_last')!!} </strong>
                        </td>
                        <td>
                             <?php echo ': ' . $lastMonthActiveTutors[0]->lastMonthActiveTutors; ?><br>
                        </td>
                    </tr>
                    
                </table>
                <!--end profile-settings-->
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="portlet box blue-madison">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>{!! trans('admin/admin.total_registration_school') !!}
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-light table-hover">
                    <tr>
                        <td width=30% >
                            <strong> {!! trans('admin/admin.total_registration')!!}</strong>
                        </td>
                        <td>
                            <?php echo ': ' . $userStats[SCHOOL]; ?><br>
                        </td>
                    </tr>
                    <tr>
                        <td width=30% >
                            <strong> {!! trans('admin/admin.active_members')!!}</strong>
                        </td>
                        <td>
                             <?php echo ': ' . $activeSchools[0]->activeSchools; ?><br>
                        </td>
                    </tr>
                    <tr>
                        <td width=30% >
                            <strong>{!! trans('admin/admin.inactive_members')!!}</strong>
                        </td>
                        <td>
                            <?php echo ': ' . $inactiveSchools[0]->inactiveSchools; ?><br>
                        </td>
                    </tr>
                    <tr>
                        <td width=30% >
                            <strong> {!! trans('admin/admin.inactive_members_last')!!} </strong>
                        </td>
                        <td>
                            <?php echo ': ' . $lastMonthInactiveSchools[0]->lastMonthInactiveSchools; ?><br>
                        </td>
                    </tr>
                    <tr>
                        <td width=30% >
                            <strong>{!! trans('admin/admin.total_registration_last')!!}</strong>
                        </td>
                        <td>
                            <?php echo ': ' . $lastMonthActiveSchools[0]->lastMonthActiveSchools; ?><br>
                        </td>
                    </tr>
                    
                </table>
                <!--end profile-settings-->
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
{!! HTML::script('assets/global/plugins/flot//jquery.flot.pie.js') !!}
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
        
        google.setOnLoadCallback(drawStuff);
        function drawStuff() {
        var data = new google.visualization.arrayToDataTable(
                [<?php echo $recordRegistredArray ?>
                ]
                );
                var options = {
                width:'50%',
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
        Index.init();
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

        Index.initDashboardDaterange();
        // Index.initJQVMAP(); // init index page's custom scripts
        Index.initCalendar(); // init index page's custom scripts
        Index.initCharts(); // init index page's custom scripts
        Index.initMiniCharts();
        Tasks.initDashboardWidget();
        //$.plot($("#placeholder_type_of_school"), dataSetTypeOfSchool, options);
        $.plot($("#placeholder_top_five_school_county"), countiesSchoolUsersArray, options);
        $.plot($("#placeholder_top_five_parent_county"), countiesParentUsersStats, options);
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