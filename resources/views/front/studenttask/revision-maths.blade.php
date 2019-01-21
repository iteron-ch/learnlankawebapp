@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.studenttask.leftbar-revision', ['subject' => $subject]) 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid">
            <div class="revisionMaths_zone">
                <div class="revisionMaths_topic_bg">
                    <div class="colMain"><a href="#">Maths<br>Topics</a></div>
                    @foreach($strandAtt as $row)
                         @if(in_array($row['id'], $assigned_strands))
                        <div data-url="{{ route('studenttask.strand',['maths',encryptParam($row['id'])]) }}" class="{{ $row['class'].' clickenable' }}">
                        @else
                        <div class="{{ $row['class'].' disable' }}">
                             @endif
                                <img src="../images/{{ $row['icon_l'] }}" />
                                <span>{{ $strand[$row['id']] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="rbot">&nbsp;</div>
    </div>
    <div class="clear_fix"></div>
</div>

@stop
@section('scripts') 
    <style>
        .clickenable{cursor: pointer;}
    </style>
    <script>
        $(document).ready(function() {
            $(".clickenable").click(function(e){
                e.preventDefault();
                window.location.href = $(this).data('url');
            });
        });
    </script>
    @stop 