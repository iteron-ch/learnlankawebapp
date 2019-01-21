@extends('admin.adminreport.schooloverview') 

@section('report_topic')
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
                                                    <!--<option value="">Select Strands</option>-->
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
                                                    <!--<option value="">Select Substrand</option>-->
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
                                                                                    //$tim =  round($val->avg_time/$val->total_record_cnt);
                                                                                    $tim =  round($val->avg_time);
                                                                                    $tim = $tim/60;
                                                                                }
                                                                                if(!empty($val->total_record_cnt)) {
                                                                                    //$avg = round($val->avg_marks/$val->total_record_cnt).' %';
                                                                                    $avg = round($val->avg_marks).' %';
                                                                                }
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
                                                    <!--<option value="">Select Strands</option>-->
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
                                                    <!--<option value="">Select Substrand</option>-->
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
                                                                                    //$tim =  round($val->avg_time/$val->total_record_cnt);
                                                                                    $tim =  round($val->avg_time);
                                                                                    $tim = $tim/60;
                                                                                }
                                                                                if(!empty($val->total_record_cnt)) {
                                                                                  //  $avg = round($val->avg_marks/$val->total_record_cnt).' %';
                                                                                    $avg = round($val->avg_marks).' %';
                                                                                }
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
@stop