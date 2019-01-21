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
                <div class="heading root_sign">{{ $trait['trait_1'] }}</div>
            </h1>
            <div id="content-l" class="revision_content">
                <div class="rev_ques">
                    <ul>
                        @foreach($revisions['assigned_revision'] as $revision)
                        <li class="revSelection clickenable" data-url="{{ route('studenttask.revisiondetail',encryptParam($revision['id'])) }}">
                            <a href="{{ route('studenttask.revisiondetail',encryptParam($revision['id'])) }}">{{ $revision['substrand']}}</a>
                            <span>&nbsp;</span>
                        </li>
                        @endforeach
                        
                    </ul>
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
<style>
    .clickenable{cursor: pointer;}
</style>
{!! HTML::script('bower_components/nicescrollbar/jquery.nicescroll.min.js') !!}
<script>
    $(document).ready(function() {
        $("#content-l").niceScroll({cursorwidth: "10px"});
        $(".clickenable").click(function(e){
            e.preventDefault();
            window.location.href = $(this).data('url'); 
        });
    });
</script>
@stop 