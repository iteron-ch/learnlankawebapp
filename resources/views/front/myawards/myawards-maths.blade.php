@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.myawards.leftbar-awards', ['subject' => $subject]) 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid">
            <div class="awards_box">
                <div class="award_col"><img src="{{ SITE_URL }}/questionimg/Award_1.png" alt="" class="img-circle" width="200">
                    <p><label>Name</label></p>
                    <p><label>Title</label></p>
                    <p><label>Description</label></p><p><label>Date</label></p>
                </div>           
                <div class="award_col"><img src="{{ SITE_URL }}/questionimg/Award_2.png" alt="" class="img-circle" width="200">
                    <p><label>Name</label></p>
                    <p><label>Title</label></p>
                    <p><label>Description</label></p><p><label>Date</label></p>
                </div>           
                <div class="award_col"><img src="{{ SITE_URL }}/questionimg/Award_1.png" alt="" class="img-circle" width="200">
                    <p><label>Name</label></p>
                    <p><label>Title</label></p>
                    <p><label>Description</label></p><p><label>Date</label></p>
                </div>           
                <div class="award_col"><img src="{{ SITE_URL }}/questionimg/Certificate_1.png" alt="" class="img-circle" width="200">
                    <p><label>Name</label></p>
                    <p><label>Title</label></p>
                    <p><label>Description</label></p><p><label>Date</label></p>
                </div>           			
                <div class="award_col"><img src="{{ SITE_URL }}/questionimg/Certificate_1.png" alt="" class="img-circle" width="200">
                    <p><label>Name</label></p>
                    <p><label>Title</label></p>
                    <p><label>Description</label></p><p><label>Date</label></p>
                </div>           
                <div class="award_col"><img src="{{ SITE_URL }}/questionimg/Certificate_3.png" alt="" class="img-circle" width="200">
                    <p><label>Name</label></p>
                    <p><label>Title</label></p>
                    <p><label>Description</label></p><p><label>Date</label></p>
                </div>           
            </div>
        </div>
        <div class="rbot">&nbsp;</div>
    </div>
    <div class="clear_fix"></div>
</div>

@stop