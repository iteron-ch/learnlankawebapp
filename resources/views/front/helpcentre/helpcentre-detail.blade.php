@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.helpcentre.leftbar-helpcentre', ['subject' => $subject]) 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid">
            <div class="revisionEng_zone help_center">
                <?php
                //asd($helpResult);
                //foreach ($helpResult[$category] as $key => $val) {
                // $helpResultData[$val->strand] = $val->strand;
                //$helpResultData[$val->strands_id . '~~' . $val->strand][$val->title][] = $val;
                //$helpResultaa[$val->strands_id][] = $val;
                //}
                if (!empty($helpResult)) {
                    //asd($helpResult);
                    foreach ($helpResult as $key => $val) {
                        $strkey = array();
                        // $strkey = explode('~~', $key);
                        ?>
                        <?php
                        $i = 0;
                        $class = "";
                        //$strkeyVal = $strkey[0];
                        if ($key == '1' || $key == '14')
                            $class = "color_box orange";
                        else if ($key == '128' || $key == '122')
                            $class = "color_box green";
                        else if ($key == '166' || $key == '100')
                            $class = "color_box pink";
                        else if ($key == '150' || $key == '93')
                            $class = "color_box dark_green";
                        else if ($key == '171' || $key == '48')
                            $class = "color_box dark_gray";
                        else if ($key == '141' || $key == '78')
                            $class = "color_box orange_gray";
                        else if ($key == '176' || $key == '98')
                            $class = "color_box blue";
                        else if ($key == '133' || $key == '113')
                            $class = "color_box sky_gray";

                        echo '<div class="' . $class . '">';
                        $title = '';
                        if (!empty($val['filedata'])) {
                            foreach ($val['filedata'] as $key2 => $val2) {
                                $showTitle = 0;
                                if ($title != $val['strandname']) {
                                    $showTitle = 1;
                                    $title = $val['strandname'];
                                }

                                echo '<div class="video_box_container" style="padding:0px; border-radius:10px.dark_gray .title">';
                                if ($showTitle == 1)
                                    echo '<div class="title"><a name="' . $key . '">&nbsp;</a>' . $val['strandname'] . '</div>';
                                echo '<div class="title sub">' . $val2['title'] . '</div>';
                                if (!empty($val2['data'])) {
                                    foreach ($val2['data'] as $keyFile => $valFile) {
                                        if ($valFile['media_type'] == 1) {
                                            // youtube
                                            ?>
                                            <div class="col col_data">
                                                <div class="heading ">  
                                                    <div class="top_row">
                                                        <span><a href="javascript:void(0)" onclick="opencolorbox('<?php echo $valFile['file_id'] ?>');">
                                                                <?php echo $valFile['media_link'] ?>
                                                            </a></span>
                                                        <span>&nbsp;</span>
                                                    </div>

                                                </div>
                                            </div>

                                            <?php
                                        } else if ($valFile['media_type'] == 2) {
                                            // upload video file

                                            $format = pathinfo($valFile['file_name'], PATHINFO_EXTENSION);

                                            if ($format == 'mp3' || $format == 'ogg') {
                                                ?>
                                                <div class="col col_data">
                                                    <div class="heading ">  
                                                        <div class="top_row">
                                                            <div class="audio_player"> 
                                                                <audio  controls="controls">  
                                                                    <source src = "/uploads/helpdocuments/<?php echo $valFile['file_name']; ?>" type = "audio/<?php echo $format ?>">
                                                                </audio> 
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            <?php } elseif ($format == 'mp4' || $format == 'wmv' || $format == 'avi' || $format == '3gp') {
                                                ?>
                                                <div class="col col_data">
                                                    <div class="heading ">  
                                                        <div class="top_row">
                                                            <div class="video_player">
                                                                <video width = "340" height = "200" controls>
                                                                    <source src = "/uploads/helpdocuments/<?php echo $valFile['file_name']; ?>" type = "video/<?php echo $format ?>">
                                                                </video >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                
                                            <?php }
                                            ?>
                                            <?php
                                        } else if ($valFile['media_type'] == 3) {

                                            $fileExt = getFileExtensionFromFilename($valFile['file_name']);
                                            if ($fileExt == 'txt')
                                                $fileicon = 'textfile.png';
                                            elseif ($fileExt == 'pdf')
                                                $fileicon = 'pdf.png';
                                            elseif ($fileExt == 'ppt')
                                                $fileicon = 'ppt.png';
                                            elseif ($fileExt == 'doc' || $fileExt == 'docx')
                                                $fileicon = 'docx.png';
                                            else
                                                $fileicon = 'textfile.png';
                                            ?>
                                            <div class="col col_data">
                                                <div class="heading ">  
                                                    <div class="top_row">
                                                        <span><a href="{{ route('helpcentre.helpcentreview',$valFile['file_id']) }}">
                                                                <img src="/images/<?php echo $fileicon ?>" title="View" width="32" height="32"></a></span>
                                                        <span><?php echo $valFile['original_file_name']; ?></span>
                                                    </div>
                                                    <div class="bottom_row">

                                                        <!--<a href="{{ route('helpcentre.helpcentreview',$valFile['file_id']) }}" class="download">
                                                            <img src="/images/hdownload.png" width="30" title="Download" ></a>-->
                                                    </div>    

                                                </div>
                                            </div>
                                            <?php
                                        } else if ($valFile['media_type'] == 4) {
                                            // jpg file
                                            ?>

                                            <div class="col col_data">
                                                <div class="heading ">  
                                                    <div class="top_row">
                                                        <span><a href="{{ route('helpcentre.helpcentreview',$valFile['file_id']) }}"><img src="/images/himage.png" title="View" width="30"></a></span>
                                                        <span><?php echo $valFile['original_file_name'] ?></span>
                                                    </div>

                                                </div>
                                            </div>                
                                        <?php } else if ($valFile['media_type'] == 5) {
                                            ?>
                                            <div class="col col_data">
                                                <div class="heading ">  
                                                    <div class="top_row">
                                                        <span><a href="{{ route('helpcentre.helpcentreview',$valFile['file_id']) }}" target="_blank"><img src="/images/hlink.png" title="Visit url" width="30"></a></span>
                                                        <span><?php echo $valFile['media_link'] ?></span>
                                                    </div>

                                                </div>
                                            </div>                
                                            <?php
                                        }
                                    }
                                }
                                echo '</div>';
                            }
                        }
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












                <!--<div class="col">
                    <div class="audio_player">
                        <audio  controls="controls">  
                           sdkcjskcjskldc
                        </audio> 
                    </div>
                </div>
                <div class="col">
                    <div class="video_player">
                        <video width = "320" height = "240" controls>
                           sdmcnsdjk
                        </video >
                    </div>
                </div>
                <div class="col">
                    <div class="heading"> 
                        sdjkcjskdc
                    </div>
                </div>
                <div class="not_completed">
                    No Help Document avaliable for this subject.
                </div>-->

            </div>
        </div>
        <div class="rbot">&nbsp;</div>
    </div>
    <div class="clear_fix"></div>
</div>
@stop
@section('pagescripts')
{!! HTML::style('css/colorbox.css') !!}
<style>
    #cboxOverlay{ background:#666666; }
</style>

<script>
    function opencolorbox(id) {
        $.colorbox({fastIframe: false, width: "800px", height: "650px", transition: "fade", scrolling: false, iframe: true, href: "/help-view/" + id});
    }
</script>
@stop