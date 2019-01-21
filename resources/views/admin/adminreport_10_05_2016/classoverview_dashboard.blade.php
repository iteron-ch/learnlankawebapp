@extends('admin.adminreport.classoverview') 

@section('report_dashboard')
<div class="tab-pane fade  @if($testtype == 'dashboard') active in @endif" id="tab_1_3">
        <div style="float:right;margin:15px 16px 0 0 ;"><a href="#" onclick="window.print()">Print Report</a></div>
        <ul class="nav nav-tabs">
            <li class="@if($tab == 'eng')active @endif" onclick="getreport(<?php echo $schoolId; ?>,<?php echo $classId; ?>, '<?php echo $testtype; ?>', 'eng')">
                <a href="#tab_4_1" data-toggle="tab">
                    <span>English</span>
                </a>
            </li>
            <li class="@if($tab == 'math')active @endif" onclick="getreport(<?php echo $schoolId; ?>,<?php echo $classId; ?>, '<?php echo $testtype; ?>', 'math')">
                <a href="#tab_4_2" data-toggle="tab">
                    <span>Math</span>
                </a>
            </li>

        </ul>
        <div class="tab-content">
            <div class="tab-pane active">
                <div class="table-scrollable">
                    @if($testtype == 'dashboard')
                    <div>
                        <?php if (!empty($dashboarddetail['classList']) && !empty($dashboarddetail['allClassTestAvg'])) { ?>
                        <div class="portlet-title blue_heading">
                            <div class="noExl caption">
                                <input type="button" class="btn default" id="exportgraphclass" name="exportgraphclass" value="Export Class Test Graph" onclick="exportClassTestGraph(<?php echo $schoolId; ?>, <?php echo $classId; ?>, '<?php echo $testtype; ?>', 'eng')"/>
                            </div>
                        </div>

                        <div id="chart_div" style="height:450px"></div>
                        <?php
                        }
                        ?>
                        <?php if (!empty($dashboarddetail['classList']) && !empty($dashboarddetail['allClassStudentAvg'])) { ?>
                        <div class="portlet-title blue_heading">
                            <div class="noExl caption">
                                <input type="button" class="btn default" id="exportgraph" name="exportgraph" value="Export Class Progress Graph" onclick="exportClassProgressGraph(<?php echo $schoolId; ?>, <?php echo $classId; ?>, '<?php echo $testtype; ?>', 'eng')" />
                            </div>
                        </div>
                        <div id="chart_div1" style="height: 500px;"></div>
                        <?php } ?>
                        <div>&nbsp</div><div>&nbsp</div>
                        <div class="portlet box green">
                            <div class="portlet-title">
                                <div class="caption">
                                    <input type="button" class="btn default" name="export" value="Export To Excel" onclick="exportdata('schooldashboardeng','school_dashboard_eng')"/>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <?php foreach($dashboarddetail['pupilPerformanceData'] as $ppData){?>
                                    <table id="schooldashboardeng" class="table table-striped table-bordered table-hover dataTable no-footer">
                                    <tr>
                                        <td style="padding:0px;">
                                            <div class="portlet-title blue_heading">
                                                <div class="noExl caption">
                                                    <strong><?php echo $ppData['title']?></strong>
                                                </div>
                                            </div>
                                        </td>    
                                    </tr>
                                    <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="portlet box red">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <strong>Performance</strong>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <div class="table-scrollable">
                                                            <table class=" noExl table table-striped table-bordered table-hover dataTable no-footer">
                                                                <thead>
                                                                    <tr class="noExl heading" role="row">
                                                                        <th class="col-md-4 text-center">Student Name</th>
                                                                        <th class="col-md-4 text-center">Average Test %</th>   
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if(count($ppData['data'])){
                                                                        foreach($ppData['data'] as $ppDataPuipl){?>
                                                                    <tr class="noExl">
                                                                        <td><?php echo $ppDataPuipl['student_name']?></td>
                                                                        <td><?php echo round($ppDataPuipl['avgScore'])?>%</td>
                                                                    </tr>
                                                                    <?php }
                                                                        } else {?>
                                                                    <tr class="noExl">
                                                                        <td colspan="2">No Record.</td>
                                                                    </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="portlet box red">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                           <strong> Usage</strong>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <div class="table-scrollable">
                                                            <table class=" noExl table table-striped table-bordered table-hover dataTable no-footer">
                                                                <thead>
                                                                    <tr class="noExl heading" role="row">
                                                                        <th class="col-md-4 text-center">Student Name</th>
                                                                        <th class="col-md-4 text-center">Timeliness</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if(count($ppData['data'])){
                                                                        foreach($ppData['data'] as $ppDataPuipl){
                                                                            $avgTime =  round($ppDataPuipl['avgTime']);
                                                                            $avgTotalTime =  round($ppDataPuipl['avgTotalTime']);
                                                                            if($avgTime > $avgTotalTime){
                                                                                $full = 'full';
                                                                                $color = 'red';
                                                                                $width = '100';
                                                                            }elseif($avgTime == $avgTotalTime){
                                                                                $full = 'full';
                                                                                $color = 'green';
                                                                                $width = '100';
                                                                            }else{
                                                                                $full = '';
                                                                                $color = 'green';
                                                                                $width = round(($avgTime/$avgTotalTime)*100);
                                                                            }
                                                                            ?>
                                                                    <tr class="noExl">
                                                                        <td><?php echo $ppDataPuipl['student_name']?></td>
                                                                        <td>
                                                                            <div class="progress_bar">
                                                                                <div class="bar <?php echo $color?> <?php echo $full?>" style="width:<?php echo $width?>%">&nbsp;</div>
                                                                                <span><?php echo $avgTime?></span>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <?php }
                                                                        } else {?>
                                                                    <tr class="noExl">
                                                                        <td colspan="2">No Record.</td>
                                                                    </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="portlet box red">
                                                    <div class="portlet-title">
                                                        <div class="noExl caption">
                                                            <strong>Timeliness</strong>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <div class="table-scrollable">
                                                            <table class=" noExl table table-striped table-bordered table-hover dataTable no-footer">
                                                                <thead>
                                                                    <tr class="noExl heading" role="row">
                                                                        <th class="col-md-4 text-center">Student Name</th>
                                                                        <th class="col-md-2 text-center">Average Timeliness (minutes)</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if(count($ppData['data'])){
                                                                        foreach($ppData['data'] as $ppDataPuipl){
                                                                            $avgTime =  round($ppDataPuipl['avgTime']);
                                                                            $avgTotalTime =  round($ppDataPuipl['avgTotalTime']);
                                                                            if($avgTime > $avgTotalTime){
                                                                                $full = 'full';
                                                                                $color = 'red';
                                                                                $width = '100';
                                                                            }elseif($avgTime == $avgTotalTime){
                                                                                $full = 'full';
                                                                                $color = 'green';
                                                                                $width = '100';
                                                                            }else{
                                                                                $full = '';
                                                                                $color = 'green';
                                                                                $width = round(($avgTime/$avgTotalTime)*100);
                                                                            }
                                                                            ?>
                                                                    <tr class="noExl">
                                                                        <td class="col-md-4"><?php echo $ppDataPuipl['student_name']?></td>
                                                                        <td class="col-md-2">
                                                                            <div class="progress_bar">
                                                                                <div class="bar <?php echo $color?> <?php echo $full?>" style="width:<?php echo $width?>%">&nbsp;</div>
                                                                                <span><?php echo $avgTime?></span>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <?php }
                                                                        } else {?>
                                                                    <tr class="noExl">
                                                                        <td colspan="2">No Record.</td>
                                                                    </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                    </tr>
                                    </table>
                                <?php } ?>

                            </div>
                        </div>

                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@stop
@section('graphjs')
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
<?php if (!empty($dashboarddetail['classList']) && !empty($dashboarddetail['allClassTestAvg'])) { ?>
    var classList = <?php echo json_encode($dashboarddetail['classList'])?>;
    var allClassTestAvg = <?php echo json_encode($dashboarddetail['allClassTestAvg'])?>;

    google.load('visualization', '1', {packages: ['corechart', 'line','bar']});
    google.setOnLoadCallback(drawLineColors);
    function drawLineColors() {
        var data = new google.visualization.DataTable();
        data.addColumn('number', 'X');
        $.each(classList,function(k,val){
            data.addColumn('number', val);
        });
        var rows = [];
        $.each(allClassTestAvg,function(k,val){
            rows[k] = [];
            rows[k].push(k+1);
            $.each(val,function(k1,val1){
                rows[k].push(val1);
            });
        });
        data.addRows(rows);
        var view = new google.visualization.DataView(data);
        var range = view.getColumnRange(0);
        var offset = 1; // change this to move the left/right margins of the chart
        var options = {
            hAxis: {
                title: 'Test Set',
                viewWindow: {
                    min: 1,
                    offset: 1,
                },
            },
            vAxis: {
                title: 'Class Percentage (%)',
            },
            pointSize: 5,
        };
        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
    function exportClassTestGraph(schoolId,classId,report,tab){
        window.location.href = "classoverview/export/classtestgraph?schoolId=" + schoolId + "&classId="+classId+"&report=" + report + "&tab="+tab;
    }
<?php }?>
<?php if (!empty($dashboarddetail['classList']) && !empty($dashboarddetail['allClassStudentAvg'])) { ?>
    var allClassStudentAvg = <?php echo json_encode($dashboarddetail['allClassStudentAvg'])?>;
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawBarChart);
    var dataArr = [];
    dataArr[0] = ['Class Progress', 'Less Than 20%', '21-50% ', '51-70%',' Greater than 70%']
    var dKey = 1;
    $.each(allClassStudentAvg,function(k,val){
        dataArr[dKey] = [];
        $.each(val,function(k1,val1){
            dataArr[dKey].push(val1);
        });
        dKey++;
    });
    function drawBarChart() {
        var data = google.visualization.arrayToDataTable(dataArr);

        var options = {
            vAxis: {title: 'Number of Pupils',format: 'decimal'},
            hAxis: {title: 'Class Progress'},
            seriesType: 'bars',
            colors: ['red', 'orange', 'green', 'blue']
        };
        var chart = new google.visualization.ComboChart(document.getElementById('chart_div1'));
        chart.draw(data, options);
    }
    function exportClassProgressGraph(schoolId,classId,report,tab){
        window.location.href = "classoverview/export/classprogressgraph?schoolId=" + schoolId + "&classId="+classId+"&report=" + report + "&tab="+tab;
    }
<?php } ?>
</script>

@stop