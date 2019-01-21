@extends('admin.layout._iframe')

@section('content')
<?php

if ($helpResult->media_type == 1) {
    echo $helpResult->media_link;
}
if ($helpResult->media_type == 2) {
    $format = pathinfo($helpResult->file_name, PATHINFO_EXTENSION);
    if ($format == 'mp3' || $format == 'ogg') {
        ?><div class="col">
            <div class="audio_player"> 
                <audio  controls="controls">  
                    <source src = "/uploads/helpdocuments/<?php echo $helpResult->file_name; ?>" type = "audio/<?php echo $format ?>">
                </audio> 
            </div>
        </div>
    <?php } elseif ($format == 'mp4' || $format == 'wmv' || $format == 'avi' || $format == '3gp') {
        ?>
        <div class="col">
            <div class="video_player">
                <video width = "95%" height = "95%" controls>
                    <source src = "/uploads/helpdocuments/<?php echo $helpResult->file_name; ?>" type = "video/<?php echo $format ?>">
                </video >
            </div>
        </div>
        <?php
    }
}
?>
@stop 
<style>
    .page-content{text-align: center}
</style>