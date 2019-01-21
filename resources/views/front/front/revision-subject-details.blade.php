@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.front.revision-leftbar') 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid pt40">
            <h1>
                <a href="javascript:void(0)" class="back ">&nbsp;</a>
                <div class="heading root_sign breadcrumb_light">Algebra > </div>
                <div class="bread_dark">Mental Maths</div>
            </h1>
            <div id="content-l" class="revision_content">
                <div class="rev_ques">
                    <ul>
                        <li class="revSelection revSelection_detail">
                            <h3>Mental Maths</h3>
                            <div class="rev_deatils">
                                <div class="detail_li">Subject - <span>Maths</span></div>
                                <div class="detail_li">Subject Category - <span>Mental Maths</span></div>
                                <div class="detail_li">Assigned By - <span>Jacob Brown</span></div>
                                <div class="detail_li">Total Questions - <span>30</span></div>
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
                    <div class="continue_btn">Continue >> </div>
                </div>
            </div>
        </div>
        <div class="rbot">&nbsp;</div>
    </div>
    <div class="clear_fix"></div>
</div>
<!-- END PAGE CONTENT-->
@stop