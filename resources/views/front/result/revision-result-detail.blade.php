@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.studenttask.leftbar-revision', ['subject' => $viewData['subject']]) 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid pt40">
            <h1>
                <a href="{{ route('studenttask.revisionsubject',$viewData['subject']) }}" class="back">&nbsp;</a>
                <div class="heading">Revision Result</div>
            </h1>

            <div class="revision_result">
                <h2 class="rr_title">
                    {{ $viewData['strand'] }}
                    <span>{{ $viewData['substrand'] }}</span>
                </h2>

                <div class="timer {{ $viewData['over_time'] ? 'trClock': 'tgClock' }}">
                    <div class="tValue">{{ $viewData['time_spent'] }}</div>
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
                
                $labelClassName = '';
                $bgClassName = '';
                $msg = '';                
                $viewData['percentage_obtained'] = round($viewData['percentage_obtained']);                
                
                if ($viewData['percentage_obtained'] <= 20) {
                    $labelClassName = 'below20';
                    $bgClassName = 'below20_bb';
                    $msg = "You need to work on this topic some more. Have you tried watching some of our video lessons?";
                 } elseif ($viewData['percentage_obtained'] > 20 && $viewData['percentage_obtained'] <= 50) {
                    $labelClassName = 'below50';
                    $bgClassName = 'below50_bb';
                    $msg = "Good try, but this topic needs more practice. Come back and try again";
                } elseif ($viewData['percentage_obtained'] > 50 && $viewData['percentage_obtained'] <= 69) {
                    $labelClassName = 'below70';
                    $bgClassName = 'below70_bb';
                    $msg = "Great attempt, you're almost there! Keep up the hard work!";
                } elseif ($viewData['percentage_obtained'] >= 70) {
                    $labelClassName = 'below100';
                    $bgClassName = 'below100_bb';
                    $msg = "Awesome! You understand this topic. Remember, practice makes perfect!";
                }
                ?>
                <div class="score_detail">
                    <div class="sNum"><span>{{ $viewData['mark_obtained'] }}/{{ $viewData['total_marks'] }}</span> Your Score</div>
                    <div class="sPer {{ $labelClassName }}">{{ round($viewData['percentage_obtained']) }}%</div>
                </div>

                <div class="result_msg {{ $bgClassName }}">
                    <p class="font22">{!! $msg !!}</p>
                </div>

                <div class="video_block" onclick="location.href='{{ route('helpcentre.subject',$viewData["subject"]) }}#{{ $viewData["strand_id"] }}'">
                    <p>Click here for a video lesson on this topic</p>
                    <span class="y_arrow">{!! HTML::image('images/yellow_arrow.png') !!}</span>
                </div>

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