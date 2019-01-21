@extends('admin.adminreport.pupiloverview') 

@section('report_test')
<div class="tab-pane fade  @if($testtype == 'test') active in @endif" id="tab_1_1">
    <div style="float:right;margin:15px 16px 0 0 ;"><a href="#" onclick="window.print()">Print Report</a></div>
    <ul class="nav nav-tabs">
        <li>
            <a href="#tab_2_1" data-toggle="tab">
                <span >English</span>
            </a></li>
        <li class="active">
            <a href="#tab_2_2" data-toggle="tab">
                <span>Math</span>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <?php $arrTabs = array(
            1 => 'english',
            2 => 'math'
        );
        foreach($arrTabs as $key => $tab){
            $tabId = 'tab_2_'.$key;
            if($tab == 'math'){
                $reportData = $reportResult['reportResultMath'];
                $active = 'active';
                $baseLine = round($studentDetail[0]->ks2_maths_baseline_value);
            }else{
                $reportData = $reportResult['reportResultEnglish'];
                $active = '';
                $baseLine = round($studentDetail[0]->ks2_english_baseline_value);
            }
            ?>
            <div class="tab-pane <?php echo $active?>" id="<?php echo $tabId?>">
                <div class="table-scrollable">
                    <div class="row " style="margin:15px 0 0">
                        <div class="col-md-12">
                            <div class="portlet box red">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <input type="button" class="btn default" name="export" value="Export To Excel" onclick="exportdata('keywords_<?php echo $tab?>','<?php echo "test_".$tab."_".$studentDetail[0]->first_name . "_" . $studentDetail[0]->last_name; ?>')"/>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="table-scrollable">
                                        <table id="keywords_<?php echo $tab?>" class="table table-striped table-bordered table-hover dataTable no-footer">
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
                                                    <th class="text-center">Test Name</th>
                                                    <th class="text-center">Attempt 1</th>
                                                    <th class="text-center">Attempt 2</th>
                                                    <th class="text-center">Attempt 3</th>
                                                    <th class="text-center">Average Score</th>
                                                    <th class="text-center">Last Assessment Date</th>
                                                    <th class="text-center">Timeliness (mins)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($reportData['gridData'] as $rKey => $rRow){
                                                $record = $rRow['record'];
                                                $recordDetail = $rRow['record_detail'];
                                            ?>
                                            <tr role="row">
                                                <td id="tr_<?php echo $tab.'_'.$rKey?>" onclick="expendrow('<?php echo $tab?>','<?php echo $rKey?>')" class="down text-center" class=" text-center"><?php echo $record['test_name']?></td>
                                                <td class=" text-center">
                                                    <?php if(!$record['attempt_1']){?>
                                                            NA
                                                    <?php } else { ?>
                                                        <div class="progress_bar">
                                                            <div class="bar <?php echo $record['attempt_1']['color']?> <?php echo $record['attempt_1']['full']?>" style="width:<?php echo $record['attempt_1']['percent']?>%">&nbsp;</div>
                                                                <span><?php echo $record['attempt_1']['label']?>%</span>
                                                        </div>
                                                    <?php } ?>
                                                </td>
                                                <td class=" text-center">
                                                    <?php if(!$record['attempt_2']){?>
                                                            NA
                                                    <?php } else { ?>
                                                        <div class="progress_bar">
                                                            <div class="bar <?php echo $record['attempt_2']['color']?> <?php echo $record['attempt_2']['full']?>" style="width:<?php echo $record['attempt_2']['percent']?>%">&nbsp;</div>
                                                                <span><?php echo $record['attempt_2']['label']?>%</span>
                                                        </div>
                                                    <?php } ?>
                                                </td>
                                                <td class=" text-center">
                                                    <?php if(!$record['attempt_3']){?>
                                                            NA
                                                    <?php } else { ?>
                                                        <div class="progress_bar">
                                                            <div class="bar <?php echo $record['attempt_3']['color']?> <?php echo $record['attempt_3']['full']?>" style="width:<?php echo $record['attempt_3']['percent']?>%">&nbsp;</div>
                                                                <span><?php echo $record['attempt_3']['label']?>%</span>
                                                        </div>
                                                    <?php } ?>
                                                </td>
                                                <td class=" text-center">
                                                    <?php if(!$record['avg_score']){?>
                                                            NA
                                                    <?php } else { ?>
                                                        <div class="progress_bar">
                                                            <div class="bar <?php echo $record['avg_score']['color']?> <?php echo $record['avg_score']['full']?>" style="width:<?php echo $record['avg_score']['percent']?>%">&nbsp;</div>
                                                                <span><?php echo $record['avg_score']['label']?>%</span>
                                                        </div>
                                                    <?php } ?>
                                                </td>
                                                <td class=" text-center"><?php echo $record['last_assessementdate']?></td>
                                                <td class=" text-center">
                                                    <div class="progress_bar">
                                                        <div class="bar <?php echo $record['timeliness ']['color']?> <?php echo $record['timeliness ']['full']?>" style="width:<?php echo $record['timeliness ']['percent']?>%">&nbsp;</div>
                                                            <span><?php echo $record['timeliness ']['label']?></span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr role="row" style="display: none;" id="<?php echo "subGrid_" .$tab.'_'.$rKey?>">
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
                                                        <thead>
                                                            <tr class="heading" role="row">
                                                                <th class="text-center" rowspan="1" colspan="1">Paper Name</th>
                                                                <th class="text-center" rowspan="1" colspan="1">Attempt 1</th>
                                                                <th class="text-center" rowspan="1" colspan="1">Attempt 2</th>
                                                                <th class="text-center" rowspan="1" colspan="1">Attempt 3</th>
                                                                <th class="text-center" rowspan="1" colspan="1">Average Score</th>
                                                                <th class="text-center" rowspan="1" colspan="1">Last Assessment Date</th>
                                                                <th class="text-center" rowspan="1" colspan="1">Timeliness (mins)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach($recordDetail as $rdKey => $rdRow){?>
                                                                <tr role="row" class="noExl">
                                                                    <td class=" text-center"><?php echo $rdRow['paper_name']?></td>
                                                                    <td class=" text-center">
                                                                    <?php if(!$rdRow['attempt_1']){?>
                                                                            NA
                                                                    <?php } else { ?>
                                                                        <div class="progress_bar">
                                                                            <div class="bar <?php echo $rdRow['attempt_1']['color']?> <?php echo $rdRow['attempt_1']['full']?>" style="width:<?php echo $rdRow['attempt_1']['percent']?>%">&nbsp;</div>
                                                                                <span><?php echo $rdRow['attempt_1']['label']?>%</span>
                                                                        </div>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class=" text-center">
                                                                    <?php if(!$rdRow['attempt_2']){?>
                                                                            NA
                                                                    <?php } else { ?>
                                                                        <div class="progress_bar">
                                                                            <div class="bar <?php echo $rdRow['attempt_2']['color']?> <?php echo $rdRow['attempt_2']['full']?>" style="width:<?php echo $rdRow['attempt_2']['percent']?>%">&nbsp;</div>
                                                                                <span><?php echo $rdRow['attempt_2']['label']?>%</span>
                                                                        </div>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class=" text-center">
                                                                    <?php if(!$rdRow['attempt_3']){?>
                                                                            NA
                                                                    <?php } else { ?>
                                                                        <div class="progress_bar">
                                                                            <div class="bar <?php echo $rdRow['attempt_3']['color']?> <?php echo $rdRow['attempt_3']['full']?>" style="width:<?php echo $rdRow['attempt_3']['percent']?>%">&nbsp;</div>
                                                                                <span><?php echo $rdRow['attempt_3']['label']?>%</span>
                                                                        </div>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class=" text-center">
                                                                    <?php if(!$rdRow['avg_score']){?>
                                                                            NA
                                                                    <?php } else { ?>
                                                                        <div class="progress_bar">
                                                                            <div class="bar <?php echo $rdRow['avg_score']['color']?> <?php echo $rdRow['avg_score']['full']?>" style="width:<?php echo $rdRow['avg_score']['percent']?>%">&nbsp;</div>
                                                                                <span><?php echo $rdRow['avg_score']['label']?>%</span>
                                                                        </div>
                                                                    <?php } ?>
                                                                </td>
                                                                <td class=" text-center"><?php echo $rdRow['last_assessementdate']?></td>
                                                                <td class=" text-center">
                                                                    <div class="progress_bar">
                                                                        <div class="bar <?php echo $rdRow['timeliness ']['color']?> <?php echo $rdRow['timeliness ']['full']?>" style="width:<?php echo $rdRow['timeliness ']['percent']?>%">&nbsp;</div>
                                                                            <span><?php echo $rdRow['timeliness ']['label']?></span>
                                                                    </div>
                                                                </td>   
                                                                </tr>
                                                            <?php } ?>
                                                            
                                                        </tbody>
                                                    </table>
                                                </td>
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
                        <strong>BASELINE:</strong> <?php echo $baseLine ?>% &nbsp;&nbsp;  
                        <strong>HIGHEST SCORE:</strong> <?php echo $reportData['metaData']['heightScore']?>% &nbsp;&nbsp;  
                        <strong>LOWEST SCORE:</strong> <?php echo $reportData['metaData']['lowestScore']?>%  &nbsp;&nbsp;  
                        <strong>AVERAGE SCORE:</strong>  <?php echo $reportData['metaData']['avgScore']?>% &nbsp;&nbsp;  
                    </div>
                </div>

            </div>
        <?php }   ?>
        
    </div>
    </div>
@stop

