@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.studenttask.leftbar-test', ['subject' => $subject]) 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid pt40">
             <h1>
                <a href="{{ $trait['back_link'] }}" class="back">&nbsp;</a>
                <div class="heading root_sign breadcrumb_light">{{ $trait['trait_1'] }} > </div>
                <div class="bread_dark">{{ $trait['trait_2'] }}</div>
                <div class="bread_dark">{{ $trait['trait_3'] }}</div>
            </h1>
            <div id="content-l" class="revision_content">
                <div class="rev_ques">
                    <ul>
                        <li class="revSelection revSelection_detail">
                            <div class="rev_deatils">
                                <div class="detail_li">Subject - <span>{{ ucfirst($subject) }}</span></div>
                                <div class="detail_li">Assigned By - <span>{{ trim($studentTestPaperDetail['first_name'].' '.$studentTestPaperDetail['last_name'])}}</span></div>
                                <div class="detail_li">Total Questions - <span>{{ $studentTestPaperDetail['num_question'] }}</span></div>
                            </div>
                        </li>
                    </ul>
                    <div class="rev_instruction">
                        @if($studentTestPaperDetail['subject'] == ENGLISH)
                        @if($studentTestPaperDetail['paper_id'] == 4)
                        <h3>SPAG Test Instructions</h3>
                        <ul>
                            <li>You have {{ $studentTestPaperDetail['paper_time'] }} minutes to complete this test. There are different types of question for you to answer.</li>
                            <li>Read the instructions carefully so that you know how to answer each question.</li>
                            <li>The maximum number of marks is displayed next to each question.</li>
                            <li>You should work through the test, until the time has run out.</li>
                            <li style="font-weight: bold;">Remember: if you cancel the test, you will not be able to redo it and you mark will be 0.</li>
                            <li>Work as quickly and as carefully as you can. If you finish early, go back and check your answers.</li>
                            <li>You have <b>{{ $studentTestPaperDetail['paper_time'] }} minutes</b> to answer the questions in this paper.</li>
                            <li style="font-weight: bold;">Good Luck!</li>
                        </ul>
                        @elseif($studentTestPaperDetail['paper_id'] == 5)
                        <h3>SPAG Test Instructions</h3>
                        <ul>
                            <li>You have {{ $studentTestPaperDetail['paper_time'] }} minutes to complete this test. There are different types of question for you to answer.</li>
                            <li>Read the instructions carefully so that you know how to answer each question.</li>
                            <li>The maximum number of marks is displayed next to each question.</li>
                            <li>You should work through the test, until the time has run out.</li>
                            <li style="font-weight: bold;">Remember: if you cancel the test, you will not be able to redo it and you mark will be 0.</li>
                            <li>Work as quickly and as carefully as you can. If you finish early, go back and check your answers.</li>
                            <li>You have <b>{{ $studentTestPaperDetail['paper_time'] }} minutes</b> to answer the questions in this paper.</li>
                            <li style="font-weight: bold;">Good Luck!</li>
                        </ul>
                        @endif
                        @else($studentTestPaperDetail['subject'] == Math)
                            @if($studentTestPaperDetail['paper_id'] == 1)
                                <h3>Arithmetic Test Instructions</h3>
                                <ul>
                                    <li>You may not use a calculator to answer any questions in this test.</li>
                                    <li>For questions expressed as common fractions, you should give your answers as common fractions.</li>
                                    <li>All other answers should be given as either whole or decimal numbers.</li>
                                    <li>You should work through the test, until the time has run out.</li>
                                    <li style="font-weight: bold;">Remember: if you cancel the test, you will not be able to redo it and you mark will be 0.</li>
                                    <li>Work as quickly and as carefully as you can. If you finish early, go back and check your answers.</li>
                                    <li>You have <b>{{ $studentTestPaperDetail['paper_time'] }} minutes</b> to answer the questions in this paper.</li>
                                    <li style="font-weight: bold;">Good Luck!</li>
                                </ul>
                            @elseif($studentTestPaperDetail['paper_id'] == 2 || $studentTestPaperDetail['paper_id'] ==3)
                                <h3>Reasoning Test Instructions</h3>
                                    <ul>
                                        <li>You may not use a calculator to answer any questions in this test.</li>
                                        <li>The maximum number of marks is displayed next to each question.</li>
                                        <li>You should work through the test, until the time has run out.</li>
                                        <li style="font-weight: bold;">Remember: if you cancel the test, you will not be able to redo it and you mark will be 0.</li>
                                        <li>Work as quickly and as carefully as you can. If you finish early, go back and check your answers.</li>
                                        <li>You have <b>{{ $studentTestPaperDetail['paper_time'] }} minutes</b> to answer the questions in this paper.</li>
                                        <li style="font-weight: bold;">Good Luck!</li>
                                    </ul>
                            @endif
                        @endif
                    </div>
                    @if($num_question != 0)
                    @if( ($isCompleted) )
                    <a class="continue_btn" href="{{ route('test.result',encryptParam($studentTestPaperDetail['attempt_id'])) }}">View Result >></a> 
                    @else
                    <a class="continue_btn" href="{{ route('examination.test',encryptParam($studentTestPaperDetail['attempt_id'])) }}">Continue >></a> 
                    @endif
                    @endif
                </div>
            </div>
        </div>
        <div class="rbot">&nbsp;</div>
    </div>
    <div class="clear_fix"></div>
</div>
<!-- END PAGE CONTENT-->
@stop