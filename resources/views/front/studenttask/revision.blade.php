@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.studenttask.leftbar-revision') 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid">
            <div class="revision_zone">
                <div class="revision_topic_bg">
                    <div class="col1"><a href="{{ route('studenttask.revisionsubject','maths') }}">{{ trans('front/front.maths') }}</a></div>
                    <div class="col2"><a href="{{ route('studenttask.revisionsubject','english') }}">{{ trans('front/front.english') }}</a></div>
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