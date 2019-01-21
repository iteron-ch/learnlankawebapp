@extends('admin.adminreport.schooloverview') 

@section('report_test')
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
                                                                    <th class="text-center" rowspan="1" colspan="1">Baseline</th>
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
                                                                    <th class="text-center" rowspan="1" colspan="1">Baseline</th>
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
@stop