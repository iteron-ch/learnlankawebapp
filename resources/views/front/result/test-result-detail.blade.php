@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.studenttask.leftbar-test', ['subject' => $viewData['subject']]) 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid pt40">
            <h1>
                <a href="{{ route('studenttask.testsubject',$viewData['subject']) }}" class="back">&nbsp;</a>
                <div class="heading">Test Result</div>
            </h1>

            <div class="revision_result">
                <h2 class="rr_title">
                    {{ $viewData['set_name'] }}
                    <span>{{ $viewData['paper_name'] }} > {{ $viewData['attempt_no'] }}</span>
                </h2>

                <div class="timer {{ $viewData['over_time'] ? 'trClock': 'tgClock' }}">
                    <div class="tValue sRed">{{ $viewData['time_spent'] }}</div>
                    <div class="tUnit">mins</div>
                </div>

                <div class="que_content">
                    <strong>Question Breakdown</strong>
                    <div class="number row">{!! $viewData['htmQusseries'] !!}</div>
                    <div class="indicator">
                        <ul>
                            <li class="iAns"><span class="greenCir"></span> <span>Right Answers</span></li>
                            <li class="iAns"><span class="redCir"></span> <span>Wrong Answers</span></li>
                        </ul>
                    </div>
                </div>
                <?php
                // $attemptAnswerAggregate[133] = array('total_marks'=>'10','marks_received'=>0,'strand'=>'Combining words,phrases and clauses','strand_id'=>'133');
                $zeroMarksReceivedStrands = array();
                if (!empty($attemptAnswerAggregate)) {
                    //asd($attemptAnswerAggregate);
                    foreach ($attemptAnswerAggregate as $key2 => $val2) {
                        if ($val2['marks_received'] == 0)
                            $zeroMarksReceivedStrands[$val2['strands_id']][$val2['substrands_id']] = $val2['substrand'];
                    }
                }

                //asd($zeroMarksReceivedStrands);
                
                $labelClassName = '';
                $bgClassName = '';
                $msg = '';                
                $viewData['percentage_obtained'] = round($viewData['percentage_obtained']);
                if ($viewData['percentage_obtained'] <= 20) {
                    $labelClassName = 'below20';
                    $bgClassName = 'below20_bb';
                    $msg = "Keep practicing, there is room for improvement. Have you tried watching some of our video lessons?";
                } elseif ($viewData['percentage_obtained'] > 20 && $viewData['percentage_obtained'] <= 50) {
                    $labelClassName = 'below50';
                    $bgClassName = 'below50_bb';
                    $msg = "Good try, but you need more practice. Come back and try again.";
                } elseif ($viewData['percentage_obtained'] > 50 && $viewData['percentage_obtained'] <= 69) {
                    $labelClassName = 'below70';
                    $bgClassName = 'below70_bb';
                    $msg = "Great attempt, you're almost there! Keep up the hard work!";
                } elseif ($viewData['percentage_obtained'] >= 70) {
                    $labelClassName = 'below100';
                    $bgClassName = 'below100_bb';
                    $msg = "Awesome! Remember, practice makes perfect!";
                }
                ?>
                <div class="score_detail">
                    <div class="sNum"><span>{{ $viewData['mark_obtained'] }}/{{ $viewData['total_marks'] }}</span> Your Score</div>
                    <div class="sPer {{ $labelClassName }}">{{ round($viewData['percentage_obtained']) }}%</div>
                </div>
                <div class="result_msg {{ $bgClassName }}">
                    <p class="font22">{!! $msg !!}</p>
                </div>
                
                    <?php
                    $zeroMarksReceivedStrands = array_slice($zeroMarksReceivedStrands, 0, 3, true);
                    
                    if (!empty($zeroMarksReceivedStrands)) {
                        echo '<div class="video_block">';
                        
                        foreach ($zeroMarksReceivedStrands as $kk => $vv) {
                            //$zeroMarksReceivedSubStrands = array_slice($vv, 0, 3, true);
                            foreach($vv as $k2=>$v2) {
                            ?>
                                <p style="padding-top: 3px;padding-bottom: 4px;">Click here for a video lesson on this <a href="/help-centre/<?php echo $viewData['subject']?>/<?php echo encryptParam($kk) ?>"><?php echo $v2 ?></a> 
                                    <span class="y_arrow">{!! HTML::image('images/yellow_arrow.png') !!}</span></p>
                            <?php
                            
                            }
                        }
                        echo '</div>';
                        
                    }
                    ?>
                <div class="question_details">
                    <span>Total Questions - {{ $viewData['num_total_qus'] }}</span>
                    <span>Date - {{ $viewData['attempt_date'] }}</span>
                </div>
            </div>
        </div>
        <div class="rbot">&nbsp;</div>
    </div>
    <div class="clear_fix"></div>
</div>
</div>
<!-- END PAGE CONTENT-->
@stop
@section('pagescripts')
{!! HTML::style('css/colorbox.css') !!}
<style>
    #cboxOverlay{ background:#666666; }
</style>
<script>
    function opencolorbox(id) {
        $.colorbox({fastIframe: false, width: "800px", height: "650px", transition: "fade", scrolling: false, iframe: true, href: "/help-view/" + id});
    }
</script>   
@stop