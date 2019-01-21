@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.front.myresult-leftbar') 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid">
            <div class="result_zone">
                <div class="result_topic_bg">
                    <div class="col1">
                        <a href="/myresult-revision-progresschart"><img src="images/mytopic_icon.png" /></a>
                        <span>My Topics</span>
                    </div>
                    <div class="col2">Maths</div>
                    <div class="col3">
                        <a href="/myresult-progresschart"><img src="images/myprogress_icon.png" /></a>
                        <span>My Progress Chart</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="rbot">&nbsp;</div>
    </div>
    <div class="clear_fix"></div>
</div>
<!-- END PAGE CONTENT-->
@stop