@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.studenttask.leftbar-test', ['subject' => $viewData['subject']]) 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid pt40">
            <h1>
                <a href="javascript:void(0)" class="back">&nbsp;</a>
                <div class="heading">Test Result</div>
            </h1>
            <div class="revision_result">
                <h2 class="rr_title">
                    {{ $viewData['set_name'] }}
                    <span>{{ $viewData['paper_name'] }} > {{ $viewData['attempt_no'] }}</span>
                </h2>
                <div class="answer_block mtb20">
                    <div class="aHalf">
                        <h3 class="atitle">&nbsp;</h3>
                        <div class="aCorrect_ans">
                            <div class="check_icon">&nbsp;</div>
                            <p>Correct Answers</p>
                            <span>{{ $viewData['num_correct_ans'] }}</span>
                        </div>

                        <div class="aInCorrect_ans">
                            <div class="wrong_icon">&nbsp;</div>
                            <p>Wrong Answers</p>
                            <span>{{ $viewData['num_wrong_ans'] }}</span>
                            <a href="{{ route('result.testresultdetail',encryptParam($viewData['attempt_id'])) }}" class="view">View</a>
                        </div>
                    </div>
                </div>
                <?php
                
                $labelClassName = '';
                $bgClassName = '';
                $msg = '';                
                $viewData['percentage_obtained'] = round($viewData['percentage_obtained']);                                
                
                if ($viewData['percentage_obtained'] < 20) {
                    $labelClassName = 'below20';
                    $bgClassName = 'below20_bb';
                    $msg = "We need to work on this topic some more. Have you tried watching some of our video lessons?";
                } elseif ($viewData['percentage_obtained'] >= 20 && $viewData['percentage_obtained'] < 70) {
                    $labelClassName = 'below70';
                    $bgClassName = 'below70_bb';
                    $msg = "Awesome!<br>You understand this topic. Remember, practice makes perfect!";
                } elseif ($viewData['percentage_obtained'] >= 70) {
                    $labelClassName = 'below70';
                    $bgClassName = 'below70_bb';
                    $msg = "Awesome!<br>You understand this topic. Remember, practice makes perfect!";
                }
                ?>
                <div class="percent_score {{ $labelClassName }}">
                    <p>{{ $viewData['student_name'] }} you scored</p>
                    <span>{{ $viewData['percentage_obtained'] }}%</span>
                </div>
                <div class="result_msg {{ $bgClassName }}">
                    <p>{!! $msg !!}</p>
                </div>

                <div class="video_block">
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