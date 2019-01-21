@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.result.leftbar-result',['subject' => $subject])
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid pt40">

            <h1>
                <a href="{{ route('result.myresultsubject',$subject) }}" class="back">&nbsp;</a>
                <div class="heading">My Tests</div>
            </h1>
            @if(!count($studentCompletedTestList))
            <div class="not_completed">No Completed Test</div>
            @else
            <div>
                <div style="overflow: auto; padding: 20px 20px 30px">
                    <div style=" width: 618px; float: left;">
                        <div style="font-size: 20px; font-family: 'GothamRounded-bold';color: #0b71bc; text-align: center;">My Progress Chart</div>
                        <div id="tabs" style="height: 248px;">
                            <div id="chartContainer1" style="height: 240px;"></div>
                        </div>
                    </div>
                    <div class="score" style="width:150px; float: left">
                        <div class="sScore">
                            <p class="sHeading">Starting Score</p>
                            <p class="spercent {{ @$studentFirstLastTestAttempt['first']['cls'] }}">{{ @$studentFirstLastTestAttempt['first']['percent'] }}%</p>
                        </div>
                        <div class="cScore">
                            <p class="sHeading">Current Score</p>
                            <p class="spercent {{ @$studentFirstLastTestAttempt['last']['cls'] }}">{{ @$studentFirstLastTestAttempt['last']['percent'] }}%</p>
                        </div>
                    </div>
                </div>

                <div style="overflow: auto; padding: 20px 20px 30px; margin: 0 16px; border: 1px solid #ccc; border-radius: 10px; background-color: #F9F7F9;">
                    @if(isset($studentStrandResultList['good']))
                    <div class="success_sec green_bg_box {{ isset($studentStrandResultList['poor']) ? 'border_right': '' }}" style="min-height:180px;">
                        <div class="ssTitle">You're great at:</div>
                        <ol>
                            @if(!empty($studentStrandResultList['good']))
                                @foreach($studentStrandResultList['good'] as $k => $strandGood)
                                <li class="topic_li"><span>{{ ($k+1) }}.</span> <span>{{ $strandGood}}</span></li>
                                @endforeach
                            @else
                                -
                            @endif
                        </ol>
                    </div>
                    @endif
                    @if(isset($studentStrandResultList['poor']))
                    <div class="success_sec red_bg_box {{ isset($studentStrandResultList['good']) ? 'right': '' }}" >
                        <div class="ssTitle">You need to work on:</div>
                        <ol>
                            @if(!empty($studentStrandResultList['poor']))
                                @foreach($studentStrandResultList['poor'] as $k => $strandPoor)
                                <li class="topic_li"><span>{{ ($k+1) }}.</span> <span>{{ $strandPoor}}</span></li>
                                @endforeach
                             @else
                                -
                            @endif    
                        </ol>
                    </div>
                    @endif
                </div>

                <div  style="overflow: auto; padding: 20px 20px 30px">
                    {!! Form::select('test_list', $studentCompletedTestList, null,['id' => 'test_list', 'class' => 'e1']) !!}
                </div>
                <div id="container_attempt_list"></div>
            </div>
            @endif
        </div>
        <div class="rbot">&nbsp;</div>
    </div>
    <div class="clear_fix"></div>
</div>
<!-- END PAGE CONTENT-->
@stop
@section('styles')
{!! HTML::style('css/jquery-ui.min.css') !!}
{!! HTML::style('css/select2.css') !!}
@stop
@section('scripts')
{!! HTML::script('js/jquery.canvasjs.min.js') !!}
{!! HTML::script('js/myresult.js') !!}
{!! HTML::script('js/select2.js') !!}
<script>
    Myresult = new Myresult();
    var vars = {
        urlTestAttemptResult: "{{ route('result.testattemptresult') }}/",
    };
    <?php if(count($studentCompletedTestList)){?>
    $(window).load(function() { 
        Myresult.testAttemptResult(vars, $("#test_list").val());
    });
    <?php }?>
    $(document).ready(function() {
        $(".e1").select2();

        $("#test_list").change(function() {
            Myresult.testAttemptResult(vars, $(this).val());
        });
        <?php if(count($studentCompletedTestList)){?>
            var dataPoints = <?php echo $studentTaskPercentage ?>;
            var title = "My Progress Chart";
            Myresult.taskProgressChart($("#chartContainer1"), dataPoints, title);
        <?php }?>
    });
</script>
@stop