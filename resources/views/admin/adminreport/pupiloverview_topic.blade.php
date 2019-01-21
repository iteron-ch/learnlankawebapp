@extends('admin.adminreport.pupiloverview') 

@section('report_topic')
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
                                                        $status = '<span style=" background: orange;border-radius: 50% !important;display: inline-block;height: 40px;line-height: 40px;text-align: center; width: 40px;"   title="69%-50%">'.round($perceMarks).'%</span>';
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
                                                                                        $barColor = 'orange';
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
                                                                                $status1 = '<span style=" background: orange;border-radius: 50% !important;display: inline-block;height: 40px;line-height: 40px;text-align: center; width: 40px;"  title="69%-50%">'.round($subMarks).'%</span>';
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
                                                                                            $barColor = 'orange';
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
                                                        $status = '<span style=" background: orange;border-radius: 50% !important;display: inline-block;height: 40px;line-height: 40px;text-align: center; width: 40px;"  title="50%-69%">'.round($perceMarks).'%</span>';
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
                                                                                        $barColor = 'orange';
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
                                                                                $status1 = '<span style=" background: orange;border-radius: 50% !important;display: inline-block;height: 40px;line-height: 40px;text-align: center; width: 40px;"  title="69%-50">'.round($subMarks).'%</span>';
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
                                                                                        $barColor = 'orange';
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
@stop

