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
                <div class="heading">My Revision </div>
            </h1>
            <div class="head text_Center">
                <label for="">Questions Attempted : <span>{{ $questionAttempt }}</span></label>
            </div>
            @if(count($dataResult))
            @foreach($dataResult as $key=>$row)
            <div id="content-l" class="avg_test_content">
                <div class="qa_table">
                    <ul class="qa_thead">
                        <li class="qa_td1">{{ $row['topic'] }}</li>
                        <li class="qa_td2">Average of Last 3<br/>Attempts</li>
                        <li class="qa_td3">Highest<br/>Score %</li>
                        <li class="qa_td4">Rate your<br/>Understanding</li>
                    </ul>
                    @foreach($row['data'] as $subtopic)
                    <ul class="qa_tbody">
                        <li class="qa_td1">{{ $subtopic['subtopic'] }}</li>
                        <li class="qa_td2">
                            <div class="qa_progress">
                                <span class="qa_pbar {{ $subtopic['avg_last_three_attempt']['avgColor'] }}" data-value="{{ $subtopic['avg_last_three_attempt']['avg'] }}%">&nbsp;</span>
                                <span class="qa_percent">{{ $subtopic['avg_last_three_attempt']['avg'] }}</span>
                            </div>
                        </li>
                        <li class="qa_td3">{{ $subtopic['heighstscore'] }}</li>
                        <li class="qa_td4">
                            <?php 
                            $stars = 0;
                            $stars = @$star_rating[$key][$subtopic['subtopic_id']][0];
                            for ($k = 1;$k<=$stars; $k++){
                                ?>
                                <img src="/images/star_rating.png" title='<?php echo $k ?> Rating' id="rating<?php echo $subtopic['subtopic_id'] ?><?php echo $k?>" onmouseover="changeRating('<?php echo $k?>','<?php echo $subtopic['subtopic_id'] ?>')" onmouseout="resetRating('<?php echo $subtopic['subtopic_id'] ?>')" onClick="saveRating('<?php echo encryptParam($student_id); ?>', '<?php echo encryptParam($key) ?>', '<?php echo encryptParam($subtopic['subtopic_id']) ?>','<?php echo $k?>','<?php echo $subtopic['subtopic_id'] ?>')" style="cursor:pointer">                            
                                <span id="span_rating<?php echo $subtopic['subtopic_id'] ?>" style="display:none;"><?php echo $stars ?></span>
                                <?php
                            }
                            for ($k = $stars+1;$k<=5; $k++){
                                ?>
                                <img src="/images/white_star.png" title='<?php echo $k ?> Rating' id="rating<?php echo $subtopic['subtopic_id'] ?><?php echo $k?>" onmouseover="changeRating('<?php echo $k?>','<?php echo $subtopic['subtopic_id'] ?>')" onmouseout="resetRating('<?php echo $subtopic['subtopic_id'] ?>')" onClick="saveRating('<?php echo encryptParam($student_id); ?>', '<?php echo encryptParam($key) ?>', '<?php echo encryptParam($subtopic['subtopic_id']) ?>','<?php echo $k?>','<?php echo $subtopic['subtopic_id'] ?>')" style="cursor:pointer">                            
                                <span id="span_rating<?php echo $subtopic['subtopic_id'] ?>" style="display:none;"><?php echo $stars ?></span>
                                <?php
                            } 
                            ?>                            
                        </li>
                    </ul>
                    @endforeach
                </div>
            </div>
            @endforeach
            @endif
        </div>
        <div class="rbot">&nbsp;</div>
    </div>
    <div class="clear_fix"></div>
</div>
<!-- END PAGE CONTENT-->
@stop
@section('scripts')
<script>
    $(document).ready(function() {

        $(".qa_table .qa_progress .qa_pbar").each(function() {
            val = $(this).data("value");
            $(this).animate({
                width: val
            }, 500);
        });
    });
    /* This is used to change the rating by the user for any startand/substrand */
    function saveRating(student_id, strand_id, substrand_id, rating, topic_id){
         $.ajax({
            url: "/save-student-rating",
            type: 'POST',
            data: {
                    student_id:student_id,
                    strand:strand_id,
                    substrand:substrand_id,
                    rating:rating
                },
            success: function(data) {
                changeRating(rating, topic_id);
                $("#span_rating"+topic_id).html(rating);
            }
        });
    }
    /* This is used to highlight the star rating */
    function changeRating(value, substrand){
        //var val=$("#span_rating"+substrand).html()
        for(var i=1; i<=5; i++){
            $("#rating"+substrand+i).attr('src', '/images/white_star.png');   
        }
        for(var i=1; i<=value; i++){
            $("#rating"+substrand+i).attr('src', '/images/star_rating.png');   
        }
    }
    /* This is used to reset the star rating */
    function resetRating( substrand){
        
        var value=$("#span_rating"+substrand).html()*1
        for(var i=1; i<=5; i++){
            $("#rating"+substrand+i).attr('src', '/images/white_star.png');   
        }
        for(var i=1; i<=value; i++){
            $("#rating"+substrand+i).attr('src', '/images/star_rating.png');   
        }        
    }
    /* This is used to reset the star rating */
    function resetRating2(value, substrand){
        for(var i=1; i<=5; i++){
            $("#rating"+substrand+i).attr('src', '/images/white_star.png');   
        }
        for(var i=1; i<=value; i++){
            $("#rating"+substrand+i).attr('src', '/images/star_rating.png');   
        }
    }
    </script>
@stop 