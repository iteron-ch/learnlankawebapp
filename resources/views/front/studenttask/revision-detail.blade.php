@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.studenttask.leftbar-revision', ['subject' => $subject]) 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid pt40">
             <h1>
                <a href="{{ $trait['back_link'] }}" class="back">&nbsp;</a>
                <div class="heading root_sign breadcrumb_light">{{ $trait['trait_1'] }}</div>
                <!--<div class="bread_dark">{{ $trait['trait_2'] }}</div>-->
            </h1>
            <div id="content-l" class="revision_content">
                <div class="rev_ques">
                    <ul>
                        <li class="revSelection revSelection_detail">
                            <h3>{{ $revisionAssigned['substrand'] }}</h3>
                            <div class="rev_deatils">
                                <div class="detail_li">Subject - <span>{{ ucfirst($revisionAssigned['subject']) }}</span></div>
                                <div class="detail_li">Subject Category - <span>{{ $revisionAssigned['substrand'] }}</span></div>
                                <div class="detail_li">Assigned By - <span>{{ trim($revisionAssigned['first_name'].' '.$revisionAssigned['last_name'])}}</span></div>
                                <div class="detail_li">Total Questions - <span>{{ $revisionAssigned['question_num'] }}</span></div>
                            </div>
                        </li>
                    </ul>
                    <div class="rev_instruction">
                        <h3>Revision Instructions</h3>
                        <ul>
                            <li>The maximum number of marks is displayed next to each question.</li>
                            <li>Work as quickly and as carefully as you can.  Make sure to go back and check your answers.</li>
                            <li style="font-weight: bold;">Remember: You can redo the revision topics as many times as needed and don’t forget to<br>check out the video tutorials!</li>
                            <li style="font-weight: bold;">Good Luck!</li>
                        </ul>
                    </div>
                    @if($isCompleted)
                    <a class="continue_btn" href="{{ route('revision.result',encryptParam($student_revisionid)) }}">View Result >></a> 
                    @else
                    <a class="continue_btn" href="{{ route('examination.revision',encryptParam($student_revisionid)) }}">Continue >></a> 
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