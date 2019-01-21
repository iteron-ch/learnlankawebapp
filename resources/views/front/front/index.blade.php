@extends('front.layout._default')

@section('content')
@if(!@session()->get('user')['tutor_id'])
    <div class="main_container bashboard_landing test">
@else        
    <div class="main_container bashboard_landing">
@endif    
    <div class="revision"><a href="/revision">{{trans('front/front.revision')}}</a></div>
    <div class="tests"><a href="/test">{{trans('front/front.tests')}}</a></div>
    <div class="results"><a href="/myresult">{{trans('front/front.results')}}</a></div>
    <div class="help_Centre"><a href="{{ route('helpcentre.helpcentre') }}">{{trans('front/front.help_centre')}}</a></div>
    <div class="awards"><a href="{{ route('myawards.myawards') }}">{{trans('front/front.awards')}}</a></div>
    <div class="messages"><a href="{{ route('messages.inbox') }}" style="width: 200px;">{{trans('front/front.messages')}}&nbsp;@include('front.messages.unread-count')</a></div>
    @if(!@session()->get('user')['tutor_id'])
        <div class="tasks"><a href="/task">{{trans('front/front.tasks')}}</a></div>
    @endif    

</div>

<!-- BEGIN PAGE CONTENT-->
@stop
@section('scripts')
@stop