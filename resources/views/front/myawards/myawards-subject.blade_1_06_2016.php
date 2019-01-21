@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.myawards.leftbar-awards', ['subject' => $subject]) 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid">
            @if(count($testAwards) || count($revisionAwards))
            @if(count($testAwards))
           
            <div class="awards_box">
                 <h2>Test Awards </h2>
                @foreach($testAwards as $award)
                <div class="award_col">
                    <a href="{{ '/uploads/myawards/'.$award['filename'] }}" target="_blank">{!! HTML::image('/uploads/myawards/thumbnail/'.$award['filename'],'',['width' => '200']) !!}</a>
                    <p><label>Title: </label>{{ $award['title'] }}</p>
                    <p><label>Date: </label>{{ outputDateFormat($award['created_at']) }}</p>
                </div>  
                @endforeach
            </div>
            @endif
            @if(count($revisionAwards))
            <div class="awards_box">
                <h2>Revision Awards </h2>
                @foreach($revisionAwards as $award)
                <div class="award_col">
                    <a href="{{ '/uploads/myawards/'.$award['filename'] }}" target="_blank">{!! HTML::image('/uploads/myawards/thumbnail/'.$award['filename'],'',['width' => '200']) !!}</a>
                    <p><label>Title: </label>{{ $award['title'] }}</p>
                    <p><label>Date: </label>{{ outputDateFormat($award['created_at']) }}</p>
                </div>  
                @endforeach
            </div>
            @endif
            @else
            <div class="not_completed">{{ trans('front/front.no_awards') }}</div>
            @endif
        </div>
        <div class="rbot">&nbsp;</div>
    </div>
    <div class="clear_fix"></div>
</div>

@stop