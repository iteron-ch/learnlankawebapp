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
                <div class="bread_dark ">{{ $trait['trait_2'] }}</div>
                <div class="bread_dark ">{{ $trait['trait_3'] }}</div>
            </h1>
            <div id="content-l" class="revision_content">
                <div class="rev_ques">
                    <ul>
                        <li class="revSelection revSelection_detail">
                            <div class="rev_deatils">
                                <div class="detail_li">Subject - <span>{{ ucfirst($subject) }}</span></div>
                                <div class="detail_li">Assigned By - <span>{{ trim($studentTestPaperDetail['first_name'].' '.$studentTestPaperDetail['last_name'])}}</span></div>
                                <div class="detail_li">Total Questions - <span>{{ $studentTestPaperDetail['question_num'] }}</span></div>
                            </div>
                        </li>
                    </ul>
                    <div class="rev_instruction">
                        <h3>Instructions</h3>
                        <ul>
                            <li>Work as quickly and as carefully as you can.</li>
                            <li>You have 45 minutes for this test.</li>
                            <li>If you cannot do one of the questions, go on to the next one.</li>
                            <li>You can come back to it later, if you have time.</li>
                            <li>If you finish before the end, go back and check your work.</li>
                        </ul>
                    </div>
                    <a class="continue_btn" href="{{ route('examination.test',encryptParam($studentTestPaperDetail['id']))}}">Continue >></a> 
                </div>
            </div>
        </div>
        <div class="rbot">&nbsp;</div>
    </div>
    <div class="clear_fix"></div>
</div>
<!-- END PAGE CONTENT-->
@stop