@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.result.leftbar-result',['subject' => $subject]) 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid">
            <div class="result_zone">
                <div class="result_topic_bg">
                    <div class="col1">
                        <a href="{{ route('result.myresultrevision','english') }}">{!! HTML::image('images/mytopic_icon.png') !!}</a>
                        <span>My Topics</span>
                    </div>
                    <div class="col2">English</div>
                    <div class="col3">
                        <a href="{{ route('result.myresulttest','english') }}">{!! HTML::image('images/myprogress_icon.png') !!}</a>
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
@section('scripts')
@stop 