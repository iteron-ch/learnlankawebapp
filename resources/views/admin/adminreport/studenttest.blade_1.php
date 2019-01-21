@extends(($layout == 'iframe') ? 'admin.layout._reports' : 'admin.layout._default')
@section('content')
<!-- BEGIN PAGE HEADER-->
<?php if($layout != 'iframe'){ ?>
@include('admin.layout._page_header', ['title' => trans('admin/admin.manageaccount'), 'trait_1' => trans('Student Report')])
<?php } ?>

<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<style>
.down {
    background: rgba(0, 0, 0, 0) url("../../../images/up.png") no-repeat scroll -5px center;
    //display: inline-block;
   // padding: 0 0 0 21px;
   // text-indent: -9999em;
}
.up {
    background: rgba(0, 0, 0, 0) url("../../../images/down.png") no-repeat scroll -5px center;
    //display: inline-block;
    //padding: 0 0 0 21px;
    //text-indent: -9999em;
}
.select2-container{width:200px}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet ">
            <div id="graph">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet box red">
                            <div class="portlet-title">
                                <div class="caption">
                                    Pupil Details 
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-scrollable">
                                    <table class="table no_border">
                                        <tr>
                                            <td><b>Pupil Name: </b><?php echo $studentDetail[0]->first_name . " " . $studentDetail[0]->last_name; ?></td>
                                            <td><b>Class: </b><?php echo $studentDetail[0]->className; ?></td>


                                        </tr>
                                        <tr>
                                            @if($testtype == 'test')
                                                <td><b>Group: </b><?php echo $studentDetail[0]->groupName; ?></td>
                                            @endif
                                            
                                            <td <?php if($testtype == 'topic') { echo "colspan='2'";}?> ><b>Number of Awards: </b><?php echo $studentDetail[0]->certificate; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Last Login: </b><?php echo $studentDetail[0]->last_login; ?></td>
                                            <td><b>Baseline: </b>30%</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet box blue">
                            <div class="portlet-title">
                                <div class="caption">
                                    <input type="button" class="btn default" id="exportgraph" name="exportgraph" value="Export Graph To Excel" />
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div id="dual_x_div"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portlet ">
            <div class="portlet-body margin-top_20" style="position: relative;">
                <ul class="nav nav-tabs">

                    <li @if($testtype == 'test') class="active" @endif onclick="getreport('<?php echo $studentId ?>', 'test', 'math')">
                         <a href="#tab_1_1" data-toggle="tab"><span>Test Summary</span></a>
                    </li>
                    <li @if($testtype == 'topic') class="active" @endif onclick="getreport('<?php echo $studentId ?>', 'topic', 'math')">
                         <a href="#tab_1_2" data-toggle="tab"><span>Topic Summary</span></a>
                    </li>


                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade  @if($testtype == 'test') active in @endif" id="tab_1_1">
                        <div style="float:right;margin:15px 16px 0 0 ;"><a href="#" onclick="window.print()">Print Report</a></div>
                        <ul class="nav nav-tabs">
                            <li class="@if($testtype == 'test' && $tab == 'eng')active @endif" onclick="getreport('<?php echo $studentId ?>', 'test', 'eng')">
                                <a href="#tab_2_1" data-toggle="tab">
                                    <span >English</span>
                                </a></li>
                            <li class="@if($testtype == 'test' && $tab == 'math')active @endif"  onclick="getreport('<?php echo $studentId ?>', 'test', 'math')">
                                <a href="#tab_2_2" data-toggle="tab">
                                    <span>Math</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane  @if($testtype == 'test' && $tab == 'eng')active @endif" id="tab_2_1">
                                <div class="table-scrollable">
                                    @if($testtype == 'test' && $tab == 'eng')
                                    <div class="row" >
                                        <div class="col-md-12">
                                            <div class="portlet box red">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <input type="button" class="btn default" name="export" value="Export To Excel" onclick="exportdata('keywords','<?php echo "test_eng_".$studentDetail[0]->first_name . "_" . $studentDetail[0]->last_name; ?>')"/>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="table-scrollable">
                                                        
                                                        <table id="keywords" class="table table-striped table-bordered table-hover dataTable no-footer">
                                                            <colgroup>
                                                                <col width="14.2%">
                                                                <col width="14.2%">
                                                                <col width="14.2%">
                                                                <col width="14.2%">
                                                                <col width="14.2%">
                                                                <col width="14.2%">
                                                                <col width="14.2%">
                                                            </colgroup>
                                                            <thead>
                                                                <tr class="heading" role="row">
                                                                    <th class="text-center <?php echo ($sortby == 'asc')?'sorting_asc':'sorting_desc'; ?> sorting_asc" onclick="sortgrid('<?php echo $studentId ?>', 'test', 'eng','<?php echo ($sortby == 'asc')?'desc':'asc'; ?>')" rowspan="1" colspan="1">Test Name</th>
                                                                    <th class="text-center" rowspan="1" colspan="1">Attempt 1</th>
                                                                    <th class="text-center" rowspan="1" colspan="1">Attempt 2</th>
                                                                    <th class="text-center" rowspan="1" colspan="1">Attempt 3</th>
                                                                    <th class="text-center" rowspan="1" colspan="1">Average Score</th>
                                                                    <th class="text-center" rowspan="1" colspan="1">Last Assessment Date</th>
                                                                    <th class="text-center" rowspan="1" colspan="1">Timeliness (mins)</th>


                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                if (count($reportResult) > 0) {
                                                                    $overviewArray = array();
                                                                    foreach ($reportResult as $key => $val) {
                                                                        $attemptCounts = 0;
                                                                        ?>
                                                                        <?php
                                                                            $noOfPaper = ($subject == 'Math') ? 3 : 2;
                                                                                $subTotalMarksPercentage1 = 0;
                                                                                $subTotalMarksPercentage2 = 0;
                                                                                $subTotalMarksPercentage3 = 0;
                                                                                $subTotaltimeLine = array();
                                                                                $subTotalPaperTime = array();
                                                                                
                                                                            for ($i = 1; $i <= $noOfPaper; $i++) {
                                                                                $tdArray = array();
                                                                                $AttamptedAt = "";
                                                                                $timeLine = 0;
                                                                                $persubTimeLine=0;
                                                                                $subTimeBar = "";
                                                                                $submarks = 0;
                                                                                foreach ($val->subResult as $res => $row) {
                                                                                    if ($row->paper_id == ($i + 3)) {
                                                                                            $subTotalPaperTime[$row->paper_id] = $row->quesmaxtime;
                                                                                            if($row->attempt == 1) {
                                                                                                $subTotalMarksPercentage1+= ($row->mark_obtain * 100) / $row->total_marks;
                                                                                                $timeLine = $row->quesmaxtime > $row->remainingtime ? $row->quesmaxtime - $row->remainingtime : $row->remainingtime;
                                                                                                $subTotaltimeLine[$row->paper_id][]= (($timeLine) / 60) ;
                                                                                            }
                                                                                            if($row->attempt == 2) {
                                                                                                $subTotalMarksPercentage2+= ($row->mark_obtain * 100) / $row->total_marks;
                                                                                                $timeLine = $row->quesmaxtime > $row->remainingtime ? $row->quesmaxtime - $row->remainingtime : $row->remainingtime;
                                                                                                $subTotaltimeLine[$row->paper_id][]= (($timeLine) / 60) ;
                                                                                            }
                                                                                            if($row->attempt == 3) {
                                                                                                $subTotalMarksPercentage3+= ($row->mark_obtain * 100) / $row->total_marks;
                                                                                                $timeLine = $row->quesmaxtime > $row->remainingtime ? $row->quesmaxtime - $row->remainingtime : $row->remainingtime;
                                                                                                $subTotaltimeLine[$row->paper_id][]= (($timeLine) / 60) ;
                                                                                            }
                                                                                    }
                                                                                } 
                                                                            } ?>                                                                  
                                                                
                                                                
                                                                
                                                                        <tr role="row">
                                                                            <td id="tr<?php echo $val->setId; ?>" onclick="expendrow('<?php echo $val->setId; ?>')" class="down text-center"><?php echo $val->setName ?></td>
                                                                            <td class=" text-center">
                                                                                <?php
                                                                                $attemptOneAvag = 0;
                                                                                $attemptOneAvagFlag = false;
                                                                                if (!empty($val->attempt1)) {
                                                                                    $attemptOneDetail = unserialize($val->attempt1);
                                                                                    //$attemptOneAvag = $attemptOneDetail[1] * 100 / $val->totalMarks;

                                                                                    $attemptOneAvag = $subTotalMarksPercentage1/$noOfPaper;
                                                                                    
                                                                                    $attemptCounts++;
                                                                                    $attemptOneAvagFlag = true;
                                                                                }
                                                                                if ($attemptOneAvagFlag == false) {
                                                                                    echo 'NA';
                                                                                } else {
                                                                                    $barColor = 'zero';
                                                                                    $mValue = number_format($attemptOneAvag, 2, '.', '');
                                                                                            
                                                                                    $mValue = round($mValue);
                                                                                    if ($mValue > 0 && $mValue <=20)
                                                                                        $barColor = 'red';
                                                                                    else if ($mValue >= 21 && $mValue <= 50)
                                                                                        $barColor = 'yellow';
                                                                                    else if ($mValue >= 51 && $mValue <= 70)
                                                                                        $barColor = 'green';
                                                                                    else if ($mValue >70)
                                                                                        $barColor = 'blue';
                                                                                        
                                                                                    $full = "";
                                                                                    if($mValue >99){
                                                                                        $full = 'full';
                                                                                    }
                                                                                    if($mValue> 100){
                                                                                        $mValueper = 100;
                                                                                    }else if($mValue <3 && $mValue >0){
                                                                                        $mValueper = 3;
                                                                                    }else{
                                                                                        $mValueper = $mValue;
                                                                                    }    
                                                                                    
                                                                                    ?>
                                                                                    <div class="progress_bar">
                                                                                        <div class="bar <?php echo $barColor ?> <?php echo $full; ?>" style="width:<?php echo $mValueper ?>%">&nbsp;</div>
                                                                                        <span><?php echo round($mValue) ?>%</span>
                                                                                    </div>
                                                                                    <?php
                                                                                }
                                                                                ?>

                                                                            </td>
                                                                            <td class=" text-center">
                                                                                <?php
                                                                                $attemptTwoAvag = 0;
                                                                                $attemptTwoAvagFlag = false;
                                                                                if (!empty($val->attempt2)) {
                                                                                    $attemptTwoDetail = unserialize($val->attempt2);
                                                                                    //$attemptTwoAvag = $attemptTwoDetail[1] * 100 / $val->totalMarks;
                                                                                     $attemptTwoAvag = $subTotalMarksPercentage2/$noOfPaper;                                                                                  
                                                                                    
                                                                                    $attemptCounts++;
                                                                                    $attemptTwoAvagFlag = true;
                                                                                }
                                                                                if ($attemptTwoAvagFlag == false) {
                                                                                    echo 'NA';
                                                                                } else {
                                                                                    $mValue = number_format($attemptTwoAvag, 2, '.', '');
                                                                                    $barColor = 'zero';
                                                                                    
                                                                                    $mValue = round($mValue);
                                                                                    if ($mValue > 0 && $mValue <=20)
                                                                                        $barColor = 'red';
                                                                                    else if ($mValue >= 21 && $mValue <= 50)
                                                                                        $barColor = 'yellow';
                                                                                    else if ($mValue >= 51 && $mValue <= 70)
                                                                                        $barColor = 'green';
                                                                                    else if ($mValue >70)
                                                                                        $barColor = 'blue';                                                                                    
                                                                                    
                                                                                    
                                                                                    /*if ($mValue > 0 && $mValue < 30)
                                                                                        $barColor = 'red';
                                                                                    else if ($mValue >= 30 && $mValue < 60)
                                                                                        $barColor = 'yellow';
                                                                                    else if ($mValue >= 60)
                                                                                        $barColor = 'green';
                                                                                    */
                                                                                    
                                                                                    
                                                                                    
                                                                                    $full = "";
                                                                                    if($mValue >99){
                                                                                        $full = 'full';
                                                                                    }
                                                                                    if($mValue> 100){
                                                                                        $mValueper = 100;
                                                                                    }else if($mValue <3 && $mValue >0){
                                                                                        $mValueper = 3;
                                                                                    }else{
                                                                                        $mValueper = $mValue;
                                                                                    }    
                                                                                    
                                                                                    ?>
                                                                                    <div class="progress_bar">
                                                                                        <div class="bar <?php echo $barColor ?> <?php echo $full; ?>" style="width:<?php echo $mValueper ?>%">&nbsp;</div>
                                                                                        <span><?php echo round($mValue) ?>%</span>
                                                                                    </div>
                                                                                    <?php
                                                                                }
                                                                                ?>


                                                                            </td>
                                                                            <td class=" text-center">
                                                                                <?php
                                                                                $attemptThreeAvag = 0;
                                                                                $attemptThreeAvagFlag = false;
                                                                                if (!empty($val->attempt3)) {
                                                                                    $attemptThreeDetail = unserialize($val->attempt3);
                                                                                    //$attemptThreeAvag = $attemptThreeDetail[1] * 100 / $val->totalMarks;
                                                                                    $attemptThreeAvag = $subTotalMarksPercentage3/$noOfPaper;                                                                                    
                                                                                    
                                                                                    $attemptCounts++;
                                                                                    $attemptThreeAvagFlag = true;
                                                                                }

                                                                                if ($attemptThreeAvagFlag == false) {
                                                                                    echo 'NA';
                                                                                } else {
                                                                                    $mValue = number_format($attemptThreeAvag, 2, '.', '');
                                                                                    $barColor = 'zero';
                                                                                    
                                                                                    $mValue = round($mValue);
                                                                                    if ($mValue > 0 && $mValue <=20)
                                                                                        $barColor = 'red';
                                                                                    else if ($mValue >= 21 && $mValue <= 50)
                                                                                        $barColor = 'yellow';
                                                                                    else if ($mValue >= 51 && $mValue <= 70)
                                                                                        $barColor = 'green';
                                                                                    else if ($mValue >70)
                                                                                        $barColor = 'blue';                                                                                    
                                                                                    
                                                                                    /*if ($mValue > 0 && $mValue < 30)
                                                                                        $barColor = 'red';
                                                                                    else if ($mValue >= 30 && $mValue < 60)
                                                                                        $barColor = 'yellow';
                                                                                    else if ($mValue >= 60)
                                                                                        $barColor = 'green';*/
                                                                                    
                                                                                    $full = "";
                                                                                    if($mValue >99){
                                                                                        $full = 'full';
                                                                                    }
                                                                                    if($mValue> 100){
                                                                                        $mValueper = 100;
                                                                                    }else if($mValue <3 && $mValue >0){
                                                                                        $mValueper = 3;
                                                                                    }else{
                                                                                        $mValueper = $mValue;
                                                                                    }    
                                                                                    
                                                                                    ?>
                                                                                    <div class="progress_bar">
                                                                                        <div class="bar <?php echo $barColor ?> <?php echo $full; ?>" style="width:<?php echo $mValueper ?>%">&nbsp;</div>
                                                                                        <span><?php echo round($mValue) ?>%</span>
                                                                                    </div>
                                                                                    <?php
                                                                                }
                                                                                ?>

                                                                            

                                                                            </td>
                                                                            <td class=" text-center">
                                                                                <?php
                                                                                $marks = number_format(($attemptThreeAvag + $attemptTwoAvag + $attemptOneAvag) / $attemptCounts, 2, '.', '');

                                                                                $barColor = 'zero';
                                                                                
                                                                                $marks = round($marks);
                                                                                if ($marks > 0 && $marks <=20)
                                                                                    $barColor = 'red';
                                                                                else if ($marks >= 21 && $marks <= 50)
                                                                                    $barColor = 'yellow';
                                                                                else if ($marks >= 51 && $marks <= 70)
                                                                                    $barColor = 'green';
                                                                                else if ($marks >70)
                                                                                    $barColor = 'blue';    
                                                                                
                                                                                /*if ($marks > 0 && $marks < 30)
                                                                                    $barColor = 'red';
                                                                                else if ($marks >= 30 && $marks < 60)
                                                                                    $barColor = 'yellow';
                                                                                else if ($marks >= 60)
                                                                                    $barColor = 'green';*/
                                                                                
                                                                                    $full = "";
                                                                                    if($marks >99){
                                                                                        $full = 'full';
                                                                                    }
                                                                                    if($marks> 100){
                                                                                        $marksper = 100;
                                                                                    }else if($marks <3 && $marks >0){
                                                                                        $marksper = 3;
                                                                                    }else{
                                                                                        $marksper = $marks;
                                                                                    }    
                                                                                
                                                                                ?>
                                                                                <div class="progress_bar">
                                                                                    <div class="bar <?php echo $barColor ?> <?php echo $full; ?>" style="width:<?php echo $marksper ?>%">&nbsp;</div>
                                                                                    <span><?php echo round($marks) ?>%</span>
                                                                                </div>
                                                                                <?php
                                                                                if (!empty($overviewArray['max'])) {
                                                                                    if ($overviewArray['max'] < $marks) {
                                                                                        $overviewArray['max'] = $marks;
                                                                                    }
                                                                                } else {
                                                                                    $overviewArray['max'] = $marks;
                                                                                }
                                                                                if (!empty($overviewArray['min'])) {
                                                                                    if ($overviewArray['min'] > $marks) {
                                                                                        $overviewArray['min'] = $marks;
                                                                                    }
                                                                                } else {
                                                                                    $overviewArray['min'] = $marks;
                                                                                }
                                                                                if (!empty($overviewArray['avg'])) {
                                                                                    $overviewArray['avg'] = ($overviewArray['avg'] + $marks) / 2;
                                                                                } else {
                                                                                    $overviewArray['avg'] = $marks;
                                                                                }
                                                                                ?>

                                                                            </td>
                                                                            <td class=" text-center"><?php echo $val->last_assessment_date ?></td>
                                                                            <td class=" text-center">
                                                                                <?php 
                                                                                $avgToatlTime = (array_sum($subTotalPaperTime)/count($subTotalPaperTime))/60;
                                                                                $dataTimeline = array();
                                                                                foreach($subTotaltimeLine as $paper_id => $rowTimeline){
                                                                                    $dataTimeline[] = array_sum($rowTimeline)/count($rowTimeline);
                                                                                }
                                                                                $usedTime = 0; 
                                                                                $usedTime = array_sum($dataTimeline)/count($dataTimeline);
                                                                                $full = "";
                                                                                if($usedTime > $avgToatlTime){
                                                                                    $bar = "red";
                                                                                    $full = 'full';
                                                                                    $avgTimeper = 100;
                                                                                }else if($usedTime <= $avgToatlTime){
                                                                                    $bar = "green";
                                                                                    $avgTimeper = (100/$avgToatlTime)*$usedTime;
                                                                                }else{
                                                                                    $bar = "zero";
                                                                                }
                                                                                
                                                                                ?>
                                                                                <div class="progress_bar">
                                                                                    <div class="bar <?php echo $bar ?> <?php echo $full; ?>" style="width:<?php echo $avgTimeper ?>%">&nbsp;</div>
                                                                                    <span><?php echo round(number_format(($usedTime), 2, '.', '')) ?></span>
                                                                                </div>

                                                                            </td>

                                                                        </tr>
                                                                        <tr role="row" style="display:none;" id="<?php echo "subGrid" . $val->setId; ?>">
                                                                            <td colspan="7">
                                                                                <table class="noExl table table-striped table-bordered table-hover dataTable no-footer">
                                                                                    <colgroup>
                                                                                        <col width="14.2%">
                                                                                        <col width="14.2%">
                                                                                        <col width="14.2%">
                                                                                        <col width="14.2%">
                                                                                        <col width="14.2%">
                                                                                        <col width="14.2%">
                                                                                        <col width="14.2%">
                                                                                    </colgroup>
                                                                                    <tbody>
                                                                                    <tr class="heading" role="row">
                                                                                        <th class="text-center" rowspan="1" colspan="1">Test Name</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Attempt 1</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Attempt 2</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Attempt 3</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Average Score</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Last Assessment Date</th>
                                                                                        <th class="text-center" rowspan="1" colspan="1">Timeliness (mins)</th>


                                                                                    </tr>
                                                                                        <?php
                                                                                        $noOfPaper = ($subject == 'Math') ? 3 : 2;
                                                                                        for ($i = 1; $i <= $noOfPaper; $i++) {
                                                                                            $tdArray = array();
                                                                                            $tdPaperTimeArray = array();
                                                                                            $AttamptedAt = "";
                                                                                            $timeLine = "";
                                                                                            $persubTimeLine=0;
                                                                                            $subTimeBar = "";
                                                                                            foreach ($val->subResult as $res => $row) {
                                                                                                if ($row->paper_id == ($i + 3)) {
                                                                                                        $tdArray[$i + 3][] = ($row->mark_obtain * 100) / $row->total_marks;
                                                                                                        $timeLineTemp = $row->quesmaxtime > $row->remainingtime ? $row->quesmaxtime - $row->remainingtime : $row->remainingtime;
                                                                                                        $timeLine += ($timeLineTemp / 60);
                                                                                                        if (!empty($row->attempt_at)) {
                                                                                                            $AttamptedAt = $row->attempt_at;
                                                                                                        }
                                                                                                        $tdPaperTimeArray[$i+3] = $row->quesmaxtime;
                                                                                                }
                                                                                            }
                                                                                            ?>
                                                                                            <tr role="row" class="noExl">
                                                                                                <td class=" text-center"><?php echo 'Paper ' . $i; ?></td>
                                                                                                <td class=" text-center">
                                                                                                    <?php
                                                                                                    if (isset($tdArray[$i + 3][0])) {
                                                                                                        $submarks = '0';
                                                                                                        $submarks = number_format($tdArray[$i + 3][0], 2, '.', '');
                                                                                                        $barColor = 'zero';
                                                                                                        
                                                                                                        $submarks = round($submarks);
                                                                                                        if ($submarks > 0 && $submarks <=20)
                                                                                                            $barColor = 'red';
                                                                                                        else if ($submarks >= 21 && $submarks <= 50)
                                                                                                            $barColor = 'yellow';
                                                                                                        else if ($submarks >= 51 && $submarks <= 70)
                                                                                                            $barColor = 'green';
                                                                                                        else if ($submarks >70)
                                                                                                            $barColor = 'blue';         
                                                                                                        
                                                                                                        /*if ($submarks > 0 && $submarks < 30)
                                                                                                            $barColor = 'red';
                                                                                                        else if ($submarks >= 30 && $submarks < 60)
                                                                                                            $barColor = 'yellow';
                                                                                                        else if ($submarks >= 60)
                                                                                                            $barColor = 'green';
                                                                                                          */  
                                                                                                        $full= "";
                                                                                                        if($submarks>99){
                                                                                                            $full = 'full';
                                                                                                        }
                                                                                                        
                                                                                                        if($submarks>100){
                                                                                                            $submarksper = 100;
                                                                                                        }else if($submarks<3 && $submarks >0){
                                                                                                            $submarksper = 3;
                                                                                                        }else{
                                                                                                            $submarksper = $submarks;
                                                                                                        }
                                                                                                        
                                                                                                        ?>
                                                                                                        <div class="progress_bar">
                                                                                                            <div class="bar <?php echo $barColor ?> <?php echo $full; ?>" style="width:<?php echo $submarksper ?>%"></div>
                                                                                                            <span><?php echo round($submarks) ?>%</span>
                                                                                                        </div>
                                                                                                        <?php
                                                                                                    } else {
                                                                                                        echo "NA";
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td class=" text-center">
                                                                                                    <?php
                                                                                                    if (isset($tdArray[$i + 3][1])) {
                                                                                                        $submarks = '0';
                                                                                                        $submarks = number_format($tdArray[$i + 3][1], 2, '.', '');
                                                                                                        $barColor = 'zero';
                                                                                                        
                                                                                                        $submarks = round($submarks);
                                                                                                        if ($submarks > 0 && $submarks <=20)
                                                                                                            $barColor = 'red';
                                                                                                        else if ($submarks >= 21 && $submarks <= 50)
                                                                                                            $barColor = 'yellow';
                                                                                                        else if ($submarks >= 51 && $submarks <= 70)
                                                                                                            $barColor = 'green';
                                                                                                        else if ($submarks >70)
                                                                                                            $barColor = 'blue';                                                                                                          
                                                                                                        
                                                                                                        /*if ($submarks > 0 && $submarks < 30)
                                                                                                            $barColor = 'red';
                                                                                                        else if ($submarks >= 30 && $submarks < 60)
                                                                                                            $barColor = 'yellow';
                                                                                                        else if ($submarks >= 60)
                                                                                                            $barColor = 'green';*/
                                                                                                        
                                                                                                        $full= "";
                                                                                                        if($submarks>99){
                                                                                                            $full = 'full';
                                                                                                        }
                                                                                                        
                                                                                                        if($submarks>100){
                                                                                                            $submarksper = 100;
                                                                                                        }else if($submarks<3 && $submarks >0){
                                                                                                            $submarksper = 3;
                                                                                                        }else{
                                                                                                            $submarksper = $submarks;
                                                                                                        }
                                                                                                        
                                                                                                        ?>
                                                                                                        <div class="progress_bar">
                                                                                                            <div class="bar <?php echo $barColor ?> <?php echo $full; ?>" style="width:<?php echo $submarksper ?>%"></div>
                                                                                                            <span><?php echo round($submarks) ?>%</span>
                                                                                                        </div>
                                                                                                        <?php
                                                                                                    } else {
                                                                                                        echo "NA";
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td class=" text-center">
                                                                                                    <?php
                                                                                                    if (isset($tdArray[$i + 3][2])) {
                                                                                                        $submarks = '0';
                                                                                                        $submarks = number_format($tdArray[$i + 3][2], 2, '.', '');
                                                                                                        $barColor = 'zero';
                                                                                                        
                                                                                                        $submarks = round($submarks);
                                                                                                        if ($submarks > 0 && $submarks <=20)
                                                                                                            $barColor = 'red';
                                                                                                        else if ($submarks >= 21 && $submarks <= 50)
                                                                                                            $barColor = 'yellow';
                                                                                                        else if ($submarks >= 51 && $submarks <= 70)
                                                                                                            $barColor = 'green';
                                                                                                        else if ($submarks >70)
                                                                                                            $barColor = 'blue';  
                                                                                                        
                                                                                                        /*if ($submarks > 0 && $submarks < 30)
                                                                                                            $barColor = 'red';
                                                                                                        else if ($submarks >= 30 && $submarks < 60)
                                                                                                            $barColor = 'yellow';
                                                                                                        else if ($submarks >= 60)
                                                                                                            $barColor = 'green';*/
                                                                                                        
                                                                                                        
                                                                                                        $full= "";
                                                                                                        if($submarks>99){
                                                                                                            $full = 'full';
                                                                                                        }
                                                                                                        
                                                                                                        if($submarks>100){
                                                                                                            $submarksper = 100;
                                                                                                        }else if($submarks<3 && $submarks >0){
                                                                                                            $submarksper = 3;
                                                                                                        }else{
                                                                                                            $submarksper = $submarks;
                                                                                                        }
                                                                                                        
                                                                                                        ?>
                                                                                                        <div class="progress_bar">
                                                                                                            <div class="bar <?php echo $barColor ?> <?php echo $full; ?>" style="width:<?php echo $submarksper ?>%"></div>
                                                                                                            <span><?php echo round($submarks) ?>%</span>
                                                                                                        </div>
                                                                                                        <?php
                                                                                                    } else {
                                                                                                        echo "NA";
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td class=" text-center"><?php
                                                                                                    $totalAttempt = 1;
                                                                                                    if (isset($tdArray[$i + 3][1])) {
                                                                                                        $totalAttempt = 2;
                                                                                                    }
                                                                                                    if (isset($tdArray[$i + 3][2])) {
                                                                                                    $totalAttempt = 3;
                                                                                                    }
                                                                                                
                                                                                                    $ssubmarks = number_format((@$tdArray[$i + 3][0] + @$tdArray[$i + 3][1] + @$tdArray[$i + 3][2]) / $totalAttempt, 2, '.', '');

                                                                                                    $barColor = 'zero';
                                                                                                    
                                                                                                        $ssubmarks = round($ssubmarks);
                                                                                                        if ($ssubmarks > 0 && $ssubmarks <=20)
                                                                                                            $barColor = 'red';
                                                                                                        else if ($ssubmarks >= 21 && $ssubmarks <= 50)
                                                                                                            $barColor = 'yellow';
                                                                                                        else if ($ssubmarks >= 51 && $ssubmarks <= 70)
                                                                                                            $barColor = 'green';
                                                                                                        else if ($ssubmarks >70)
                                                                                                            $barColor = 'blue';                                                                                                      
                                                                                                    
                                                                                                   /* if ($ssubmarks > 0 && $ssubmarks < 30)
                                                                                                        $barColor = 'red';
                                                                                                    else if ($ssubmarks >= 30 && $ssubmarks < 60)
                                                                                                        $barColor = 'yellow';
                                                                                                    else if ($ssubmarks >= 60)
                                                                                                        $barColor = 'green';
                                                                                                    */
                                                                                                    $full = "";
                                                                                                    if($ssubmarks>99){
                                                                                                        $full = "full";
                                                                                                    }
                                                                                                    
                                                                                                    if($ssubmarks>100){
                                                                                                        $ssubmarksper = 100;
                                                                                                    }else if($ssubmarks < 3 && $ssubmarks >0){
                                                                                                        $ssubmarksper = 3;
                                                                                                    }else{
                                                                                                        $ssubmarksper = $ssubmarks;
                                                                                                    }
                                                                                                    
                                                                                                    ?>
                                                                                                    <div class="progress_bar">
                                                                                                        <div class="bar <?php echo $barColor ?> <?php echo $full ?>" style="width:<?php echo $ssubmarksper ?>%">&nbsp;</div>
                                                                                                        <span><?php echo round($ssubmarks) ?>%</span>
                                                                                                    </div></td>
                                                                                                <td class=" text-center"><?php echo (!empty($AttamptedAt)) ? $AttamptedAt : 'NA'; ?></td>
                                                                                                <td class=" text-center"><?php 
                                                                                                $subTime =  $timeLine/$totalAttempt;
                                                                                                $totalTime = $tdPaperTimeArray[$i+3]/60;
                                                                                                $barColor = 'zero';
                                                                                                $full = "";
                                                                                                if($subTime > $totalTime){
                                                                                                    $subTimeBar = "red";
                                                                                                    $full = 'full';
                                                                                                    $subTimeper = 100;
                                                                                                }else if($subTime <= $totalTime){
                                                                                                    $subTimeBar = "green";
                                                                                                    $subTimeper = (100/$totalTime)*$subTime;
                                                                                                }else{
                                                                                                    $subTimeBar = "zero";
                                                                                                }
                                                                                                
                                                                                                ?>
                                                                                                
                                                                                                    <div class="progress_bar">
                                                                                                        <div class="bar <?php echo $subTimeBar; ?> <?php echo $full; ?>" style="width:<?php echo $subTimeper; ?>%">&nbsp;</div>
                                                                                                        <span><?php echo round(number_format($subTime, 2, '.', '')); ?></span>
                                                                                                    </div>
                                                                                                
                                                                                                </td>

                                                                                            </tr>
                                                                                        <?php } ?>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>

                                                                        <?php
                                                                    }
                                                                }else {
                                                                    ?>
                                                                    <tr role="row" class="even">
                                                                        <td Colspan="7" class=" text-center">No Result Found For this Pupil</td>
                                                                    </tr>
                                                                <?php } ?>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="position: absolute;right: 0; top: 0;">
                                        <strong>BASELINE:</strong> 30% &nbsp;&nbsp;  
                                        <strong>HIGHEST SCORE:</strong> <?php echo (!empty($overviewArray['max']))?  round($overviewArray['max'])."%":"NA"; ?> &nbsp;&nbsp;  
                                        <strong>LOWEST SCORE:</strong><?php  echo (!empty($overviewArray['min']))?  round($overviewArray['min'])."%":"NA"; ?>  &nbsp;&nbsp;  
                                        <strong>AVERAGE SCORE:</strong> <?php echo (!empty($overviewArray['avg']))?  round($overviewArray['avg'])."%":"NA"; ?>&nbsp;&nbsp;  
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane  @if($testtype == 'test' && $tab == 'math')active @endif" id="tab_2_2">
                                <div class="table-scrollable">
                                    @if($testtype == 'test' && $tab == 'math')
                                    <div class="row " style="margin:15px 0 0">
                                        <div class="col-md-12">
                                            <div class="portlet box red">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <input type="button" class="btn default" name="export" value="Export To Excel" onclick="exportdata('keywords1','<?php echo "test_math_".$studentDetail[0]->first_name . "_" . $studentDetail[0]->last_name; ?>')"/>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="table-scrollable">
                                                        <table id="keywords1" class="table table-striped table-bordered table-hover dataTable no-footer">
                                                            <colgroup>
                                                                <col width="14.2%">
                                                                <col width="14.2%">
                                                                <col width="14.2%">
                                                                <col width="14.2%">
                                                                <col width="14.2%">
                                                                <col width="14.2%">
                                                                <col width="14.2%">
                                                            </colgroup>
                                                            <thead>
                                                                <tr class="heading" role="row">
                                                                    <th class="text-center <?php echo ($sortby == 'asc')?'sorting_asc':'sorting_desc'; ?> sorting_asc" onclick="sortgrid('<?php echo $studentId ?>', 'test', 'math','<?php echo ($sortby == 'asc')?'desc':'asc'; ?>')" rowspan="1" colspan="1">Test Name</th>
                                                                    <th class="text-center" rowspan="1" colspan="1">Attempt 1</th>
                                                                    <th class="text-center" rowspan="1" colspan="1">Attempt 2</th>
                                                                    <th class="text-center" rowspan="1" colspan="1">Attempt 3</th>
                                                                    <th class="text-center" rowspan="1" colspan="1">Average Score</th>
                                                                    <th class="text-center" rowspan="1" colspan="1">Last Assessment Date</th>
                                                                    <th class="text-center" rowspan="1" colspan="1">Timeliness (mins)</th>


                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                if (count($reportResult) > 0) {
                                                                    $overviewArray = array();
                                                                    foreach ($reportResult as $key => $val) {
                                                                        $attemptCounts = 0;
                                                                        ?>
                                                                        <?php
                                                                            $noOfPaper = ($subject == 'Math') ? 3 : 2;
                                                                                $subTotalMarksPercentage1 = 0;
                                                                                $subTotalMarksPercentage2 = 0;
                                                                                $subTotalMarksPercentage3 = 0;
                                                                                $subTotaltimeLine = array();
                                                                                $subTotalPaperTime = array();
                                                                                
                                                                            for ($i = 1; $i <= $noOfPaper; $i++) {
                                                                                $tdArray = array();
                                                                                $AttamptedAt = "";
                                                                                $timeLine = 0;
                                                                                $persubTimeLine=0;
                                                                                $subTimeBar = "";
                                                                                $submarks = 0;
                                                                                foreach ($val->subResult as $res => $row) {
                                                                                    if ($row->paper_id == $i) {
                                                                                            $subTotalPaperTime[$row->paper_id] = $row->quesmaxtime;
                                                                                            if($row->attempt == 1) {
                                                                                                $subTotalMarksPercentage1+= ($row->mark_obtain * 100) / $row->total_marks;
                                                                                                $timeLine = $row->quesmaxtime > $row->remainingtime ? $row->quesmaxtime - $row->remainingtime : $row->remainingtime;
                                                                                                $subTotaltimeLine[$row->paper_id][]= (($timeLine) / 60) ;
                                                                                            }
                                                                                            if($row->attempt == 2) {
                                                                                                $subTotalMarksPercentage2+= ($row->mark_obtain * 100) / $row->total_marks;
                                                                                                $timeLine = $row->quesmaxtime > $row->remainingtime ? $row->quesmaxtime - $row->remainingtime : $row->remainingtime;
                                                                                                $subTotaltimeLine[$row->paper_id][]= (($timeLine) / 60) ;
                                                                                            }
                                                                                            if($row->attempt == 3) {
                                                                                                $subTotalMarksPercentage3+= ($row->mark_obtain * 100) / $row->total_marks;
                                                                                                $timeLine = $row->quesmaxtime > $row->remainingtime ? $row->quesmaxtime - $row->remainingtime : $row->remainingtime;
                                                                                                $subTotaltimeLine[$row->paper_id][]= (($timeLine) / 60) ;
                                                                                            }
                                                                                    }
                                                                                } 
                                                                            } ?>      
                                                                <tr role="row">
                                                                        <td id="tr<?php echo $val->setId; ?>" onclick="expendrow('<?php echo $val->setId; ?>')" class="down text-center"><?php echo $val->setName ?></td>
                                                                        <td class=" text-center">
                                                                            <?php
                                                                            $attemptOneAvag = 0;
                                                                            $attemptOneAvagFlag = false;
                                                                            if (!empty($val->attempt1)) {
                                                                                $attemptOneDetail = unserialize($val->attempt1);
                                                                                //$attemptOneAvag = $attemptOneDetail[1] * 100 / $val->totalMarks;

                                                                                $attemptOneAvag = $subTotalMarksPercentage1/$noOfPaper;

                                                                                $attemptCounts++;
                                                                                $attemptOneAvagFlag = true;
                                                                            }
                                                                            if ($attemptOneAvagFlag == false) {
                                                                                echo 'NA';
                                                                            } else {
                                                                                $barColor = 'zero';
                                                                                $mValue = number_format($attemptOneAvag, 2, '.', '');

                                                                                
                                                                                $mValue = round($mValue);
                                                                                if ($mValue > 0 && $mValue <=20)
                                                                                    $barColor = 'red';
                                                                                else if ($mValue >= 21 && $mValue <= 50)
                                                                                    $barColor = 'yellow';
                                                                                else if ($mValue >= 51 && $mValue <= 70)
                                                                                    $barColor = 'green';
                                                                                else if ($mValue >70)
                                                                                    $barColor = 'blue';                                                                                  
                                                                                /*if ($mValue > 0 && $mValue < 30)
                                                                                    $barColor = 'red';
                                                                                else if ($mValue >= 30 && $mValue < 60)
                                                                                    $barColor = 'yellow';
                                                                                else if ($mValue >= 60)
                                                                                    $barColor = 'green';*/

                                                                                $full = "";
                                                                                if($mValue >99){
                                                                                    $full = 'full';
                                                                                }
                                                                                if($mValue> 100){
                                                                                    $mValueper = 100;
                                                                                }else if($mValue <3 && $mValue >0){
                                                                                    $mValueper = 3;
                                                                                }else{
                                                                                    $mValueper = $mValue;
                                                                                }    

                                                                                ?>
                                                                                <div class="progress_bar">
                                                                                    <div class="bar <?php echo $barColor ?> <?php echo $full; ?>" style="width:<?php echo $mValueper ?>%">&nbsp;</div>
                                                                                    <span><?php echo round($mValue) ?>%</span>
                                                                                </div>
                                                                                <?php
                                                                            }
                                                                            ?>

                                                                        </td>
                                                                        <td class=" text-center">
                                                                            <?php
                                                                            $attemptTwoAvag = 0;
                                                                            $attemptTwoAvagFlag = false;
                                                                            if (!empty($val->attempt2)) {
                                                                                $attemptTwoDetail = unserialize($val->attempt2);
                                                                                //$attemptTwoAvag = $attemptTwoDetail[1] * 100 / $val->totalMarks;
                                                                                 $attemptTwoAvag = $subTotalMarksPercentage2/$noOfPaper;                                                                                  

                                                                                $attemptCounts++;
                                                                                $attemptTwoAvagFlag = true;
                                                                            }
                                                                            if ($attemptTwoAvagFlag == false) {
                                                                                echo 'NA';
                                                                            } else {
                                                                                $mValue = number_format($attemptTwoAvag, 2, '.', '');
                                                                                $barColor = 'zero';
                                                                                
                                                                                $mValue = round($mValue);
                                                                                if ($mValue > 0 && $mValue <=20)
                                                                                    $barColor = 'red';
                                                                                else if ($mValue >= 21 && $mValue <= 50)
                                                                                    $barColor = 'yellow';
                                                                                else if ($mValue >= 51 && $mValue <= 70)
                                                                                    $barColor = 'green';
                                                                                else if ($mValue >70)
                                                                                    $barColor = 'blue';                                                                                  
                                                                                /*if ($mValue > 0 && $mValue < 30)
                                                                                    $barColor = 'red';
                                                                                else if ($mValue >= 30 && $mValue < 60)
                                                                                    $barColor = 'yellow';
                                                                                else if ($mValue >= 60)
                                                                                    $barColor = 'green';
                                                                                */

                                                                                $full = "";
                                                                                if($mValue >99){
                                                                                    $full = 'full';
                                                                                }
                                                                                if($mValue> 100){
                                                                                    $mValueper = 100;
                                                                                }else if($mValue <3 && $mValue >0){
                                                                                    $mValueper = 3;
                                                                                }else{
                                                                                    $mValueper = $mValue;
                                                                                }    

                                                                                ?>
                                                                                <div class="progress_bar">
                                                                                    <div class="bar <?php echo $barColor ?> <?php echo $full; ?>" style="width:<?php echo $mValueper ?>%">&nbsp;</div>
                                                                                    <span><?php echo round($mValue) ?>%</span>
                                                                                </div>
                                                                                <?php
                                                                            }
                                                                            ?>


                                                                        </td>
                                                                        <td class=" text-center">
                                                                            <?php
                                                                            $attemptThreeAvag = 0;
                                                                            $attemptThreeAvagFlag = false;
                                                                            if (!empty($val->attempt3)) {
                                                                                $attemptThreeDetail = unserialize($val->attempt3);
                                                                                //$attemptThreeAvag = $attemptThreeDetail[1] * 100 / $val->totalMarks;
                                                                                $attemptThreeAvag = $subTotalMarksPercentage3/$noOfPaper;                                                                                    

                                                                                $attemptCounts++;
                                                                                $attemptThreeAvagFlag = true;
                                                                            }

                                                                            if ($attemptThreeAvagFlag == false) {
                                                                                echo 'NA';
                                                                            } else {
                                                                                $mValue = number_format($attemptThreeAvag, 2, '.', '');
                                                                                $barColor = 'zero';
                                                                                
                                                                                $mValue = round($mValue);
                                                                                if ($mValue > 0 && $mValue <=20)
                                                                                    $barColor = 'red';
                                                                                else if ($mValue >= 21 && $mValue <= 50)
                                                                                    $barColor = 'yellow';
                                                                                else if ($mValue >= 51 && $mValue <= 70)
                                                                                    $barColor = 'green';
                                                                                else if ($mValue >70)
                                                                                    $barColor = 'blue';  
                                                                                
                                                                                /*if ($mValue > 0 && $mValue < 30)
                                                                                    $barColor = 'red';
                                                                                else if ($mValue >= 30 && $mValue < 60)
                                                                                    $barColor = 'yellow';
                                                                                else if ($mValue >= 60)
                                                                                    $barColor = 'green';*/

                                                                                $full = "";
                                                                                if($mValue >99){
                                                                                    $full = 'full';
                                                                                }
                                                                                if($mValue> 100){
                                                                                    $mValueper = 100;
                                                                                }else if($mValue <3 && $mValue >0){
                                                                                    $mValueper = 3;
                                                                                }else{
                                                                                    $mValueper = $mValue;
                                                                                }    

                                                                                ?>
                                                                                <div class="progress_bar">
                                                                                    <div class="bar <?php echo $barColor ?> <?php echo $full; ?>" style="width:<?php echo $mValueper ?>%">&nbsp;</div>
                                                                                    <span><?php echo round($mValue) ?>%</span>
                                                                                </div>
                                                                                <?php
                                                                            }
                                                                            ?>



                                                                        </td>
                                                                        <td class=" text-center">
                                                                            <?php
                                                                            $marks = number_format(($attemptThreeAvag + $attemptTwoAvag + $attemptOneAvag) / $attemptCounts, 2, '.', '');

                                                                            $barColor = 'zero';
                                                                            
                                                                                $marks = round($marks);
                                                                                if ($marks > 0 && $marks <=20)
                                                                                    $barColor = 'red';
                                                                                else if ($marks >= 21 && $marks <= 50)
                                                                                    $barColor = 'yellow';
                                                                                else if ($marks >= 51 && $marks <= 70)
                                                                                    $barColor = 'green';
                                                                                else if ($marks >70)
                                                                                    $barColor = 'blue';                                                                              
                                                                            /*if ($marks > 0 && $marks < 30)
                                                                                $barColor = 'red';
                                                                            else if ($marks >= 30 && $marks < 60)
                                                                                $barColor = 'yellow';
                                                                            else if ($marks >= 60)
                                                                                $barColor = 'green';*/

                                                                                $full = "";
                                                                                if($marks >99){
                                                                                    $full = 'full';
                                                                                }
                                                                                if($marks> 100){
                                                                                    $marksper = 100;
                                                                                }else if($marks <3 && $marks >0){
                                                                                    $marksper = 3;
                                                                                }else{
                                                                                    $marksper = $marks;
                                                                                }    

                                                                            ?>
                                                                            <div class="progress_bar">
                                                                                <div class="bar <?php echo $barColor ?> <?php echo $full; ?>" style="width:<?php echo $marksper ?>%">&nbsp;</div>
                                                                                <span><?php echo round($marks) ?>%</span>
                                                                            </div>
                                                                            <?php
                                                                            if (!empty($overviewArray['max'])) {
                                                                                if ($overviewArray['max'] < $marks) {
                                                                                    $overviewArray['max'] = $marks;
                                                                                }
                                                                            } else {
                                                                                $overviewArray['max'] = $marks;
                                                                            }
                                                                            if (!empty($overviewArray['min'])) {
                                                                                if ($overviewArray['min'] > $marks) {
                                                                                    $overviewArray['min'] = $marks;
                                                                                }
                                                                            } else {
                                                                                $overviewArray['min'] = $marks;
                                                                            }
                                                                            if (!empty($overviewArray['avg'])) {
                                                                                $overviewArray['avg'] = ($overviewArray['avg'] + $marks) / 2;
                                                                            } else {
                                                                                $overviewArray['avg'] = $marks;
                                                                            }
                                                                            ?>

                                                                        </td>
                                                                        <td class=" text-center"><?php echo $val->last_assessment_date ?></td>
                                                                        <td class=" text-center">
                                                                            <?php 
                                                                            $avgToatlTime = (array_sum($subTotalPaperTime)/count($subTotalPaperTime))/60;
                                                                            $dataTimeline = array();
                                                                            foreach($subTotaltimeLine as $paper_id => $rowTimeline){
                                                                                $dataTimeline[] = array_sum($rowTimeline)/count($rowTimeline);
                                                                            }
                                                                            $usedTime = 0; 
                                                                            $usedTime = array_sum($dataTimeline)/count($dataTimeline);
                                                                            $full = "";
                                                                            if($usedTime > $avgToatlTime){
                                                                                $bar = "red";
                                                                                $full = 'full';
                                                                                $avgTimeper = 100;
                                                                            }else if($usedTime <= $avgToatlTime){
                                                                                $bar = "green";
                                                                                $avgTimeper = (100/$avgToatlTime)*$usedTime;
                                                                            }else{
                                                                                $bar = "zero";
                                                                            }

                                                                            ?>
                                                                            <div class="progress_bar">
                                                                                <div class="bar <?php echo $bar ?> <?php echo $full; ?>" style="width:<?php echo $avgTimeper ?>%">&nbsp;</div>
                                                                                <span><?php echo round(number_format(($usedTime), 2, '.', '')) ?></span>
                                                                            </div>

                                                                        </td>

                                                                    </tr>
                                                                        <tr role="row" style="display:none;" id="<?php echo "subGrid" . $val->setId; ?>">
                                                                            <td colspan="7">
                                                                                <table class=" noExl table table-striped table-bordered table-hover dataTable no-footer">
                                                                                    <colgroup>
                                                                                        <col width="14.2%">
                                                                                        <col width="14.2%">
                                                                                        <col width="14.2%">
                                                                                        <col width="14.2%">
                                                                                        <col width="14.2%">
                                                                                        <col width="14.2%">
                                                                                        <col width="14.2%">
                                                                                    </colgroup>
                                                                                    <tbody>
                                                                                        <tr class="heading" role="row">
                                                                                            <th class="text-center" rowspan="1" colspan="1">Test Name</th>
                                                                                            <th class="text-center" rowspan="1" colspan="1">Attempt 1</th>
                                                                                            <th class="text-center" rowspan="1" colspan="1">Attempt 2</th>
                                                                                            <th class="text-center" rowspan="1" colspan="1">Attempt 3</th>
                                                                                            <th class="text-center" rowspan="1" colspan="1">Average Score</th>
                                                                                            <th class="text-center" rowspan="1" colspan="1">Last Assessment Date</th>
                                                                                            <th class="text-center" rowspan="1" colspan="1">Timeliness (mins)</th>


                                                                                        </tr>
                                                                                        <?php
                                                                                        $noOfPaper = ($subject == 'Math') ? 3 : 2;
                                                                                        for ($i = 1; $i <= $noOfPaper; $i++) {
                                                                                            $tdArray = array();
                                                                                            $tdPaperTimeArray = array();
                                                                                            $AttamptedAt = "";
                                                                                            $timeLine = "";
                                                                                            $persubTimeLine=0;
                                                                                            $subTimeBar = "";
                                                                                            foreach ($val->subResult as $res => $row) {
                                                                                                if ($row->paper_id == $i) {
                                                                                                        $tdArray[$i][] = ($row->mark_obtain * 100) / $row->total_marks;
                                                                                                        $timeLineTemp = $row->quesmaxtime > $row->remainingtime ? $row->quesmaxtime - $row->remainingtime : $row->remainingtime;
                                                                                                        $timeLine += ($timeLineTemp / 60);
                                                                                                        if (!empty($row->attempt_at)) {
                                                                                                            $AttamptedAt = $row->attempt_at;
                                                                                                        }
                                                                                                        $tdPaperTimeArray[$i] = $row->quesmaxtime;
                                                                                                }
                                                                                            }
                                                                                            ?>
                                                                                            <tr role="row" class="noExl">
                                                                                                <td class=" text-center"><?php echo 'Paper ' . $i; ?></td>
                                                                                                <td class=" text-center">
                                                                                                    <?php
                                                                                                    if (isset($tdArray[$i][0])) {
                                                                                                        $submarks = '0';
                                                                                                        $submarks = number_format($tdArray[$i][0], 2, '.', '');
                                                                                                        $barColor = 'zero';
                                                                                                        
                                                                                                        $submarks = round($submarks);
                                                                                                        if ($submarks > 0 && $submarks <=20)
                                                                                                            $barColor = 'red';
                                                                                                        else if ($submarks >= 21 && $submarks <= 50)
                                                                                                            $barColor = 'yellow';
                                                                                                        else if ($submarks >= 51 && $submarks <= 70)
                                                                                                            $barColor = 'green';
                                                                                                        else if ($submarks >70)
                                                                                                            $barColor = 'blue';                                                                                                        
                                                                                                        /*if ($submarks > 0 && $submarks < 30)
                                                                                                            $barColor = 'red';
                                                                                                        else if ($submarks >= 30 && $submarks < 60)
                                                                                                            $barColor = 'yellow';
                                                                                                        else if ($submarks >= 60)
                                                                                                            $barColor = 'green';
                                                                                                        */
                                                                                                            
                                                                                                        $full= "";
                                                                                                        if($submarks>99){
                                                                                                            $full = 'full';
                                                                                                        }
                                                                                                        
                                                                                                        if($submarks>100){
                                                                                                            $submarksper = 100;
                                                                                                        }else if($submarks<3 && $submarks >0){
                                                                                                            $submarksper = 3;
                                                                                                        }else{
                                                                                                            $submarksper = $submarks;
                                                                                                        }
                                                                                                        
                                                                                                        ?>
                                                                                                        <div class="progress_bar">
                                                                                                            <div class="bar <?php echo $barColor ?> <?php echo $full; ?>" style="width:<?php echo $submarksper ?>%"></div>
                                                                                                            <span><?php echo round($submarks) ?>%</span>
                                                                                                        </div>
                                                                                                        <?php
                                                                                                    } else {
                                                                                                        echo "NA";
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td class=" text-center">
                                                                                                    <?php
                                                                                                    if (isset($tdArray[$i][1])) {
                                                                                                        $submarks = '0';
                                                                                                        $submarks = number_format($tdArray[$i][1], 2, '.', '');
                                                                                                        $barColor = 'zero';
                                                                                                        
                                                                                                        $submarks = round($submarks);
                                                                                                        if ($submarks > 0 && $submarks <=20)
                                                                                                            $barColor = 'red';
                                                                                                        else if ($submarks >= 21 && $submarks <= 50)
                                                                                                            $barColor = 'yellow';
                                                                                                        else if ($submarks >= 51 && $submarks <= 70)
                                                                                                            $barColor = 'green';
                                                                                                        else if ($submarks >70)
                                                                                                            $barColor = 'blue';
                                                                                                        /*
                                                                                                        if ($submarks > 0 && $submarks < 30)
                                                                                                            $barColor = 'red';
                                                                                                        else if ($submarks >= 30 && $submarks < 60)
                                                                                                            $barColor = 'yellow';
                                                                                                        else if ($submarks >= 60)
                                                                                                            $barColor = 'green';
                                                                                                        */
                                                                                                        
                                                                                                        $full= "";
                                                                                                        if($submarks>99){
                                                                                                            $full = 'full';
                                                                                                        }
                                                                                                        
                                                                                                        if($submarks>100){
                                                                                                            $submarksper = 100;
                                                                                                        }else if($submarks<3 && $submarks >0){
                                                                                                            $submarksper = 3;
                                                                                                        }else{
                                                                                                            $submarksper = $submarks;
                                                                                                        }
                                                                                                        
                                                                                                        ?>
                                                                                                        <div class="progress_bar">
                                                                                                            <div class="bar <?php echo $barColor ?> <?php echo $full; ?>" style="width:<?php echo $submarksper ?>%"></div>
                                                                                                            <span><?php echo round($submarks) ?>%</span>
                                                                                                        </div>
                                                                                                        <?php
                                                                                                    } else {
                                                                                                        echo "NA";
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td class=" text-center">
                                                                                                    <?php
                                                                                                    if (isset($tdArray[$i][2])) {
                                                                                                        $submarks = '0';
                                                                                                        $submarks = number_format($tdArray[$i][2], 2, '.', '');
                                                                                                        $barColor = 'zero';
                                                                                                        
                                                                                                        $submarks = round($submarks);
                                                                                                        if ($submarks > 0 && $submarks <=20)
                                                                                                            $barColor = 'red';
                                                                                                        else if ($submarks >= 21 && $submarks <= 50)
                                                                                                            $barColor = 'yellow';
                                                                                                        else if ($submarks >= 51 && $submarks <= 70)
                                                                                                            $barColor = 'green';
                                                                                                        else if ($submarks >70)
                                                                                                            $barColor = 'blue';
                                                                                                        
                                                                                                        /*if ($submarks > 0 && $submarks < 30)
                                                                                                            $barColor = 'red';
                                                                                                        else if ($submarks >= 30 && $submarks < 60)
                                                                                                            $barColor = 'yellow';
                                                                                                        else if ($submarks >= 60)
                                                                                                            $barColor = 'green';*/
                                                                                                        
                                                                                                        $full= "";
                                                                                                        if($submarks>99){
                                                                                                            $full = 'full';
                                                                                                        }
                                                                                                        
                                                                                                        if($submarks>100){
                                                                                                            $submarksper = 100;
                                                                                                        }else if($submarks<3 && $submarks >0){
                                                                                                            $submarksper = 3;
                                                                                                        }else{
                                                                                                            $submarksper = $submarks;
                                                                                                        }
                                                                                                        
                                                                                                        ?>
                                                                                                        <div class="progress_bar">
                                                                                                            <div class="bar <?php echo $barColor ?> <?php echo $full; ?>" style="width:<?php echo $submarksper ?>%"></div>
                                                                                                            <span><?php echo round($submarks) ?>%</span>
                                                                                                        </div>
                                                                                                        <?php
                                                                                                    } else {
                                                                                                        echo "NA";
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                                <td class=" text-center"><?php
                                                                                                    $totalAttempt = 1;
                                                                                                    if (isset($tdArray[$i][1])) {
                                                                                                        $totalAttempt = 2;
                                                                                                    }
                                                                                                    if (isset($tdArray[$i][2])) {
                                                                                                    $totalAttempt = 3;
                                                                                                    }
                                                                                                
                                                                                                    $ssubmarks = number_format((@$tdArray[$i][0] + @$tdArray[$i][1] + @$tdArray[$i][2]) / $totalAttempt, 2, '.', '');

                                                                                                    $barColor = 'zero';
                                                                                                    
                                                                                                        $ssubmarks = round($ssubmarks);
                                                                                                        if ($ssubmarks > 0 && $ssubmarks <=20)
                                                                                                            $barColor = 'red';
                                                                                                        else if ($ssubmarks >= 21 && $ssubmarks <= 50)
                                                                                                            $barColor = 'yellow';
                                                                                                        else if ($ssubmarks >= 51 && $ssubmarks <= 70)
                                                                                                            $barColor = 'green';
                                                                                                        else if ($ssubmarks >70)
                                                                                                            $barColor = 'blue';
                                                                                                        
                                                                                                    /*if ($ssubmarks > 0 && $ssubmarks < 30)
                                                                                                        $barColor = 'red';
                                                                                                    else if ($ssubmarks >= 30 && $ssubmarks < 60)
                                                                                                        $barColor = 'yellow';
                                                                                                    else if ($ssubmarks >= 60)
                                                                                                        $barColor = 'green';
                                                                                                    
                                                                                                    */
                                                                                                    $full = "";
                                                                                                    if($ssubmarks>99){
                                                                                                        $full = "full";
                                                                                                    }
                                                                                                    
                                                                                                    if($ssubmarks>100){
                                                                                                        $ssubmarksper = 100;
                                                                                                    }else if($ssubmarks < 3 && $ssubmarks >0){
                                                                                                        $ssubmarksper = 3;
                                                                                                    }else{
                                                                                                        $ssubmarksper = $ssubmarks;
                                                                                                    }
                                                                                                    
                                                                                                    ?>
                                                                                                    <div class="progress_bar">
                                                                                                        <div class="bar <?php echo $barColor ?> <?php echo $full ?>" style="width:<?php echo $ssubmarksper ?>%">&nbsp;</div>
                                                                                                        <span><?php echo round($ssubmarks) ?>%</span>
                                                                                                    </div></td>
                                                                                                <td class=" text-center"><?php echo (!empty($AttamptedAt)) ? $AttamptedAt : 'NA'; ?></td>
                                                                                                <td class=" text-center"><?php 
                                                                                                $subTime =  $timeLine/$totalAttempt;
                                                                                                $totalTime = $tdPaperTimeArray[$i]/60;
                                                                                                $barColor = 'zero';
                                                                                                $full = "";
                                                                                                if($subTime > $totalTime){
                                                                                                    $subTimeBar = "red";
                                                                                                    $full = 'full';
                                                                                                    $subTimeper = 100;
                                                                                                }else if($subTime <= $totalTime){
                                                                                                    $subTimeBar = "green";
                                                                                                    $subTimeper = (100/$totalTime)*$subTime;
                                                                                                }else{
                                                                                                    $subTimeBar = "zero";
                                                                                                }
                                                                                                
                                                                                                ?>
                                                                                                
                                                                                                    <div class="progress_bar">
                                                                                                        <div class="bar <?php echo $subTimeBar; ?> <?php echo $full; ?>" style="width:<?php echo $subTimeper; ?>%">&nbsp;</div>
                                                                                                        <span><?php echo round(number_format($subTime, 2, '.', '')); ?></span>
                                                                                                    </div>
                                                                                                
                                                                                                </td>

                                                                                            </tr>
                                                                                        <?php } ?>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>

                                                                        <?php
                                                                    }
                                                                } else {
                                                                    ?>
                                                                    <tr role="row" class="even">
                                                                        <td Colspan="7" class=" text-center">No Result Found For this Pupil</td>
                                                                    </tr>
                                                                <?php } ?>

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="position: absolute;right: 0; top: 0;">
                                        <strong>BASELINE:</strong> 30% &nbsp;&nbsp;  
                                        <strong>HIGHEST SCORE:</strong> <?php echo (!empty($overviewArray['max']))?  round($overviewArray['max'])."%":"NA"; ?> &nbsp;&nbsp;  
                                        <strong>LOWEST SCORE:</strong><?php  echo (!empty($overviewArray['min']))?  round($overviewArray['min'])."%":"NA"; ?>  &nbsp;&nbsp;  
                                        <strong>AVERAGE SCORE:</strong> <?php echo (!empty($overviewArray['avg']))?  round($overviewArray['avg'])."%":"NA"; ?>&nbsp;&nbsp;  
                                    </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade @if($testtype == 'topic')active in @endif" id="tab_1_2">
                        <div style="float:right;margin:15px 16px 0 0 ;"><a href="#" onclick="window.print()">Print Report</a></div>
                        <ul class="nav nav-tabs">
                            <li class="@if($testtype == 'topic' && $tab == 'eng')active @endif"  onclick="getreport('<?php echo $studentId ?>', 'topic', 'eng')">
                                <a href="#tab_3_1" data-toggle="tab">
                                    <span>English</span>
                                </a></li>
                            <li class="@if($testtype == 'topic' && $tab == 'math')active @endif"  onclick="getreport('<?php echo $studentId ?>', 'topic', 'math')">
                                <a href="#tab_3_2" data-toggle="tab">
                                    <span>Math</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane  @if($testtype == 'topic' && $tab == 'eng')active @endif" id="tab_3_1">
                                <div class="table-scrollable">
                                    @if($testtype == 'topic' && $tab == 'eng')
                                    <div class="row" style="margin:15px 0 0">
                                        <div class="col-md-12">
                                            <div class="portlet box red">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                         <input type="button" class="btn default" name="export" value="Export To Excel" onclick="exportdata('keywords2','<?php echo "topic_eng_".$studentDetail[0]->first_name . "_" . $studentDetail[0]->last_name; ?>')"/>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="table-scrollable">


                                                        <table id="keywords2" class="table table-striped table-bordered table-hover dataTable no-footer"><colgroup>
                                                                <col width="16.6%">
                                                                <col width="16.6%">
                                                                <col width="16.6%">
                                                                <col width="16.6%">
                                                                <col width="16.6%">
                                                                
                                                            </colgroup>
                                                            <thead>
                                                                <tr class="heading" role="row">
                                                                    <th class="text-center" rowspan="1" colspan="1">Attempted</th>
                                                                    <th  class="text-center <?php echo ($sortby == 'asc')?'sorting_asc':'sorting_desc'; ?> sorting_asc" onclick="sortgrid('<?php echo $studentId ?>', 'topic', 'eng','<?php echo ($sortby == 'asc')?'desc':'asc'; ?>')" rowspan="1" colspan="1">Topic Name</th>
                                                                    <!--<th class="text-center" rowspan="1" colspan="1">status</th>-->
                                                                    <th class="text-center" rowspan="1" colspan="1">No. Attempts</th>
                                                                    <th class="text-center" rowspan="1" colspan="1">No. of Questions Attempted </th>
                                                                    <!--<th class="text-center" rowspan="1" colspan="1">Usage</th>-->
                                                                    <th class="text-center" rowspan="1" colspan="1">Topic Performance</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                
                                                                //asd($reportResult);
                                                                if (count($reportResult) > 0) {
                                                                    foreach ($reportResult as $key => $val) {
                                                                        $attampted = '<span class="glyphicon glyphicon-remove" style="color:red;"></span>';
                                                                        $perMarks = 0;
                                                                        $perMarks = number_format($val->strand_mark_obtain * 100 / $val->total_marks, 2, '.', '');
                                                                        
                                                                        $attemptCounts = 0;
                                                                        $totalSubStrands = count($val->subResult);
                                                                        $substandsArray = array();
                                                                        if (!empty($val->substrand_aggrigate)) {
                                                                            $substandsArray = unserialize($val->substrand_aggrigate);
                                                                            //asd($substandsArray);
                                                                            $attamptedSubStands = $attemptCounts = count($substandsArray);
                                                                            if ($attamptedSubStands > 0) {
                                                                                $attampted = '<span class="glyphicon glyphicon-ok" style="color:green;"></span>';
                                                                            }
                                                                        }

                                                                        $time = 0;
                                                                        $time = number_format((($val->quesmaxtime - $val->remainingtime) / 60), 2, '.', '');
                                                                        if (!empty($substandsArray)) {
                                                                            $totalMarks = 0;
                                                                            $obtainedMarks = 0;
                                                                            foreach ($substandsArray as $k => $v) {
                                                                                $totalMarks += $v[0];
                                                                                $obtainedMarks += $v[1];
                                                                            }
                                                                            $perceMarks = ($obtainedMarks * 100) / $totalMarks;
                                                                        }
                                                                        $subMarks = 0;
                                                                        if (count($val->subResult) > 0) {
                                                                            foreach ($val->subResult as $res => $row) {
                                                                                if(isset($substandsArray[$row->id])){
                                                                                $subStRes = $substandsArray[$row->id];
                                                                                $subMarks+= (number_format(($subStRes[1] * 100) / $subStRes[0],2,'.',''));
                                                                                }
                                                                            }
                                                                            $perceMarks =  $subMarks/count($val->subResult);
                                                                            //$graph['English'][] = round($perceMarks);
                                                                        }
                                                                       
                                                                        
                                                                        
                                                                        if ($perceMarks >= 70) {
                                                                            $status = '<span style=" background: green;border-radius: 50% !important;display: inline-block;height: 40px;line-height: 40px;text-align: center; width: 40px;"  title="70%-100%">'.round($perceMarks).'%</span>';
                                                                        } elseif ($perceMarks >= 50 && $perceMarks < 70) {
                                                                            $status = '<span style=" background: yellow;border-radius: 50% !important;display: inline-block;height: 40px;line-height: 40px;text-align: center; width: 40px;"   title="69%-50%">'.round($perceMarks).'%</span>';
                                                                        } else {
                                                                            $status = '<span style=" background: red;border-radius: 50% !important;display: inline-block;height: 40px;line-height: 40px;text-align: center; width: 40px;"   title="0%-49%">'.round($perceMarks).'%</span>';
                                                                        }
                                                                        ?>
                                                                        <tr role="row"  class="odd" >
                                                                            <td id="tr<?php echo $val->taskId; ?>" onclick="expendrowrev('<?php echo $val->taskId; ?>')" class="down text-center"><?php echo $attampted; ?></td>
                                                                            <td class=" text-center"><?php echo $val->strand; ?></td>
                                                                            <!--<td class=" text-center"><?php //echo $status; ?></td>-->
                                                                            <td class=" text-center"><?php echo $val->attempt_count;//$attamptedSubStands; ?></td>
                                                                            <td class=" text-center"><?php echo $val->num_answered; ?></td>
                                                                            <!--<td class=" text-center"><?php //echo $time; ?></td>-->
                                                                            <td class=" text-center">
                                                                            
                                                                                         <?php $subMarks = number_format($perceMarks, 2, '.', ''); 
                                                                                                        $barColor = 'zero';
                                                                                                            /*if ($subMarks > 0 && $subMarks < 30)
                                                                                                                $barColor = 'red';
                                                                                                            else if ($subMarks >= 30 && $subMarks < 60)
                                                                                                                $barColor = 'yellow';
                                                                                                            else if ($subMarks >= 60)
                                                                                                                $barColor = 'green';*/
                                                                                                            
                                                                                                        $subMarks = round($subMarks);
                                                                                                        if ($subMarks > 0 && $subMarks <=20)
                                                                                                            $barColor = 'red';
                                                                                                        else if ($subMarks >= 21 && $subMarks <= 50)
                                                                                                            $barColor = 'yellow';
                                                                                                        else if ($subMarks >= 51 && $subMarks <= 70)
                                                                                                            $barColor = 'green';
                                                                                                        else if ($subMarks >70)
                                                                                                            $barColor = 'blue';                                                                                                        
                                                                                                            
                                                                                                                
                                                                                                            $full = "";
                                                                                                            if($subMarks>99){
                                                                                                                $full = "full";
                                                                                                            }
                                                                                                            if($subMarks >100){
                                                                                                                $subMarksper = 100;
                                                                                                            }else if($subMarks < 3 && $subMarks > 0){
                                                                                                                $subMarksper = 3;
                                                                                                            }else{
                                                                                                                $subMarksper = $subMarks;
                                                                                                            }
                                                                                                                
                                                                                                            ?>
                                                                                                            <div class="progress_bar">
                                                                                                                <div class="bar <?php echo $barColor ?> <?php echo $full; ?>" style="width:<?php echo $subMarksper ?>%">&nbsp;</div>
                                                                                                                <span><?php echo round($subMarks) ?>%</span>
                                                                                                            </div>
                                                                            
                                                                            </td>
                                                                        </tr>
                                                                        <tr role="row" style="display:none;" id="<?php echo "subGridrev" . $val->taskId; ?>">
                                                                            <td colspan="7">
                                                                                <table class="noExl table table-striped table-bordered table-hover dataTable no-footer">
                                                                                    <colgroup>
                                                                                        <col width="16.6%">
                                                                                        <col width="16.6%">
                                                                                        <col width="16.6%">
                                                                                        <col width="16.6%">
                                                                                        <col width="16.6%">
                                                                                        
                                                                                    </colgroup>
                                                                                    <tbody>
                                                                                         <tr class="heading" role="row">
                                                                                            <th class="text-center" rowspan="1" colspan="1">Attempted</th>
                                                                                            <th class="text-center" rowspan="1" colspan="1">Topic Name</th>
                                                                                            <!--<th class="text-center" rowspan="1" colspan="1">status</th>-->
                                                                                            <th class="text-center" rowspan="1" colspan="1">No. Attempts</th>
                                                                                            <th class="text-center" rowspan="1" colspan="1">No. of Questions Attempted </th>
                                                                                           <!-- <th class="text-center" rowspan="1" colspan="1">Usage</th>-->
                                                                                            <th class="text-center" rowspan="1" colspan="1">Topic Performance</th>
                                                                                        </tr>
                                                                                        <?php
                                                                                        if (count($val->subResult) > 0) {
                                                                                            foreach ($val->subResult as $res => $row) {
                                                                                                if(isset($substandsArray[$row->id])){
                                                                                                $subStRes = $substandsArray[$row->id];
                                                                                                $subMarks = (number_format(($subStRes[1] * 100) / $subStRes[0],2,'.',''));
                                                                                                if ($subMarks >= 70) {
                                                                                                    $status1 = '<span style=" background: green;border-radius: 50% !important;display: inline-block;height: 40px;line-height: 40px;text-align: center; width: 40px;"   title="70%-100%">'.round($subMarks).'%</span>';
                                                                                                } elseif ($subMarks >= 50 && $subMarks < 70) {
                                                                                                    $status1 = '<span style=" background: yellow;border-radius: 50% !important;display: inline-block;height: 40px;line-height: 40px;text-align: center; width: 40px;"  title="69%-50%">'.round($subMarks).'%</span>';
                                                                                                } else {
                                                                                                    $status1 = '<span style=" background: red;border-radius: 50% !important;display: inline-block;height: 40px;line-height: 40px;text-align: center; width: 40px;"  title="0-49%">'.round($subMarks).'%</span>';
                                                                                                }
                                                                                                }
                                                                                                ?>
                                                                                                <tr role="row" class="noExl">

                                                                                                    <td class=" text-center"></td>
                                                                                                    <td class=" text-center"><?php echo $row->strand; ?></td>
                                                                                                    <!--<td class=" text-center"><?php //echo $status1; ?></td>-->
                                                                                                    <td class=" text-center"><?php echo $row->attempt_count; ?></td>
                                                                                                   <!-- <td class=" text-center">NA</td> -->
                                                                                                    <td class=" text-center"><?php echo $row->num_answered; ?></td>
                                                                                                    <td class=" text-center">
                                                                                                    <?php //echo $subMarks; 
                                                                                                        $barColor = 'zero';
                                                                                                        
                                                                                                            $subMarks = round($subMarks);
                                                                                                            if ($subMarks > 0 && $subMarks <=20)
                                                                                                                $barColor = 'red';
                                                                                                            else if ($subMarks >= 21 && $subMarks <= 50)
                                                                                                                $barColor = 'yellow';
                                                                                                            else if ($subMarks >= 51 && $subMarks <= 70)
                                                                                                                $barColor = 'green';
                                                                                                            else if ($subMarks >70)
                                                                                                                $barColor = 'blue';
                                                                                                        
                                                                                                            /*if ($subMarks > 0 && $subMarks < 30)
                                                                                                                $barColor = 'red';
                                                                                                            else if ($subMarks >= 30 && $subMarks < 60)
                                                                                                                $barColor = 'yellow';
                                                                                                            else if ($subMarks >= 60)
                                                                                                                $barColor = 'green';*/
                                                                                                                
                                                                                                            $full = "";
                                                                                                            if($subMarks>98){
                                                                                                                $full = 'full';
                                                                                                            }
                                                                                                            if($subMarks >100){
                                                                                                                $submarkper = 100;
                                                                                                            }else if($subMarks < 3 && $subMarks > 0){
                                                                                                                   $submarkper = 3;
                                                                                                            }else{
                                                                                                                $submarkper = $subMarks;
                                                                                                            }      
                                                                                                            ?>
                                                                                                            <div class="progress_bar">
                                                                                                                <div class="bar <?php echo $barColor ?> <?php echo $full; ?>" style="width:<?php echo $submarkper ?>%">&nbsp;</div>
                                                                                                                <span><?php echo round($subMarks) ?>%</span>
                                                                                                            </div>
                                                                                                    </td>


                                                                                                </tr>
                                                                                                <?php
                                                                                            }
                                                                                        }
                                                                                        ?> 
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>

                                                                        <?php
                                                                    }
                                                                } else {
                                                                    ?>
                                                                    <tr role="row" class="even">
                                                                        <td Colspan="7" class=" text-center">No Result Found For this Pupil</td>
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
                                    <div class="row" style="margin:15px 0 0">
                                        <div class="col-md-12">
                                            <div class="portlet box red">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <input type="button" class="btn default" name="export" value="Export To Excel" onclick="exportdata('keywords3','<?php echo "topic_math_".$studentDetail[0]->first_name . "_" . $studentDetail[0]->last_name; ?>')"/>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="table-scrollable">
                                                        <table id="keywords3" class="table table-striped table-bordered table-hover dataTable no-footer">
                                                            <colgroup>
                                                                <col width="16.6%">
                                                                <col width="16.6%">
                                                                <col width="16.6%">
                                                                <col width="16.6%">
                                                                <col width="16.6%">
                                                            </colgroup>
                                                            <thead>
                                                                <tr class="heading" role="row">
                                                                    <th class="text-center" rowspan="1" colspan="1">Attempted</th>
                                                                    <th class="text-center <?php echo ($sortby == 'asc')?'sorting_asc':'sorting_desc'; ?> sorting_asc" onclick="sortgrid('<?php echo $studentId ?>', 'topic', 'math','<?php echo ($sortby == 'asc')?'desc':'asc'; ?>')" rowspan="1" colspan="1">Topic Name</th>
                                                                    <!--<th class="text-center" rowspan="1" colspan="1">status</th>-->
                                                                    <th class="text-center" rowspan="1" colspan="1">No. Attempts</th>
                                                                    <th class="text-center" rowspan="1" colspan="1">No. of Questions Attempted </th>
                                                                    <!--<th class="text-center" rowspan="1" colspan="1">Usage</th>-->
                                                                    <th class="text-center" rowspan="1" colspan="1">Topic Performance</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                if (count($reportResult) > 0) {

                                                                    foreach ($reportResult as $key => $val) {
                                                                        $attampted = '<span class="glyphicon glyphicon-remove" style="color:red;"></span>';
                                                                        $perMarks = 0;
                                                                        $perMarks = number_format($val->strand_mark_obtain * 100 / $val->total_marks, 2, '.', '');
                                                                        
                                                                        $attemptCounts = 0;
                                                                        $totalSubStrands = count($val->subResult);
                                                                        $substandsArray = array();
                                                                        if (!empty($val->substrand_aggrigate)) {
                                                                            $substandsArray = unserialize($val->substrand_aggrigate);
                                                                            //asd($substandsArray);
                                                                            $attamptedSubStands = $attemptCounts = count($substandsArray);
                                                                            if ($attamptedSubStands > 0) {
                                                                                $attampted = '<span class="glyphicon glyphicon-ok" style="color:green;"></span>';
                                                                            }
                                                                        }

                                                                        $time = 0;
                                                                        $time = number_format((($val->quesmaxtime - $val->remainingtime) / 60), 2, '.', '');
                                                                       
                                                                        if (!empty($substandsArray)) {
                                                                            $totalMarks = 0;
                                                                            $obtainedMarks = 0;
                                                                            foreach ($substandsArray as $k => $v) {
                                                                                $totalMarks += $v[0];
                                                                                $obtainedMarks += $v[1];
                                                                            }
                                                                            $perceMarks = ($obtainedMarks * 100) / $totalMarks;
                                                                            //$graph['Math'][] = $perceMarks;
                                                                        }
                                                                        
                                                                        $subMarks = 0;
                                                                        if (count($val->subResult) > 0) {
                                                                            foreach ($val->subResult as $res => $row) {
                                                                                if(isset($substandsArray[$row->id])){
                                                                                $subStRes = $substandsArray[$row->id];
                                                                                $subMarks+= (number_format(($subStRes[1] * 100) / $subStRes[0],2,'.',''));
                                                                                }
                                                                            }
                                                                            $perceMarks =  $subMarks/count($val->subResult);
                                                                            //$graph['English'][] = round($perceMarks);
                                                                        }
                                                                        
                                                                        
                                                                        
                                                                        
                                                                        if ($perceMarks >= 70) {
                                                                            $status = '<span style=" background: green;border-radius: 50% !important;display: inline-block;height: 40px;line-height: 40px;text-align: center; width: 40px;"   title="70%-100%">'.round($perceMarks).'%</span>';
                                                                        } elseif ($perceMarks >= 50 && $perceMarks < 70) {
                                                                            $status = '<span style=" background: yellow;border-radius: 50% !important;display: inline-block;height: 40px;line-height: 40px;text-align: center; width: 40px;"  title="50%-69%">'.round($perceMarks).'%</span>';
                                                                        } else {
                                                                            $status = '<span style=" background: red;border-radius: 50% !important;display: inline-block;height: 40px;line-height: 40px;text-align: center; width: 40px;"  title="0-49%">'.round($perceMarks).'%</span>';
                                                                        }
                                                                        
                                                                        
                                                                        ?>
                                                                        <tr role="row">
                                                                            <td id="tr<?php echo $val->taskId; ?>" onclick="expendrowrev('<?php echo $val->taskId; ?>')" class="down text-center"><?php echo $attampted; ?></td>
                                                                            <td class=" text-center"><?php echo $val->strand; ?></td>
                                                                            <!--<td class=" text-center"><?php //echo $status; ?></td>-->
                                                                            <td class=" text-center"><?php echo $val->attempt_count //echo $attamptedSubStands; ?></td>
                                                                            <td class=" text-center"><?php echo $val->num_answered; ?></td>
                                                                           <!-- <td class=" text-center"><?php //echo $time; ?></td>-->
                                                                            <td class=" text-center"><?php $subMarks = number_format($perceMarks, 2, '.', ''); 
                                                                            
                                                                            $barColor = 'zero';
                                                                                                            /*if ($subMarks > 0 && $subMarks < 30)
                                                                                                                $barColor = 'red';
                                                                                                            else if ($subMarks >= 30 && $subMarks < 60)
                                                                                                                $barColor = 'yellow';
                                                                                                            else if ($subMarks >= 60)
                                                                                                                $barColor = 'green';*/
                                                                                                            
                                                                                                            
                                                                                                           $subMarks = round($subMarks);                                                                                                         $subMarks = round($subMarks);
                                                                                                        if ($subMarks > 0 && $subMarks <=20)
                                                                                                            $barColor = 'red';
                                                                                                        else if ($subMarks >= 21 && $subMarks <= 50)
                                                                                                            $barColor = 'yellow';
                                                                                                        else if ($subMarks >= 51 && $subMarks <= 70)
                                                                                                            $barColor = 'green';
                                                                                                        else if ($subMarks >70)
                                                                                                            $barColor = 'blue';
                                                                                                                
                                                                                                            $full = "";
                                                                                                            if($subMarks>99){
                                                                                                                $full = "full";
                                                                                                            }
                                                                                                            if($subMarks >100){
                                                                                                                $subMarksper = 100;
                                                                                                            }else if($subMarks < 3 && $subMarks > 0){
                                                                                                                $subMarksper = 3;
                                                                                                            }else{
                                                                                                                $subMarksper = $subMarks;
                                                                                                            }    
                                                                                                            ?>
                                                                                                            <div class="progress_bar">
                                                                                                                <div class="bar <?php echo $barColor ?> <?php echo $full; ?>" style="width:<?php echo $subMarksper ?>%">&nbsp;</div>
                                                                                                                <span><?php echo round($subMarks) ?>%</span>
                                                                                                            </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr role="row" style="display:none;" id="<?php echo "subGridrev" . $val->taskId; ?>">
                                                                            <td colspan="7">
                                                                                <table class="noExl table table-striped table-bordered table-hover dataTable no-footer">
                                                                                    <colgroup>
                                                                                        <col width="16.6%">
                                                                                        <col width="16.6%">
                                                                                        <col width="16.6%">
                                                                                        <col width="16.6%">
                                                                                        <col width="16.6%">
                                                                                    </colgroup>
                                                                                    <tbody>
                                                                                         <tr class="heading" role="row">
                                                                                            <th class="text-center" rowspan="1" colspan="1">Attempted</th>
                                                                                            <th class="text-center" rowspan="1" colspan="1">Topic Name</th>
                                                                                            <!--<th class="text-center" rowspan="1" colspan="1">status</th>-->
                                                                                            <th class="text-center" rowspan="1" colspan="1">No. Attempts</th>
                                                                                            <th class="text-center" rowspan="1" colspan="1">No. of Questions Attempted </th>
                                                                                           <!-- <th class="text-center" rowspan="1" colspan="1">Usage</th>-->
                                                                                            <th class="text-center" rowspan="1" colspan="1">Topic Performance</th>
                                                                                        </tr>
                                                                                        <?php
                                                                                        
                                                                                        if (count($val->subResult) > 0) {
                                                                                            foreach ($val->subResult as $res => $row) {

                                                                                                $subStRes = @$substandsArray[@$row->id];
                                                                                                $subMarks = 0;
                                                                                                if(!empty($subStRes[0]))
                                                                                                    $subMarks = (number_format(($subStRes[1] * 100) / $subStRes[0],2,'.',''));
                                                                                                
                                                                                                if ($subMarks >= 70) {
                                                                                                    $status1 = '<span style=" background: green;border-radius: 50% !important;display: inline-block;height: 40px;line-height: 40px;text-align: center; width: 40px;" title="70%-100%">'.round($subMarks).'%</span>';
                                                                                                } elseif ($subMarks >= 50 && $subMarks < 70) {
                                                                                                    $status1 = '<span style=" background: yellow;border-radius: 50% !important;display: inline-block;height: 40px;line-height: 40px;text-align: center; width: 40px;"  title="69%-50">'.round($subMarks).'%</span>';
                                                                                                } else {
                                                                                                    $status1 = '<span style=" background: red;border-radius: 50% !important;display: inline-block;height: 40px;line-height: 40px;text-align: center; width: 40px;"  title="0-49%">'.round($subMarks).'%</span>';
                                                                                                }
                                                                                                ?>
                                                                                                <tr role="row" class="noExl">

                                                                                                    <td class=" text-center"></td>
                                                                                                    <td class=" text-center"><?php echo $row->strand; ?></td>
                                                                                                    <!--<td class=" text-center"><?php //echo $status1; ?></td>-->
                                                                                                    <td class=" text-center"><?php echo $row->attempt_count; ?></td>
                                                                                                   <!-- <td class=" text-center">NA</td> -->
                                                                                                    <td class=" text-center"><?php echo $row->num_answered; ?></td>
                                                                                                    <td class=" text-center">
                                                                                                        
                                                                                                        <?php //echo $subMarks; 
                                                                                                        $barColor = 'zero';
                                                                                                                                                                                                                $subMarks = round($subMarks);
                                                                                                        $subMarks = round($subMarks);
                                                                                                        if ($subMarks > 0 && $subMarks <=20)
                                                                                                            $barColor = 'red';
                                                                                                        else if ($subMarks >= 21 && $subMarks <= 50)
                                                                                                            $barColor = 'yellow';
                                                                                                        else if ($subMarks >= 51 && $subMarks <= 70)
                                                                                                            $barColor = 'green';
                                                                                                        else if ($subMarks >70)
                                                                                                            $barColor = 'blue';
                                                                                                        
                                                                                                        
                                                                                                            /*if ($subMarks > 0 && $subMarks < 30)
                                                                                                                $barColor = 'red';
                                                                                                            else if ($subMarks >= 30 && $subMarks < 60)
                                                                                                                $barColor = 'yellow';
                                                                                                            else if ($subMarks >= 60)
                                                                                                                $barColor = 'green';*/
                                                                                                            
                                                                                                            $full = "";
                                                                                                            if($subMarks>98){
                                                                                                                $full = 'full';
                                                                                                            }
                                                                                                            if($subMarks >100){
                                                                                                                $submarkper = 100;
                                                                                                            }else if($subMarks < 3 && $subMarks > 0){
                                                                                                                   $submarkper = 3;
                                                                                                            }else{
                                                                                                                $submarkper = $subMarks;
                                                                                                            }    
                                                                                                            ?>
                                                                                                            <div class="progress_bar">
                                                                                                                <div class="bar <?php echo $barColor ?> <?php echo $full; ?>" style="width:<?php echo $submarkper ?>%">&nbsp;</div>
                                                                                                                <span><?php echo  round($subMarks) ?>%</span>
                                                                                                            </div>
                                                                                                        </td>


                                                                                                </tr>
                                                                                                <?php
                                                                                            }
                                                                                        }
                                                                                        ?> 
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>

                                                                        <?php
                                                                    }
                                                                } else {
                                                                    ?>
                                                                    <tr role="row" class="even">
                                                                        <td Colspan="7" class=" text-center">No Result Found For this Pupil</td>
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
</div><?php

?>
<form name="graphexport" action="/adminreport/studentgraph" method="post" id="graphexport">
    <input type="hidden" name="graphdata" id="graphdata" value=""/>
</form>
<!-- END PAGE CONTENT-->
@stop
@section('pagescripts')
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['bar']}]}"></script>
{!! HTML::script('js/jquery.tablesorter.min.js') !!}
{!! HTML::script('js/jquery.table2excel.js') !!}
<script>
function exportdata(id,name){
    $("#"+id).table2excel({
        exclude: ".noExl",
        name: "Excel Document Name",
        filename: name,
    }); 

}
function sortgrid(id, report, tab, sortby){
    var layout = '<?php echo $layout ?>'; 
    window.location.href = "/adminreport/studenttest?id=" + id + "&report=" + report + "&tab=" + tab +"&sortby="+sortby+"&layout="+layout;
}
// $(function(){
  // $('#keywords').tablesorter(); 
  // $('#keywords1').tablesorter(); 
  // $('#keywords2').tablesorter(); 
  // $('#keywords3').tablesorter(); 
// });

                                                                                    var graphData = jQuery.parseJSON('<?php echo json_encode($graph); ?>');
                                                                            function expendrowrev(id) {
                                                                                $('#subGridrev' + id).toggle();
                                                                                 $('#tr' + id).toggleClass("down up");
                                                                            }
                                                                            function expendrow(id) {
                                                                                $('#subGrid' + id).toggle();
                                                                                $('#tr' + id).toggleClass("down up");
                                                                            }
                                                                            function getreport(id, report, tab) {
                                                                                var layout = '<?php echo $layout ?>'; 
                                                                                window.location.href = "/adminreport/studenttest?id=" + id + "&report=" + report + "&tab=" + tab +"&layout="+layout;
                                                                            }
                                                                            var dataArray = [];
                                                                            dataArray.push(['', 'English', 'Math']);
                                                                            var mathLenght = graphData.Math.length;
                                                                            if(graphData.English)
                                                                                var englishLenght = graphData.English.length;
                                                                            var loopLength = mathLenght > englishLenght ? mathLenght : englishLenght;
                                                                            loopLength = loopLength > 20 ? loopLength : 20;
                                                                            for (var i = 0; i < loopLength; i++) {
                                                                                dataArray.push([i + 1, 0, 0]);
                                                                            }

                                                                            $.each(graphData.English, function (i, v) {
                                                                                //dataArray[i+1][0] = i + 1;
                                                                                //dataArray[i + 1][1] = (graphData.English[i].MarksObtain/graphData.English[i].totalMarks)*100;
                                                                                dataArray[i + 1][1] = graphData.English[i];

                                                                            });
                                                                            $.each(graphData.Math, function (i, v) {
                                                                                //dataArray[i+1][0] = i + 1;
                                                                                //dataArray[i + 1][2] = (graphData.Math[i].MarksObtain/graphData.Math[i].totalMarks)*100;
                                                                                dataArray[i + 1][2] = graphData.Math[i];

                                                                            });
                                                                            var testtype = '<?php echo ucfirst($testtype) ?>';
                                                                            if(testtype == 'Topic')
                                                                                testtype = 'Revision';
                                                                            
                                                                            testtype = 'Test';
                                                                            google.setOnLoadCallback(drawStuff(testtype));

                                                                            function drawStuff(testtype) {

                                                                                var data = new google.visualization.arrayToDataTable(dataArray);
                                                                                // [
                                                                                // ['', 'English', 'Math'],
                                                                                // ['1', 8000, 23.3],
                                                                                // ['2', 24000, 4.5],
                                                                                // ['3', 30000, 14.3],
                                                                                // ['4', 50000, 0.9],
                                                                                // ['5', 60000, 13.1]
                                                                                // ]
                                                                                var options = {
                                                                                    width:'80%',
                                                                                    height:'300',
                                                                                    vAxis: {title:'Percentage %'},
                                                                                    hAxis: {title:testtype},
                                                                                    chart: {
                                                                                        title: 'Progress',
                                                                                        subtitle: 'Student progress chart'
                                                                                    },
                                                                                    bars: 'verticals', // Required for Material Bar Charts.

                                                                                };

                                                                                var chart = new google.charts.Bar(document.getElementById('dual_x_div'));
                                                                                chart.draw(data, google.charts.Bar.convertOptions(options));
                                                                            };
                                                                            
    $('#exportgraph').click(function(){
        $('#graphdata').val(JSON.stringify(dataArray));
        $('#graphdata').serializeArray()
        $('#graphexport').submit();
    })
</script>
@stop
