@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.front.revision-leftbar') 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid">
            <div class="revision_zone">
                <div class="revision_topic_bg">
                    <div class="col1"><a href="/revision-maths">Maths</a></div>
                    <div class="col2"><a href="/revision-english">English</a></div>
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