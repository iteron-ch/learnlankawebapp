@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.helpcentre.leftbar-helpcentre', ['subject' => $subject]) 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid">
            <div class="revisionEng_zone help_center">
                <?php //asd($helpCentreData);
                
                
                if (count($helpCentreData) > 0) {
                    foreach ($helpCentreData as $stkey => $stval) {
                        $class = "";
                        if($stval['strands_id'] == '1' || $stval['strands_id'] == '14')
                            $class = "color_box orange";
                        else if($stval['strands_id'] == '128' || $stval['strands_id'] == '122')
                            $class = "color_box green";
                        else if($stval['strands_id'] == '166' || $stval['strands_id'] == '100')
                            $class = "color_box pink";
                        else if($stval['strands_id'] == '150' || $stval['strands_id'] == '93')
                            $class = "color_box dark_green";
                        else if($stval['strands_id'] == '171' || $stval['strands_id'] == '48')
                            $class = "color_box dark_gray";
                        else if($stval['strands_id'] == '141' || $stval['strands_id'] == '78')
                            $class = "color_box orange_gray";
                        else if($stval['strands_id'] == '176' || $stval['strands_id'] == '98')
                            $class = "color_box blue";
                        else if($stval['strands_id'] == '133' || $stval['strands_id'] == '113')
                            $class = "color_box sky_gray";
                        unset($stval['strands_id']);
                        echo '<div class="'.$class.'">';
                        echo '<div class="title">' . $stkey . '</div>';
                        echo '<div class="video_box_container">';
                        foreach ($stval as $key => $val) {
                            $format = pathinfo($val['file_name'], PATHINFO_EXTENSION);
                            if ($format == 'mp3' || $format == 'ogg') {
                                ?><div class="col">
                                    <div class="audio_player">
                                        <audio  controls="controls">  
                                            <source src = "/uploads/helpDocuments/<?php echo $val['file_name']; ?>" type = "audio/<?php echo $format ?>">
                                        </audio> 
                                    </div>
                                </div>
                            <?php } elseif ($format == 'mp4' || $format == 'wmv' || $format == 'avi' || $format == '3gp') {
                                ?>
                                <div class="col">
                                    <div class="video_player">
                                        <video width = "320" height = "240" controls>
                                            <source src = "/uploads/helpDocuments/<?php echo $val['file_name']; ?>" type = "video/<?php echo $format ?>">
                                        </video >
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="col">
                                    <div class="heading"> 
                                        <a href="/uploads/helpDocuments/<?php echo $val['file_name']; ?>" target="_blank"><?php echo $val['original_file_name']; ?></a> 
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    ?>
                    <div class="not_completed">
                         No Help Document available for this subject.
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="rbot">&nbsp;</div>
    </div>
    <div class="clear_fix"></div>
</div>


@stop