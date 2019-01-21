@extends(($layout == 'iframe') ? 'admin.layout._reports' : 'admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER -->
<?php if($layout != 'iframe') {?>
@include('admin.layout._page_header', ['title' => trans('admin/admin.manageaccount'), 'trait_1' => $trait['trait_1'], 'trait_1_link' => $trait['trait_1_link'],'trait_2' => $trait['trait_2']])
<?php } ?>
<style>
.select2-container{width:200px}
.table-scrollable{position:relative}
</style>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet for_reports_table">
            <div class="portlet-body">
                <ul class="nav nav-tabs">

                    <li @if($testtype == 'dashboard') class="active" @endif onclick="getreport(<?php echo $schoolId; ?>, 'dashboard', 'math')">
                         <a href="#tab_1_3" data-toggle="tab"><span>Dashboard</span></a>
                    </li>
                    <li @if($testtype == 'test') class="active" @endif onclick="getreport(<?php echo $schoolId; ?>, 'test', 'math')">
                         <a href="#tab_1_1" data-toggle="tab"><span>Test Summary</span></a>
                    </li>
                    <li @if($testtype == 'topic') class="active" @endif onclick="getreport(<?php echo $schoolId; ?>, 'topic', 'math')">
                         <a href="#tab_1_2" data-toggle="tab"><span>Topic Summary</span></a>
                    </li>

                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade  @if($testtype == 'dashboard') active in @endif" id="tab_1_3">
                        <div style="float:right;margin:15px 16px 0 0 ;"><a href="#" onclick="window.print()">Print Report</a></div>
                        <ul class="nav nav-tabs">
                            <li class="@if($testtype == 'dashboard' && $tab == 'eng')active @endif" onclick="getreport(<?php echo $schoolId; ?>, 'dashboard', 'eng')">
                                <a href="#tab_4_1" data-toggle="tab">
                                    <span>English</span>
                                </a></li>
                            <li class="@if($testtype == 'dashboard' && $tab == 'math')active @endif" onclick="getreport(<?php echo $schoolId; ?>, 'dashboard', 'math')">
                                <a href="#tab_4_2" data-toggle="tab">
                                    <span>Math</span>
                                </a>
                            </li>
                            <div style="float:right; padding-right:20px;padding-top:15px">
                                <?php 
                                
                                    $schAvgMarks = 0;
                                    if(!empty($dashboarddetail['arraySchoolOverViewTest'])){
                                        $counter = 0;
                                        $schMarks = 0;
                                        
                                        foreach($dashboarddetail['arraySchoolOverViewTest'] as $f=>$g){
                                            $schMarks = $schMarks+ $g['totalMark'];
                                            $counter++;
                                        }
                                        if($counter>0 && $schMarks>0){
                                            $schAvgMarks = $schMarks/$counter;
                                        }
                                    
                                    }
                                    $noOfTestCompleted = 0;
                                    if(!empty($dashboarddetail['graph'])){
                                       
                                        foreach($dashboarddetail['graph'] as $x=>$y){
                                            $countTest = count($y);
                                            if($countTest > $noOfTestCompleted){
                                                $noOfTestCompleted = $countTest;
                                            }
                                        }
                                    
                                    }
                                ?>
                                <?php if($schAvgMarks>0){?>
                                    <strong>School Average Score:&nbsp;</strong><?php echo number_format($schAvgMarks,'2','.',''); ?>%
                                <?php } ?>
                                <span>&nbsp;</span>
                                <?php if($noOfTestCompleted>0) {?>
                                <strong>No of Test Completed:&nbsp;</strong><?php echo $noOfTestCompleted; ?>
                                <?php } ?>
                            </div>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane  @if($testtype == 'dashboard' && $tab == 'eng')active @endif" id="tab_4_1">
                                <div class="table-scrollable">
                                    @if($testtype == 'dashboard' && $tab == 'eng')
                                    <div>
                                         <?php if (!empty($dashboarddetail['graph'])) { ?>
                                        <div class="portlet-title blue_heading">
                                            <div class="noExl caption">
                                                <input type="button" class="btn default" id="exportgraphclass" name="exportgraphclass" value="Export Class Test Graph" />
                                            </div>
                                        </div>
                                        <div id="chart_div" style="height:450px"></div>
                                        <div class="portlet-title blue_heading">
                                            <div class="noExl caption">
                                                <input type="button" class="btn default" id="exportgraph" name="exportgraph" value="Export Class Progress Graph" />
                                            </div>
                                        </div>
                                        <div>&nbsp</div><div>&nbsp</div>
                                       
                                        <div id="chart_div1" style="height:600px"></div>
                                        <?php } ?>
                                        <div style="transform: rotate(270deg); left: -44px;position: absolute;top: 755px;">Number of Pupils</div>
                                        <div>&nbsp</div><div>&nbsp</div>
                                        <div class="portlet box green">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <input type="button" class="btn default" name="export" value="Export To Excel" onclick="exportdata('schooldashboardeng','school_dashboard_eng')"/>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                
                                                    <table id="schooldashboardeng" class="table table-striped table-bordered table-hover dataTable no-footer">
                                                    <tr>
                                                    <td style="padding:0px;">
                                                   
                                                        <div class="portlet-title blue_heading">
                                                            <div class="noExl caption">
                                                                <strong>Pupil Progress +</strong>
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
                                                                                        <th class="text-center" rowspan="1" colspan="1">Student Name</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Average Test %
</th>

                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    if (!empty($dashboarddetail['mostImprovedStu'])) {
                                                                                        $i = 1;
                                                                                        arsort($dashboarddetail['mostImprovedStu']);
                                                                                        foreach ($dashboarddetail['mostImprovedStu'] as $key => $val) {
                                                                                        if($i>5){
                                                                                            break;
                                                                                        }
                                                                                            ?>
                                                                                            <tr class="noExl">
                                                                                                <td><?php echo $dashboarddetail['studentNameArray'][$key]; ?></td>
                                                                                                <td><?php echo round($val); ?>%</td>
                                                                                            </tr>
                                                                                            <?php $i++;
                                                                                        }
                                                                                    } else {
                                                                                        ?>
                                                                                        <tr class="noExl">
                                                                                            <td colspan="2">No record available</td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
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
                                                                                        <th class="text-center" rowspan="1" colspan="1">Student Name</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Timeliness</th>

                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    if (!empty($dashboarddetail['mostImprovedStuTime'])) {
                                                                                        $i=1;
                                                                                        arsort($dashboarddetail['mostImprovedStuTime']);
                                                                                        foreach ($dashboarddetail['mostImprovedStuTime'] as $key => $val) {
                                                                                        if($i>5){
                                                                                            break;
                                                                                        }
                                                                                            ?>
                                                                                            <tr class="noExl">
                                                                                                <td><?php echo $dashboarddetail['studentNameArray'][$key]; ?></td>
                                                                                                <td>
                                                                                                    <?php $tim =  number_format( $val/60 ,2,'.',''); 
                                                                                                        if($tim>30){
                                                                                                            $bar = 'red';
                                                                                                        }else if($tim>0){
                                                                                                            $bar = 'green';
                                                                                                        }else{
                                                                                                            $bar = 'zero';
                                                                                                        }
                                                                                                        
                                                                                                        if($tim > 99){
                                                                                                            $full = 'full';
                                                                                                        }else{
                                                                                                            $full = '';
                                                                                                        }
                                                                                                        if($tim > 100){
                                                                                                            $barper = 100;
                                                                                                        }else if($tim < 3){
                                                                                                            $barper = 3;
                                                                                                        }else{
                                                                                                            $barper = $tim;
                                                                                                        }
                                                                                                    ?>
                                                                                                    <div class="progress_bar">
                                                                                                       <div class="bar <?php echo $bar; ?> <?php echo $full; ?>" style="width:<?php echo $barper; ?>%">&nbsp;</div>
                                                                                                        <span><?php echo round($tim); ?></span>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php $i++;
                                                                                        }
                                                                                    } else {
                                                                                        ?>
                                                                                        <tr class="noExl">
                                                                                            <td colspan="2">No record available</td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
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
                                                                                        <th class="text-center" rowspan="1" colspan="1">Student Name</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Average Timeliness (minutes)</th>

                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    if (!empty($dashboarddetail['mostImprovedStuTime'])) {
                                                                                    $i = 1;
                                                                                    arsort($dashboarddetail['mostImprovedStuTime']);
                                                                                        foreach ($dashboarddetail['mostImprovedStuTime'] as $key => $val) {
                                                                                        if($i>5){
                                                                                            break;
                                                                                        }
                                                                                            ?>
                                                                                            <tr class="noExl">
                                                                                                <td><?php echo $dashboarddetail['studentNameArray'][$key]; ?></td>
                                                                                                <td>
                                                                                                    <?php $tim =  number_format( $val/60 ,2,'.',''); 
                                                                                                        if($tim>30){
                                                                                                            $bar = 'red';
                                                                                                        }else if($tim>0){
                                                                                                            $bar = 'green';
                                                                                                        }else{
                                                                                                            $bar = 'zero';
                                                                                                        }
                                                                                                        
                                                                                                        if($tim > 99){
                                                                                                            $full = 'full';
                                                                                                        }else{
                                                                                                            $full = '';
                                                                                                        }
                                                                                                        if($tim > 100){
                                                                                                            $barper = 100;
                                                                                                        }else if($tim < 3){
                                                                                                            $barper = 3;
                                                                                                        }else{
                                                                                                            $barper = $tim;
                                                                                                        }
                                                                                                    ?>
                                                                                                    <div class="progress_bar">
                                                                                                       <div class="bar <?php echo $bar; ?> <?php echo $full; ?>" style="width:<?php echo $barper; ?>%">&nbsp;</div>
                                                                                                        <span><?php echo round($tim); ?></span>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php $i++;
                                                                                        }
                                                                                    } else {
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td colspan="2">No record available</td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    
                                                    </td>
                                                    </tr>
                                                    <tr>
                                                    <td style="padding:0px;">
                                                   
                                                        <div class="portlet-title blue_heading">
                                                            <div class="noExl caption">
                                                                <strong>Highest Attaining Pupils</strong>
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
                                                                                    <tr class=" noExl heading" role="row">
                                                                                        <th class="text-center" rowspan="1" colspan="1">Student Name</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Average Test %
</th>

                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    if (!empty($dashboarddetail['studentMaxMarks'])) {
                                                                                        $i = 1;
                                                                                        arsort($dashboarddetail['studentMaxMarks']);
                                                                                        foreach ($dashboarddetail['studentMaxMarks'] as $key => $val) {
                                                                                            if ($i > 5) {
                                                                                                break;
                                                                                            }
                                                                                            ?>
                                                                                            <tr class="noExl">
                                                                                                <td><?php echo $dashboarddetail['studentNameArray'][$key]; ?></td>
                                                                                                <td><?php echo round($val); ?>%</td>
                                                                                            </tr>
                                                                                            <?php
                                                                                            $i++;
                                                                                        }
                                                                                    } else {
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td colspan="2">No record available</td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
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
                                                                            <strong>Usage</strong>
                                                                        </div>
                                                                    </div>
                                                                    <div class="portlet-body">
                                                                        <div class=" table-scrollable">
                                                                            <table class=" noExl table table-striped table-bordered table-hover dataTable no-footer">
                                                                                <thead>
                                                                                    <tr class="noExl heading" role="row">
                                                                                        <th class="text-center" rowspan="1" colspan="1">Student Name</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Timeliness (Minutes) </th>

                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    if (!empty($dashboarddetail['studentTestTime'])) {
                                                                                        $i = 1;
                                                                                           arsort($dashboarddetail['studentTestTime']); 
                                                                                        foreach ($dashboarddetail['studentTestTime'] as $key => $val) {
                                                                                            if ($i > 5) {
                                                                                                break;
                                                                                            }
                                                                                            ?>
                                                                                            <tr class="noExl">
                                                                                                <td><?php echo $dashboarddetail['studentNameArray'][$key]; ?></td>
                                                                                               <td>
                                                                                                    <?php $tim =  number_format( $val/60 ,2,'.',''); 
                                                                                                        if($tim>30){
                                                                                                            $bar = 'red';
                                                                                                        }else if($tim>0){
                                                                                                            $bar = 'green';
                                                                                                        }else{
                                                                                                            $bar = 'zero';
                                                                                                        }
                                                                                                        
                                                                                                        if($tim > 99){
                                                                                                            $full = 'full';
                                                                                                        }else{
                                                                                                            $full = '';
                                                                                                        }
                                                                                                        if($tim > 100){
                                                                                                            $barper = 100;
                                                                                                        }else if($tim < 3){
                                                                                                            $barper = 3;
                                                                                                        }else{
                                                                                                            $barper = $tim;
                                                                                                        }
                                                                                                    ?>
                                                                                                    <div class="progress_bar">
                                                                                                       <div class="bar <?php echo $bar; ?> <?php echo $full; ?>" style="width:<?php echo $barper; ?>%">&nbsp;</div>
                                                                                                        <span><?php echo round($tim); ?></span>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php
                                                                                            $i++;
                                                                                        }
                                                                                    } else {
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td colspan="2">No record available</td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
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
                                                                            <strong>Timeliness</strong>
                                                                        </div>
                                                                    </div>
                                                                    <div class="portlet-body">
                                                                        <div class="table-scrollable">
                                                                            <table class=" noExl table table-striped table-bordered table-hover dataTable no-footer">
                                                                                <thead>
                                                                                    <tr class="noExl heading" role="row">
                                                                                        <th class="text-center" rowspan="1" colspan="1">Student Name</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Average Timeliness (minutes)</th>

                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    if (!empty($dashboarddetail['studentTestTime'])) {
                                                                                        $i = 1;
                                                                                        arsort($dashboarddetail['studentTestTime']);
                                                                                        foreach ($dashboarddetail['studentTestTime'] as $key => $val) {
                                                                                            if ($i > 5) {
                                                                                                break;
                                                                                            }
                                                                                            ?>
                                                                                            <tr class="noExl">
                                                                                                <td><?php echo $dashboarddetail['studentNameArray'][$key]; ?></td>
                                                                                                <td>
                                                                                                    <?php $tim =  number_format( $val/60 ,2,'.',''); 
                                                                                                        if($tim>30){
                                                                                                            $bar = 'red';
                                                                                                        }else if($tim>0){
                                                                                                            $bar = 'green';
                                                                                                        }else{
                                                                                                            $bar = 'zero';
                                                                                                        }
                                                                                                        
                                                                                                        if($tim > 99){
                                                                                                            $full = 'full';
                                                                                                        }else{
                                                                                                            $full = '';
                                                                                                        }
                                                                                                        if($tim > 100){
                                                                                                            $barper = 100;
                                                                                                        }else if($tim < 3){
                                                                                                            $barper = 3;
                                                                                                        }else{
                                                                                                            $barper = $tim;
                                                                                                        }
                                                                                                    ?>
                                                                                                    <div class="progress_bar">
                                                                                                       <div class="bar <?php echo $bar; ?> <?php echo $full; ?>" style="width:<?php echo $barper; ?>%">&nbsp;</div>
                                                                                                        <span><?php echo round($tim); ?></span>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php
                                                                                            $i++;
                                                                                        }
                                                                                    } else {
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td colspan="2">No record available</td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    
                                                    </td>
                                                    </tr>
                                                    <tr>
                                                    <td style="padding:0px;">
                                                    
                                                        <div class="portlet-title blue_heading">
                                                            <div class="caption">
                                                                <strong>Lowest Attaining Pupils</strong>
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
                                                                                        <th class="text-center" rowspan="1" colspan="1">Pupil Name</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Average Test %
</th>

                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    if (!empty($dashboarddetail['studentMaxMarks'])) {
                                                                                        $i = 1;
                                                                                        asort($dashboarddetail['studentMaxMarks']);
                                                                                        foreach ($dashboarddetail['studentMaxMarks'] as $key => $val) {
                                                                                            if ($i > 5) {
                                                                                                break;
                                                                                            }
                                                                                            ?>
                                                                                            <tr class="noExl">
                                                                                                <td><?php echo $dashboarddetail['studentNameArray'][$key]; ?></td>
                                                                                                <td><?php echo round($val); ?>%</td>
                                                                                            </tr>
                                                                                            <?php
                                                                                            $i++;
                                                                                        }
                                                                                    } else {
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td colspan="2">No record available</td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
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
                                                                                        <th class="text-center" rowspan="1" colspan="1">Pupil Name</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Timeliness (Minutes)</th>

                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    if (!empty($dashboarddetail['studentTestTime'])) {
                                                                                        $i = 1;
                                                                                        asort($dashboarddetail['studentTestTime']);
                                                                                        foreach ($dashboarddetail['studentTestTime'] as $key => $val) {
                                                                                            if ($i > 5) {
                                                                                                break;
                                                                                            }
                                                                                            ?>
                                                                                            <tr class="noExl">
                                                                                                <td><?php echo $dashboarddetail['studentNameArray'][$key]; ?></td>
                                                                                               <td>
                                                                                                    <?php $tim =  number_format( $val/60 ,2,'.',''); 
                                                                                                        if($tim>30){
                                                                                                            $bar = 'red';
                                                                                                        }else if($tim>0){
                                                                                                            $bar = 'green';
                                                                                                        }else{
                                                                                                            $bar = 'zero';
                                                                                                        }
                                                                                                        
                                                                                                        if($tim > 99){
                                                                                                            $full = 'full';
                                                                                                        }else{
                                                                                                            $full = '';
                                                                                                        }
                                                                                                        if($tim > 100){
                                                                                                            $barper = 100;
                                                                                                        }else if($tim < 3){
                                                                                                            $barper = 3;
                                                                                                        }else{
                                                                                                            $barper = $tim;
                                                                                                        }
                                                                                                    ?>
                                                                                                    <div class="progress_bar">
                                                                                                       <div class="bar <?php echo $bar; ?> <?php echo $full; ?>" style="width:<?php echo $barper; ?>%">&nbsp;</div>
                                                                                                        <span><?php echo round($tim); ?></span>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php
                                                                                            $i++;
                                                                                        }
                                                                                    } else {
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td colspan="2">No record available</td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
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
                                                                           <strong> Timeliness</strong>
                                                                        </div>
                                                                    </div>
                                                                    <div class="portlet-body">
                                                                        <div class="table-scrollable">
                                                                            <table class=" noExl table table-striped table-bordered table-hover dataTable no-footer">
                                                                                <thead>
                                                                                    <tr class="noExl heading" role="row">
                                                                                        <th class="text-center" rowspan="1" colspan="1">Pupil Name</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Average Timeliness (minutes)</th>

                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    if (!empty($dashboarddetail['studentTestTime'])) {
                                                                                        $i = 1;
                                                                                        asort($dashboarddetail['studentTestTime']);
                                                                                        foreach ($dashboarddetail['studentTestTime'] as $key => $val) {
                                                                                            if ($i > 5) {
                                                                                                break;
                                                                                            }
                                                                                            ?>
                                                                                            <tr class="noExl">
                                                                                                <td><?php echo $dashboarddetail['studentNameArray'][$key]; ?></td>
                                                                                               <td>
                                                                                                    <?php $tim =  number_format( $val/60 ,2,'.',''); 
                                                                                                        if($tim>30){
                                                                                                            $bar = 'red';
                                                                                                        }else if($tim>0){
                                                                                                            $bar = 'green';
                                                                                                        }else{
                                                                                                            $bar = 'zero';
                                                                                                        }
                                                                                                        
                                                                                                        if($tim > 99){
                                                                                                            $full = 'full';
                                                                                                        }else{
                                                                                                            $full = '';
                                                                                                        }
                                                                                                        if($tim > 100){
                                                                                                            $barper = 100;
                                                                                                        }else if($tim < 3){
                                                                                                            $barper = 3;
                                                                                                        }else{
                                                                                                            $barper = $tim;
                                                                                                        }
                                                                                                    ?>
                                                                                                    <div class="progress_bar">
                                                                                                       <div class="bar <?php echo $bar; ?> <?php echo $full; ?>" style="width:<?php echo $barper; ?>%">&nbsp;</div>
                                                                                                        <span><?php echo round($tim); ?></span>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php
                                                                                            $i++;
                                                                                        }
                                                                                    } else {
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td colspan="2">No record available</td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
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
                                                
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane  @if($testtype == 'dashboard' && $tab == 'math')active @endif" id="tab_4_2">
                                <div class="table-scrollable">
                                    @if($testtype == 'dashboard' && $tab == 'math')
                                    <div>
                                        <?php if (!empty($dashboarddetail['graph'])) { ?>
                                        <div class="portlet-title blue_heading">
                                            <div class="noExl caption">
                                                <input type="button" class="btn default" id="exportgraphclass" name="exportgraphclass" value="Export Class Test Graph" />
                                            </div>
                                        </div>
                                        <div id="chart_div" style="height:450px"></div>
                                        <div style="transform: rotate(270deg); left: -44px;position: absolute;top: 755px;">Number of Pupils</div>
                                        <div class="portlet-title blue_heading">
                                            <div class="noExl caption">
                                                <input type="button" class="btn default" id="exportgraph" name="exportgraph" value="Export Class Progress Graph" />
                                            </div>
                                        </div>
                                        <div>&nbsp</div><div>&nbsp</div>
                                        
                                            <div id="chart_div1" style="height:450px"></div>
                                        <?php }?>
                                        <div>&nbsp</div><div>&nbsp</div>
                                        <div class="portlet box green">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <input type="button" class="btn default" name="export" value="Export To Excel" onclick="exportdata('schooldashboardmath','school_dashboard_math')"/>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                
                                                    <table id="schooldashboardmath" class="table table-striped table-bordered table-hover dataTable no-footer">
                                                    <tr>
                                                    <td style="padding:0px;">
                                                   
                                                        <div class="portlet-title blue_heading">
                                                            <div class="noExl caption">
                                                                <strong>Pupil Progress +</strong>
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
                                                                                        <th class="text-center" rowspan="1" colspan="1">Pupil Name</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Average Test %
</th>

                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    if (!empty($dashboarddetail['mostImprovedStu'])) {
                                                                                        $i = 1;
                                                                                        arsort($dashboarddetail['mostImprovedStu']);
                                                                                        foreach ($dashboarddetail['mostImprovedStu'] as $key => $val) {
                                                                                        if($i>5){
                                                                                            break;
                                                                                        }
                                                                                            ?>
                                                                                            <tr class="noExl">
                                                                                                <td><?php echo $dashboarddetail['studentNameArray'][$key]; ?></td>
                                                                                                <td><?php echo round($val); ?>%</td>
                                                                                            </tr>
                                                                                            <?php $i++;
                                                                                        }
                                                                                    } else {
                                                                                        ?>
                                                                                        <tr class="noExl">
                                                                                            <td colspan="2">No record available</td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
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
                                                                                        <th class="text-center" rowspan="1" colspan="1">Pupil Name</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Timeliness (Minutes)</th>

                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    if (!empty($dashboarddetail['mostImprovedStuTime'])) {
                                                                                        $i=1;
                                                                                        arsort($dashboarddetail['mostImprovedStuTime']);
                                                                                        foreach ($dashboarddetail['mostImprovedStuTime'] as $key => $val) {
                                                                                        if($i>5){
                                                                                            break;
                                                                                        }
                                                                                            ?>
                                                                                            <tr class="noExl">
                                                                                                <td><?php echo $dashboarddetail['studentNameArray'][$key]; ?></td>
                                                                                                <td>
                                                                                                    <?php $tim =  number_format( $val/60 ,2,'.',''); 
                                                                                                        if($tim>30){
                                                                                                            $bar = 'red';
                                                                                                        }else if($tim>0){
                                                                                                            $bar = 'green';
                                                                                                        }else{
                                                                                                            $bar = 'zero';
                                                                                                        }
                                                                                                        
                                                                                                        if($tim > 99){
                                                                                                            $full = 'full';
                                                                                                        }else{
                                                                                                            $full = '';
                                                                                                        }
                                                                                                        if($tim > 100){
                                                                                                            $barper = 100;
                                                                                                        }else if($tim < 3){
                                                                                                            $barper = 3;
                                                                                                        }else{
                                                                                                            $barper = $tim;
                                                                                                        }
                                                                                                    ?>
                                                                                                    <div class="progress_bar">
                                                                                                       <div class="bar <?php echo $bar; ?> <?php echo $full; ?>" style="width:<?php echo $barper; ?>%">&nbsp;</div>
                                                                                                        <span><?php echo round($tim); ?></span>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php $i++;
                                                                                        }
                                                                                    } else {
                                                                                        ?>
                                                                                        <tr class="noExl">
                                                                                            <td colspan="2">No record available</td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
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
                                                                                        <th class="text-center" rowspan="1" colspan="1">Pupil Name</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Average Timeliness (minutes)</th>

                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    if (!empty($dashboarddetail['mostImprovedStuTime'])) {
                                                                                    $i = 1;
                                                                                    arsort($dashboarddetail['mostImprovedStuTime']);
                                                                                        foreach ($dashboarddetail['mostImprovedStuTime'] as $key => $val) {
                                                                                        if($i>5){
                                                                                            break;
                                                                                        }
                                                                                            ?>
                                                                                            <tr class="noExl">
                                                                                                <td><?php echo $dashboarddetail['studentNameArray'][$key]; ?></td>
                                                                                                <td>
                                                                                                    <?php $tim =  number_format( $val/60 ,2,'.',''); 
                                                                                                        if($tim>30){
                                                                                                            $bar = 'red';
                                                                                                        }else if($tim>0){
                                                                                                            $bar = 'green';
                                                                                                        }else{
                                                                                                            $bar = 'zero';
                                                                                                        }
                                                                                                        
                                                                                                        if($tim > 99){
                                                                                                            $full = 'full';
                                                                                                        }else{
                                                                                                            $full = '';
                                                                                                        }
                                                                                                        if($tim > 100){
                                                                                                            $barper = 100;
                                                                                                        }else if($tim < 3){
                                                                                                            $barper = 3;
                                                                                                        }else{
                                                                                                            $barper = $tim;
                                                                                                        }
                                                                                                    ?>
                                                                                                    <div class="progress_bar">
                                                                                                       <div class="bar <?php echo $bar; ?> <?php echo $full; ?>" style="width:<?php echo $barper; ?>%">&nbsp;</div>
                                                                                                        <span><?php echo round($tim); ?></span>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php $i++;
                                                                                        }
                                                                                    } else {
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td colspan="2">No record available</td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    
                                                    </td>
                                                    </tr>
                                                    <tr>
                                                    <td style="padding:0px;">
                                                   
                                                        <div class="portlet-title blue_heading">
                                                            <div class="noExl caption">
                                                                <strong>Highest Attaining Pupils</strong>
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
                                                                                    <tr class=" noExl heading" role="row">
                                                                                        <th class="text-center" rowspan="1" colspan="1">Pupil Name</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Average Test %
</th>

                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    if (!empty($dashboarddetail['studentMaxMarks'])) {
                                                                                        $i = 1;
                                                                                        arsort($dashboarddetail['studentMaxMarks']);
                                                                                        foreach ($dashboarddetail['studentMaxMarks'] as $key => $val) {
                                                                                            if ($i > 5) {
                                                                                                break;
                                                                                            }
                                                                                            ?>
                                                                                            <tr class="noExl">
                                                                                                <td><?php echo $dashboarddetail['studentNameArray'][$key]; ?></td>
                                                                                                <td><?php echo round($val); ?>%</td>
                                                                                            </tr>
                                                                                            <?php
                                                                                            $i++;
                                                                                        }
                                                                                    } else {
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td colspan="2">No record available</td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
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
                                                                            <strong>Usage</strong>
                                                                        </div>
                                                                    </div>
                                                                    <div class="portlet-body">
                                                                        <div class=" table-scrollable">
                                                                            <table class=" noExl table table-striped table-bordered table-hover dataTable no-footer">
                                                                                <thead>
                                                                                    <tr class="noExl heading" role="row">
                                                                                        <th class="text-center" rowspan="1" colspan="1">Pupil Name</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Timeliness (Minutes)</th>

                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    if (!empty($dashboarddetail['studentTestTime'])) {
                                                                                        $i = 1;
                                                                                           arsort($dashboarddetail['studentTestTime']); 
                                                                                        foreach ($dashboarddetail['studentTestTime'] as $key => $val) {
                                                                                            if ($i > 5) {
                                                                                                break;
                                                                                            }
                                                                                            ?>
                                                                                            <tr class="noExl">
                                                                                                <td><?php echo $dashboarddetail['studentNameArray'][$key]; ?></td>
                                                                                                <td>
                                                                                                    <?php $tim =  number_format( $val/60 ,2,'.',''); 
                                                                                                        if($tim>30){
                                                                                                            $bar = 'red';
                                                                                                        }else if($tim>0){
                                                                                                            $bar = 'green';
                                                                                                        }else{
                                                                                                            $bar = 'zero';
                                                                                                        }
                                                                                                        
                                                                                                        if($tim > 99){
                                                                                                            $full = 'full';
                                                                                                        }else{
                                                                                                            $full = '';
                                                                                                        }
                                                                                                        if($tim > 100){
                                                                                                            $barper = 100;
                                                                                                        }else if($tim < 3){
                                                                                                            $barper = 3;
                                                                                                        }else{
                                                                                                            $barper = $tim;
                                                                                                        }
                                                                                                    ?>
                                                                                                    <div class="progress_bar">
                                                                                                       <div class="bar <?php echo $bar; ?> <?php echo $full; ?>" style="width:<?php echo $barper; ?>%">&nbsp;</div>
                                                                                                        <span><?php echo round($tim); ?></span>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php
                                                                                            $i++;
                                                                                        }
                                                                                    } else {
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td colspan="2">No record available</td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
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
                                                                            <strong>Timeliness</strong>
                                                                        </div>
                                                                    </div>
                                                                    <div class="portlet-body">
                                                                        <div class="table-scrollable">
                                                                            <table class=" noExl table table-striped table-bordered table-hover dataTable no-footer">
                                                                                <thead>
                                                                                    <tr class="noExl heading" role="row">
                                                                                        <th class="text-center" rowspan="1" colspan="1">Pupil Name</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Average Timeliness (minutes)</th>

                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    if (!empty($dashboarddetail['studentTestTime'])) {
                                                                                        $i = 1;
                                                                                        arsort($dashboarddetail['studentTestTime']);
                                                                                        foreach ($dashboarddetail['studentTestTime'] as $key => $val) {
                                                                                            if ($i > 5) {
                                                                                                break;
                                                                                            }
                                                                                            ?>
                                                                                            <tr class="noExl">
                                                                                                <td><?php echo $dashboarddetail['studentNameArray'][$key]; ?></td>
                                                                                                <td>
                                                                                                    <?php $tim =  number_format( $val/60 ,2,'.',''); 
                                                                                                        if($tim>30){
                                                                                                            $bar = 'red';
                                                                                                        }else if($tim>0){
                                                                                                            $bar = 'green';
                                                                                                        }else{
                                                                                                            $bar = 'zero';
                                                                                                        }
                                                                                                        
                                                                                                        if($tim > 99){
                                                                                                            $full = 'full';
                                                                                                        }else{
                                                                                                            $full = '';
                                                                                                        }
                                                                                                        if($tim > 100){
                                                                                                            $barper = 100;
                                                                                                        }else if($tim < 3){
                                                                                                            $barper = 3;
                                                                                                        }else{
                                                                                                            $barper = $tim;
                                                                                                        }
                                                                                                    ?>
                                                                                                    <div class="progress_bar">
                                                                                                       <div class="bar <?php echo $bar; ?> <?php echo $full; ?>" style="width:<?php echo $barper; ?>%">&nbsp;</div>
                                                                                                        <span><?php echo round($tim); ?></span>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php
                                                                                            $i++;
                                                                                        }
                                                                                    } else {
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td colspan="2">No record available</td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    
                                                    </td>
                                                    </tr>
                                                    <tr>
                                                    <td style="padding:0px;">
                                                    
                                                        <div class="portlet-title blue_heading">
                                                            <div class="caption">
                                                                <strong>Lowest Attaining Pupils</strong>
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
                                                                                        <th class="text-center" rowspan="1" colspan="1">Pupil Name</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Average Test %
</th>

                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    if (!empty($dashboarddetail['studentMaxMarks'])) {
                                                                                        $i = 1;
                                                                                        asort($dashboarddetail['studentMaxMarks']);
                                                                                        foreach ($dashboarddetail['studentMaxMarks'] as $key => $val) {
                                                                                            if ($i > 5) {
                                                                                                break;
                                                                                            }
                                                                                            ?>
                                                                                            <tr class="noExl">
                                                                                                <td><?php echo $dashboarddetail['studentNameArray'][$key]; ?></td>
                                                                                                <td><?php echo round($val); ?>%</td>
                                                                                            </tr>
                                                                                            <?php
                                                                                            $i++;
                                                                                        }
                                                                                    } else {
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td colspan="2">No record available</td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
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
                                                                                        <th class="text-center" rowspan="1" colspan="1">Pupil Name</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Timeliness (Minutes)</th>

                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    if (!empty($dashboarddetail['studentTestTime'])) {
                                                                                        $i = 1;
                                                                                        asort($dashboarddetail['studentTestTime']);
                                                                                        foreach ($dashboarddetail['studentTestTime'] as $key => $val) {
                                                                                            if ($i > 5) {
                                                                                                break;
                                                                                            }
                                                                                            ?>
                                                                                            <tr class="noExl">
                                                                                                <td><?php echo $dashboarddetail['studentNameArray'][$key]; ?></td>
                                                                                               <td>
                                                                                                    <?php $tim =  number_format( $val/60 ,2,'.',''); 
                                                                                                        if($tim>30){
                                                                                                            $bar = 'red';
                                                                                                        }else if($tim>0){
                                                                                                            $bar = 'green';
                                                                                                        }else{
                                                                                                            $bar = 'zero';
                                                                                                        }
                                                                                                        
                                                                                                        if($tim > 99){
                                                                                                            $full = 'full';
                                                                                                        }else{
                                                                                                            $full = '';
                                                                                                        }
                                                                                                        if($tim > 100){
                                                                                                            $barper = 100;
                                                                                                        }else if($tim < 3){
                                                                                                            $barper = 3;
                                                                                                        }else{
                                                                                                            $barper = $tim;
                                                                                                        }
                                                                                                    ?>
                                                                                                    <div class="progress_bar">
                                                                                                       <div class="bar <?php echo $bar; ?> <?php echo $full; ?>" style="width:<?php echo $barper; ?>%">&nbsp;</div>
                                                                                                        <span><?php echo round($tim); ?></span>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php
                                                                                            $i++;
                                                                                        }
                                                                                    } else {
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td colspan="2">No record available</td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
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
                                                                           <strong> Timeliness</strong>
                                                                        </div>
                                                                    </div>
                                                                    <div class="portlet-body">
                                                                        <div class="table-scrollable">
                                                                            <table class=" noExl table table-striped table-bordered table-hover dataTable no-footer">
                                                                                <thead>
                                                                                    <tr class="noExl heading" role="row">
                                                                                        <th class="text-center" rowspan="1" colspan="1">Pupil Name</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Average Timeliness (minutes)</th>

                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    if (!empty($dashboarddetail['studentTestTime'])) {
                                                                                        $i = 1;
                                                                                        asort($dashboarddetail['studentTestTime']);
                                                                                        foreach ($dashboarddetail['studentTestTime'] as $key => $val) {
                                                                                            if ($i > 5) {
                                                                                                break;
                                                                                            }
                                                                                            ?>
                                                                                            <tr class="noExl">
                                                                                                <td><?php echo $dashboarddetail['studentNameArray'][$key]; ?></td>
                                                                                                <td>
                                                                                                    <?php $tim =  number_format( $val/60 ,2,'.',''); 
                                                                                                        if($tim>30){
                                                                                                            $bar = 'red';
                                                                                                        }else if($tim>0){
                                                                                                            $bar = 'green';
                                                                                                        }else{
                                                                                                            $bar = 'zero';
                                                                                                        }
                                                                                                        
                                                                                                        if($tim > 99){
                                                                                                            $full = 'full';
                                                                                                        }else{
                                                                                                            $full = '';
                                                                                                        }
                                                                                                        if($tim > 100){
                                                                                                            $barper = 100;
                                                                                                        }else if($tim < 3){
                                                                                                            $barper = 3;
                                                                                                        }else{
                                                                                                            $barper = $tim;
                                                                                                        }
                                                                                                    ?>
                                                                                                    <div class="progress_bar">
                                                                                                       <div class="bar <?php echo $bar; ?> <?php echo $full; ?>" style="width:<?php echo $barper; ?>%">&nbsp;</div>
                                                                                                        <span><?php echo round($tim); ?></span>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php
                                                                                            $i++;
                                                                                        }
                                                                                    } else {
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td colspan="2">No record available</td>
                                                                                        </tr>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
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
                                                
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade  @if($testtype == 'test') active in @endif" id="tab_1_1">
                        <div style="float:right;margin:15px 16px 0 0 ;"><a href="#" onclick="window.print()">Print Report</a></div>
                        <ul class="nav nav-tabs">
                            <li class="@if($testtype == 'test' && $tab == 'eng')active @endif"  onclick="getreport(<?php echo $schoolId; ?>, 'test', 'eng')">
                                <a href="#tab_2_1" data-toggle="tab">
                                    <span>English</span>
                                </a></li>
                            <li class="@if($testtype == 'test' && $tab == 'math')active @endif"  onclick="getreport(<?php echo $schoolId; ?>, 'test', 'math')">
                                <a href="#tab_2_2" data-toggle="tab">
                                    <span>Math</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane  @if($testtype == 'test' && $tab == 'eng')active @endif" id="tab_2_1">
                                <div class="table-scrollable">
                                    @if($testtype == 'test' && $tab == 'eng')
                                    <div class="heder_filer">
                                        <span><strong>Set Name:</strong></span>
                                        <select name="set" id="setId" class="select2me" id="set">
                                            <option value=""> Select Set</option>
                                           
                                        <?php
                                            if(!empty($dashboarddetail['satArray'])){
                                                foreach($dashboarddetail['satArray'] as $setKey=>$setValue){
                                                $selected = $setId == $setValue->id ? 'selected="selected"':'';
                                        ?>    
                                                    <option value="<?php echo $setValue->id; ?>" <?php echo $selected?>><?php echo $setValue->set_name; ?></option>
                                        <?php             
                                                
                                                }
                                            }

                                        ?>
                                        </select> 
                                        <span style="padding-left:25px"><strong>Paper:</strong></span>
                                        <select name="paper" id ="paperId" class="select2me">
                                            <option value="">All papers</option> 
                                            <option value="4" <?php echo $paperId == 4 ? 'selected="selected"':''?>>Paper 1</option> 
                                            <option value="5" <?php echo $paperId == 5 ? 'selected="selected"':''?>>Paper 2</option> 
                                        </select>
                                        <span style="padding-left:25px">
                                            <input class="btn green" type ="button" name="submit" id="setfilter" value="submit" onclick="setdata(<?php echo $schoolId; ?>, 'test', 'eng')">
                                        </span>
                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="portlet box red">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <input type="button" class="btn default" name="export" value="Export To Excel" onclick="exportdata('schooltesteng','school_test_eng')"/>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="table-scrollable">
                                                        <table id="schooltesteng" class="table table-striped table-bordered table-hover dataTable no-footer">
                                                            <thead>
                                                                <tr class="heading" role="row">
                                                                    <th class="text-center" rowspan="1" colspan="1">Class Name</th>
                                                                    <th class="text-center" rowspan="1" colspan="1">Average Score</th>
                                                                    <th class="text-center" rowspan="1" colspan="1">Average Baseline</th>
                                                                    <th class="text-center" rowspan="1" colspan="1">Timeliness (minutes)</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php 
                                                                if (!empty($dashboarddetail['topicdetail'])) {
                                                                foreach ($dashboarddetail['topicdetail'] as $key => $val) {
                                                                    ?>      
                                                                    <tr class="heading" role="row">
                                                                        <td class="text-center" rowspan="1" colspan="1"><?php echo trim($val->class_name); ?></td>
                                                                        <?php
                                                                                $avg = 'NA';
                                                                                $tim = 'NA';
                                                                                $avg_total_time = '';

                                                                                $avg_total_time = ($val->p1_max_time+$val->p2_max_time)/2;
                                                                                $avg_total_time = round(($avg_total_time/60));
                                                                                $baseline = 'NA';
                                                                                // $atm = $val->ks2_english_baseline_value;
                                                                                //echo $avg_total_time = round(($val->paper_total_time/$val->attempt_count)/60);
                                                                                
                                                                                if(!empty($val->test_attempt_avg_time))
                                                                                    $tim =  $val->test_attempt_avg_time/60;

                                                                                if(!empty($val->test_attempt_avg_marks))
                                                                                    $avg = round($val->test_attempt_avg_marks).'%';
                                                                                
                                                                                if(!empty($dashboarddetail['paper']))
                                                                                {
                                                                                    if($dashboarddetail['paper'] == 4) {
                                                                                        if(!empty($val->p1_avg))
                                                                                            $avg = round($val->p1_avg).'%';
                                                                                        
                                                                                        //if(!empty($val->attempt_count)) {
                                                                                            //$tim = ($val->p1_avg_time/$val->attempt_count)/60;
                                                                                            $tim = $val->p1_avg_time/60;
                                                                                            $avg_total_time = ($val->p1_max_time);
                                                                                            $avg_total_time = round($avg_total_time/60);
                                                                                       // }
                                                                                    }
                                                                                    if($dashboarddetail['paper'] == 5) {
                                                                                        if(!empty($val->p2_avg))
                                                                                            $avg = round($val->p2_avg).'%';
                                                                                        //if(!empty($val->attempt_count)) {
                                                                                            
                                                                                            $tim = $val->p2_avg_time/60;
                                                                                            $avg_total_time = ($val->p2_max_time);
                                                                                            $avg_total_time = round($avg_total_time/60);
                                                                                       // }
                                                                                    }
                                                                                }
                                                                                
                                                                        ?>
                                                                        <td class="text-center" rowspan="1" colspan="1"><?php  echo $avg; ?></td>
                                                                        <td class="text-center" rowspan="1" colspan="1"><?php //echo round($baseline); ?></td>
                                                                        <td class="text-center" rowspan="1" colspan="1"><?php if($tim !='NA'){
                                                                                if(round($tim) > round($avg_total_time)){
                                                                                    $bar = "red";
                                                                                }else{
                                                                                    $bar = "green";
                                                                                }
                                                                                $grp = ($tim>$avg_total_time)?100:($tim/$avg_total_time)*100;
                                                                                $full = "";
                                                                                if($grp >99 ){
                                                                                    $full = 'full';
                                                                                }
                                                                                if($grp<3){
                                                                                    $grp = 3;
                                                                                }
                                                                                echo '<div class="progress_bar"><div class="bar '.$bar.' '.$full.'" style="width:'.$grp.'%">&nbsp;</div><span>'.round($tim).'</span></div>';
                                                                            }else{
                                                                                echo $tim;
                                                                            }     ?></td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            } else {
                                                                    ?>
                                                                    <tr>
                                                                        <td colspan="2">No record available</td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>	
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane  @if($testtype == 'test' && $tab == 'math')active @endif" id="tab_2_2">
                            <div class="table-scrollable">
                                @if($testtype == 'test' && $tab == 'math')
                                <div class="heder_filer">
                                        <span><strong>Set Name:</strong></span>
                                        <select name="set" id="setId" class="select2me">
                                            <option value=""> Select Set</option>
                                        
                                        <?php
                                            if(!empty($dashboarddetail['satArray'])){
                                                foreach($dashboarddetail['satArray'] as $setKey=>$setValue){
                                                $selected = $setId == $setValue->id ? 'selected="selected"':'';
                                        ?>    
                                                    <option value="<?php echo $setValue->id; ?>" <?php echo $selected ?>><?php echo $setValue->set_name; ?></option>
                                        <?php             
                                                
                                                }
                                            }

                                        ?>
                                        </select>    
                                        <span style="padding-left:25px"><strong>Paper:</strong></span>
                                        <select name="paper" id ="paperId" class="select2me">
                                            <option value="">All papers</option> 
                                            <option value="1" <?php echo $paperId == 1 ? 'selected="selected"':''?>>Paper 1</option> 
                                            <option value="2" <?php echo $paperId == 2 ? 'selected="selected"':''?>>Paper 2</option> 
                                            <option value="3" <?php echo $paperId == 3 ? 'selected="selected"':''?>>Paper 3</option> 
                                        </select>
                                        <span style="padding-left:25px">
                                            <input class="btn green" type ="button" name="submit" id="setfilter" value="submit" onclick="setdata(<?php echo $schoolId; ?>, 'test', 'math')">
                                        </span>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="portlet box red">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <input type="button" class="btn default" name="export" value="Export To Excel" onclick="exportdata('schooltestmath','school_test_math')"/>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="table-scrollable">
                                                    <table id="schooltestmath" class="table table-striped table-bordered table-hover dataTable no-footer">
                                                        <thead>
                                                            <tr class="heading" role="row">
                                                                <th class="text-center" rowspan="1" colspan="1">Class Name</th>
                                                                <th class="text-center" rowspan="1" colspan="1">Average Score</th>
                                                                <th class="text-center" rowspan="1" colspan="1">Average Baseline</th>
                                                                <th class="text-center" rowspan="1" colspan="1">Timeliness (minutes)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                                <?php 
                                                                if (!empty($dashboarddetail['topicdetail'])) {
                                                                foreach ($dashboarddetail['topicdetail'] as $key => $val) {
                                                                    ?>      
                                                                    <tr class="heading" role="row">
                                                                        <td class="text-center" rowspan="1" colspan="1"><?php echo trim($val->class_name); ?></td>
                                                                        <?php
                                                                                $avg = 'NA';
                                                                                $tim = 'NA';
                                                                                $avg_total_time = '';
                                                                                $baseline = 'NA';
                                                                                // $atm = $val->ks2_english_baseline_value;
                                                                                //echo $avg_total_time = round(($val->paper_total_time/$val->attempt_count)/60);
                                                                                if(!empty($dashboarddetail['paper']))
                                                                                {
                                                                                    if($dashboarddetail['paper'] == 1) {
                                                                                        if(!empty($val->p1_avg))
                                                                                            $avg = round($val->p1_avg).'%';
                                                                                            $tim = $val->p1_avg_time/60;
                                                                                            $avg_total_time = ($val->p1_max_time);
                                                                                            $avg_total_time = round($avg_total_time/60);
                                                                                    }
                                                                                    if($dashboarddetail['paper'] == 2) {
                                                                                        if(!empty($val->p2_avg))
                                                                                            $avg = round($val->p2_avg).'%';
                                                                                            $tim = $val->p2_avg_time/60;
                                                                                            $avg_total_time = ($val->p2_max_time);
                                                                                            $avg_total_time = round($avg_total_time/60);
                                                                                    }
                                                                                    if($dashboarddetail['paper'] == 3) {
                                                                                        if(!empty($val->p3_avg))
                                                                                            $avg = round($val->p3_avg).'%';
                                                                                            $tim = $val->p3_avg_time/60;
                                                                                            $avg_total_time = ($val->p3_max_time);
                                                                                            $avg_total_time = round($avg_total_time/60);
                                                                                    }
                                                                                }
                                                                                else{
                                                                                    $avg_total_time = ($val->p1_max_time+$val->p2_max_time+$val->p3_max_time)/3;
                                                                                    $avg_total_time = round(($avg_total_time/60));
                                                                                    if(!empty($val->test_attempt_avg_time))
                                                                                        $tim =  $val->test_attempt_avg_time/60;

                                                                                    if(!empty($val->test_attempt_avg_marks))
                                                                                        $avg = round($val->test_attempt_avg_marks).'%';                                                                                    
                                                                                }
                                                                                
                                                                        ?>
                                                                        <td class="text-center" rowspan="1" colspan="1"><?php  echo $avg; ?></td>
                                                                        <td class="text-center" rowspan="1" colspan="1"><?php //echo round($baseline); ?></td>
                                                                        <td class="text-center" rowspan="1" colspan="1"><?php if($tim !='NA'){
                                                                                if(round($tim) > round($avg_total_time)){
                                                                                    $bar = "red";
                                                                                }else{
                                                                                    $bar = "green";
                                                                                }
                                                                                $grp = ($tim>$avg_total_time)?100:($tim/$avg_total_time)*100;
                                                                                $full = "";
                                                                                if($grp >99 ){
                                                                                    $full = 'full';
                                                                                }
                                                                                if($grp<3){
                                                                                    $grp = 3;
                                                                                }
                                                                                echo '<div class="progress_bar"><div class="bar '.$bar.' '.$full.'" style="width:'.$grp.'%">&nbsp;</div><span>'.round($tim).'</span></div>';
                                                                            }else{
                                                                                echo $tim;
                                                                            }     ?></td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            } else {
                                                                    ?>
                                                                    <tr>
                                                                        <td colspan="2">No record available</td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                ?>
                                                        </tbody>
                                                    </table>	
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @endif
                            </div>

                        </div>
                    </div>
                </div>
                <div class="tab-pane fade @if($testtype == 'topic')active in @endif" id="tab_1_2">
                    <div style="float:right;margin:15px 16px 0 0 ;"><a href="#" onclick="window.print()">Print Report</a></div>
                    <ul class="nav nav-tabs">
                        <li class="@if($testtype == 'topic' && $tab == 'eng')active @endif" onclick="getreport(<?php echo $schoolId; ?>, 'topic', 'eng')">
                            <a href="#tab_3_1" data-toggle="tab">
                                <span>English</span>
                            </a></li>
                        <li class="@if($testtype == 'topic' && $tab == 'math')active @endif"  onclick="getreport(<?php echo $schoolId; ?>, 'topic', 'math')"><a href="#tab_3_2" data-toggle="tab" >
                                <span>Math</span>
                            </a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane  @if($testtype == 'topic' && $tab == 'eng')active @endif" id="tab_3_1">
                            <div class="table-scrollable">
                                @if($testtype == 'topic' && $tab == 'eng')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="dataTables_length select_box_row heder_filer" style="margin:10px;">
                                            <label style="margin-right:20px;">
                                                <select name="sta" class="select2me" id="sta" class="select2me" onchange="getsta(<?php echo $schoolId; ?>, 'topic', 'eng',this.value,<?php if(!empty($substrand)){ echo $substrand; }else{ echo 0; } ?>)">
                                                    <option value="">Select Strands</option>
                                                    <?php
                                                    if (!empty($dashboarddetailTopic['strands'])) {
                                                        foreach ($dashboarddetailTopic['strands'] as $key => $val) {
                                                            ?>    
                                                            <option value="<?php echo $val->id ?>" <?php if ($val->id == $dashboarddetailTopic['strandId']) { ?> selected="selected"<?php } ?>><?php echo $val->strand ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </label>
                                            <label><select name="substa" id="substa" class="select2me" onchange="getsubstr(<?php echo $schoolId; ?>, 'topic', '<?php echo $tab; ?>',this.value,<?php if(!empty($strand)){ echo $strand; }else{ echo 0; } ?>)">
                                                    <option value="">Select Substrand</option>
                                                    <?php
                                                    if (!empty($dashboarddetailTopic['substrands'])) {
                                                        foreach ($dashboarddetailTopic['substrands'] as $key => $val) {
                                                            ?>    
                                                            <option value="<?php echo $val->id ?>" <?php if ($val->id == $dashboarddetailTopic['substrandId']) { ?> selected="selected"<?php } ?>><?php echo $val->strand ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </label>
                                            <a href="/adminreport/schooloverview?id=<?php echo $schoolId; ?>&report=topic&tab=eng&layout=<?php echo $layout ?>" style="margin-left:15px;"> <input type="button" name="reset" id="reset" value="Reset Filter" class="btn btn-primary grey"></a>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="portlet box red">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                   <input type="button" class="btn default" name="export" value="Export To Excel" onclick="exportdata('schooltopiceng','school_topic_eng')"/>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="table-scrollable">
                                                    <table id="schooltopiceng"class="table table-striped table-bordered table-hover dataTable no-footer">
                                                        <thead>
                                                            <tr class="heading" role="row">
                                                                <th class="text-center" rowspan="1" colspan="1">Class Name</th>
                                                                <th class="text-center" rowspan="1" colspan="1">Average Score</th>
                                                                <th class="text-center" rowspan="1" colspan="1">Attempted</th>
                                                                <th class="text-center" rowspan="1" colspan="1">Timeliness (minutes)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            if (!empty($dashboarddetailTopic['topicdetail'])) {
                                                                foreach ($dashboarddetailTopic['topicdetail'] as $key => $val) {
                                                                    ?>      
                                                                    <tr class="heading" role="row">
                                                                        <td class="text-center" rowspan="1" colspan="1"><?php echo $val->class_name; ?></td>
                                                                        <?php
                                                                                $avg = 'NA';
                                                                                $tim = 'NA';
                                                                                
                                                                                $avg_total_time = round($val->avg_total_time/60);
                                                                                
                                                                                $atm = '<span class="glyphicon glyphicon-remove" style="color:red;"></span>';
                                                                                if(!empty($val->avg_marks))
                                                                                    $atm = '<span class="glyphicon glyphicon-ok" style="color:green;"></span>';
                                                                                
                                                                                if(!empty($val->total_record_cnt)) {
                                                                                    $tim =  round($val->avg_time/$val->total_record_cnt);
                                                                                    $tim = $tim/60;
                                                                                }
                                                                                if(!empty($val->total_record_cnt))
                                                                                    $avg = round($val->avg_marks/$val->total_record_cnt).' %';
                                                                        ?>
                                                                        <td class="text-center" rowspan="1" colspan="1"><?php  echo $avg; ?></td>
                                                                        <td class="text-center" rowspan="1" colspan="1"><?php echo $atm; ?></td>
                                                                        <td class="text-center" rowspan="1" colspan="1"><?php if($tim !='NA'){
                                                                                if(round($tim) > round($avg_total_time)){
                                                                                    $bar = "red";
                                                                                }else{
                                                                                    $bar = "green";
                                                                                }
                                                                                $grp = ($tim>$avg_total_time)?100:($tim/$avg_total_time)*100;
                                                                                $full = "";
                                                                                if($grp >99 ){
                                                                                    $full = 'full';
                                                                                }
                                                                                if($grp<3){
                                                                                    $grp = 3;
                                                                                }
                                                                                echo '<div class="progress_bar"><div class="bar '.$bar.' '.$full.'" style="width:'.$grp.'%">&nbsp;</div><span>'.round($tim).'</span></div>';
                                                                            }else{
                                                                                echo $tim;
                                                                            }     ?></td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                } else {
                                                                ?>
                                                                <tr class="heading" role="row">
                                                                    <td class="text-center" rowspan="1" colspan="4">No record found</td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane  @if($testtype == 'topic' && $tab == 'math')active @endif" id="tab_3_2">
                            <div class="table-scrollable">
                                @if($testtype == 'topic' && $tab == 'math')
                                <div class="row">
                                    <div class="col-md-12 heder_filer">
                                        <div class="dataTables_length select_box_row" style="margin:10px;">
                                            <label style="margin-right:20px;">
                                                <select name="sta" id="sta" class="select2me" onchange="getsta(<?php echo $schoolId; ?>, 'topic', 'math',this.value,<?php if(!empty($substrand)){ echo $substrand; }else{ echo 0 ; } ?>)">
                                                    <option value="">Select Strands</option>
                                                    <?php
                                                    if (!empty($dashboarddetailTopic['strands'])) {
                                                        foreach ($dashboarddetailTopic['strands'] as $key => $val) {
                                                            ?>    
                                                            <option value="<?php echo $val->id ?>" <?php if ($val->id == $dashboarddetailTopic['strandId']) { ?> selected="selected"<?php } ?>><?php echo $val->strand ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </label>
                                            <label><select name="substa" id="substa" class="select2me" onchange="getsubstr(<?php echo $schoolId; ?>, 'topic', '<?php echo $tab; ?>',this.value,<?php if(!empty($strand)){ echo $strand; }else{ echo 0; } ?>)">
                                                    <option value="">Select Substrand</option>
                                                    <?php
                                                    if (!empty($dashboarddetailTopic['substrands'])) {
                                                        foreach ($dashboarddetailTopic['substrands'] as $key => $val) {
                                                            ?>    
                                                            <option value="<?php echo $val->id ?>" <?php if ($val->id == $dashboarddetailTopic['substrandId']) { ?> selected="selected"<?php } ?>><?php echo $val->strand ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </label>
                                            <a href="/adminreport/schooloverview?id=<?php echo $schoolId; ?>&report=topic&tab=math&layout=<?php echo $layout ?>" style="margin-left:15px;"> <input type="button" name="reset" id="reset" value="Reset Filter" class="btn btn-primary grey"></a>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="portlet box red">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                   <input type="button" class="btn default" name="export" value="Export To Excel" onclick="exportdata('schooltopicmath','school_topic_math')"/>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="table-scrollable">
                                                    <table id="schooltopicmath" class="table table-striped table-bordered table-hover dataTable no-footer">
                                                        <thead>
                                                            <tr class="heading" role="row">
                                                                <th class="text-center" colspan="1">Class Name</th>
                                                                <th class="text-center" colspan="1">Average Score</th>
                                                                <th class="text-center" colspan="1">Attempted</th>
                                                                <th class="text-center" colspan="1">Timeliness (minutes)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            if (!empty($dashboarddetailTopic['topicdetail'])) {
                                                                foreach ($dashboarddetailTopic['topicdetail'] as $key => $val) {
                                                                    ?>      
                                                                    <tr class="heading" role="row">
                                                                        <td class="text-center" rowspan="1" colspan="1"><?php echo $val->class_name; ?></td>
                                                                        <?php
                                                                                $avg = 'NA';
                                                                                $tim = 'NA';
                                                                                
                                                                                $avg_total_time = round($val->avg_total_time/60);
                                                                                
                                                                                $atm = '<span class="glyphicon glyphicon-remove" style="color:red;"></span>';
                                                                                if(!empty($val->avg_marks))
                                                                                    $atm = '<span class="glyphicon glyphicon-ok" style="color:green;"></span>';
                                                                                
                                                                                if(!empty($val->total_record_cnt)) {
                                                                                    $tim =  round($val->avg_time/$val->total_record_cnt);
                                                                                    $tim = $tim/60;
                                                                                }
                                                                                if(!empty($val->total_record_cnt))
                                                                                    $avg = round($val->avg_marks/$val->total_record_cnt).' %';
                                                                        ?>
                                                                        <td class="text-center" rowspan="1" colspan="1"><?php  echo $avg; ?></td>
                                                                        <td class="text-center" rowspan="1" colspan="1"><?php echo $atm; ?></td>
                                                                        <td class="text-center" rowspan="1" colspan="1"><?php if($tim !='NA'){
                                                                                if(round($tim) > round($avg_total_time)){
                                                                                    $bar = "red";
                                                                                }else{
                                                                                    $bar = "green";
                                                                                }
                                                                                $grp = ($tim>$avg_total_time)?100:($tim/$avg_total_time)*100;
                                                                                $full = "";
                                                                                if($grp >99 ){
                                                                                    $full = 'full';
                                                                                }
                                                                                if($grp<3){
                                                                                    $grp = 3;
                                                                                }
                                                                                echo '<div class="progress_bar"><div class="bar '.$bar.' '.$full.'" style="width:'.$grp.'%">&nbsp;</div><span>'.round($tim).'</span></div>';
                                                                            }else{
                                                                                echo $tim;
                                                                            }     ?></td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                                } else {
                                                                ?>
                                                                <tr class="heading" role="row">
                                                                    <td class="text-center" rowspan="1" colspan="4">No record found</td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div id="barchart_material" style="width: 900px; height: 500px;"></div>

<?php if($testtype == 'dashboard'){ ?>
<form name="testgraphexport" action="/adminreport/clstest" method="post" id="testgraphexport">
    <input type="hidden" name="testclass" id="testclass" value='<?php echo json_encode($dashboarddetail['classNameArray']); ?>'/>
    <input type="hidden" name="testgraphdata" id="testgraphdata" value='<?php echo serialize($dashboarddetail['graph']); ?>'/>
</form>
<form name="graphexport" action="/adminreport/higgting" method="post" id="graphexport">
    <input type="hidden" name="class" id="class" value='<?php echo json_encode($dashboarddetail['classNameArray']); ?>'/>
    <input type="hidden" name="graphdata" id="graphdata" value='<?php echo serialize($dashboarddetail['classHittingCount']); ?>'/>
</form>
<?php } ?>
<!-- END PAGE CONTENT
 <div id="chart_div"></div>
 <style>
#chart_div1 svg > g > g > rect + rect { display:none;}
</style>
-->
@stop
@section('pagescripts')
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
{!! HTML::script('js/jquery.table2excel.js') !!}





<script>
function setdata(id,report,tab){
     
    var paperId = $('#paperId').val();
    var setId = $('#setId').val();
    if(setId == ""){
        alert("Please select set");
        return false;
    }
    var layout = '<?php echo $layout ?>';
    
    if(paperId ==""){
        window.location.href = "/adminreport/schooloverview?id=" + id + "&report=" + report + "&tab=" + tab+"&setId="+setId+"&layout="+layout;
    }else{
       window.location.href = "/adminreport/schooloverview?id=" + id + "&report=" + report + "&tab=" + tab+"&setId="+setId+"&paperId="+paperId+"&layout="+layout; 
    }    
    
}

function exportdata(id,name){
    $("#"+id).table2excel({
        exclude: ".noExl",
        name: "Excel Document Name",
        filename: name,
    }); 

}
function getreport(id, report, tab) {
     var layout = '<?php echo $layout ?>'; 
    window.location.href = "/adminreport/schooloverview?id=" + id + "&report=" + report + "&tab=" + tab+"&layout="+layout;;
}
function getsta(id,report,tab,strand,sustrand){
    var layout = '<?php echo $layout ?>'; 
    if(sustrand !=0){
        window.location.href = "/adminreport/schooloverview?id="+id+"&report="+report+"&tab="+tab+"&strand="+strand+"&substrand="+sustrand+"&layout="+layout;;
    }else{
        window.location.href = "/adminreport/schooloverview?id="+id+"&report="+report+"&tab="+tab+"&strand="+strand+"&layout="+layout;;
    }
}

function getsubstr(id,report,tab,sustrand,strand){
     var layout = '<?php echo $layout ?>'; 
    if(strand!=0){
        window.location.href = "/adminreport/schooloverview?id="+id+"&report="+report+"&tab="+tab+"&strand="+strand+"&substrand="+sustrand+"&layout="+layout;;
    }else{
        window.location.href = "/adminreport/schooloverview?id="+id+"&report="+report+"&tab="+tab+"&substrand="+sustrand+"&layout="+layout;;
    }
}
<?php if (!empty($dashboarddetail['classNameArray'])) { ?>
    google.load('visualization', '1', {packages: ['corechart', 'line','bar']});
    google.setOnLoadCallback(drawLineColors);

    function drawLineColors() {
        var data = new google.visualization.DataTable();
        data.addColumn('number', 'X');
        <?php foreach ($dashboarddetail['classNameArray'] as $key => $val) { ?>
            data.addColumn('number', '<?php echo $val; ?>');
        <?php } ?>
        <?php
        $mainArray = array();
        for ($k = 0; $k <= 20; $k++) {
            $dataArray = array();
            $j = $k;
            $dataArray[] = $j + 1;
            foreach ($dashboarddetail['classNameArray'] as $key => $val) {
                $testMarks = 0;
                if (!empty($dashboarddetail['graph'][$key][$k])) {
                    $dataArray[] = round(number_format($dashboarddetail['graph'][$key][$k],2,'.',''));
                } else {
                    $dataArray[] = 0; //rand(25,60);
                }
            }
            $mainArray[] = $dataArray;
        }
        ?>
        data.addRows(JSON.parse('<?php echo json_encode($mainArray) ?>'));
        var options = {
            hAxis: {
                title: 'Test Set'
            },
            vAxis: {
                title: 'Class Percentage (%)'
            },
            colors: ['#a52714', '#097138', 'blue', '#cccccc', 'red', 'yellow']
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
       

        

        // var components = [
          
          // {type: 'csv', datasource: 'https://spreadsheets.google.com/tq?key=pCQbetd-CptHnwJEfo8tALA'},
        
        // ];

        // var container = document.getElementById('toolbar_div');
        // google.visualization.drawToolbar(container, components);
    }
    
    
    
    
    
    
<?php } ?>

</script>
<?php if (!empty($dashboarddetail)) { ?>


<script>

    // second graph
        //google.charts.load('current', {'packages':['bar']});
        google.setOnLoadCallback(drawBarChart);
        <?php $grpArray = array();
            if(!empty($dashboarddetail['classNameArray'])){
                
                $hArray = array('Class Progress', 'Less Than 20 %', '21-50 % ', '51-70%',' Greater than 70%');
                $grpArray[]  = $hArray;   
                foreach ($dashboarddetail['classNameArray'] as $key => $val) {
                    $dArray = array();
                    $dArray[]= $val;
                    if(!empty($dashboarddetail['classHittingCount'])){
                        $dArray[] = $dashboarddetail['classHittingCount'][$key]['red'];
                        $dArray[] = $dashboarddetail['classHittingCount'][$key]['orange'];
                        $dArray[] = $dashboarddetail['classHittingCount'][$key]['green'];
                        $dArray[] = $dashboarddetail['classHittingCount'][$key]['blue'];
                    }else{
                        $dArray[] = 0;
                        $dArray[] = 0;
                        $dArray[] = 0;
                        $dArray[] = 0;
                        
                    }
                    
                    $grpArray[] = $dArray;
                }
            }
        ?>
      
        function drawBarChart() {
            var data = google.visualization.arrayToDataTable(<?php echo json_encode($grpArray)?>);

            var options = {
                // chart: {
                    // title: 'Class Hitting Target',
                // },
                hAxis: {
                  title: 'Class Progress',
                },
                vAxis: {
                  title: 'School',
                },
                bars: 'vertical', // Required for Material Bar Charts.
                colors: ['red', 'orange', 'green', 'blue'],
            
            };

            var chart = new google.charts.Bar(document.getElementById('chart_div1'));

            chart.draw(data, options);
        }
</script>
<?php } ?>
<script>
$('#exportgraph').click(function(){
    $('#graphexport').submit();
})
$('#exportgraphclass').click(function(){
    $('#testgraphexport').submit();
})
</script>
@stop