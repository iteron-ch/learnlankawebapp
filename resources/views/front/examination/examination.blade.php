@extends('front.layout._examination')

@section('content')
<!-- BEGIN PAGE CONTENT-->


<div id="quetionrendering" style="display:none;" class="row" ng-app="quesRender" ng-controller="renderCtrl" ng-init="setDefaultValue({{ $questions}})">
    <div class="col-md-12">
        <div class="col-md-12 padding-top-14" id="topsection">
            <div class="full_container">
                <a ng-click="gotohome()" href="javascript:void(0);" class="logo"></a>
                <div class="right_col">
                    <div class="ques_breadCrumb">
                        <div class="mainTest"  tooltip-placement="bottom" tooltip="[%quessummary.titlefirst%]">[%quessummary.titlefirst%]</div>
                        <span>[%quessummary.titlesecond%]</span>
                    </div>
                    <div class="ques_stats_mark_allow">
                        <div class="ques_stats">
                            <div class="tq">Total Questions <span class="qs_black">[%quessummary.totalques%]</span></div>
                            <div class="ans">Answered <span class="qs_blue">[%quessummary.answered%]</span></div>
                            <div class="skip">Skipped <span class="qs_red">[%quessummary.skipped%]</span></div>
                            <div class="remain">Remaining <span class="qs_yellow">[%quessummary.remaining%]</span></div>
                        </div>
                        <!--<div class="mark_allo">[%quessummary.totalmarks%]</div>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 slider_head">
            <div class="col-md-12">
                <div class="timerBar col-md-10">
                    <div class="bar_bg">
                        <div class="bar_remain">&nbsp;</div>
                    </div>
                </div>
                <div class="timerMin col-md-2" style="font-family:arial; font-weight:bold;">[%quessummary.remainingtime | duration%]</div>
            </div>
            <div class="col-md-12" style="margin-bottom:10px;">
                <div class="qNumList ">
                    <div class="slider1">
                        <div class="slide" ng-repeat="renderEle in renderQuestion" ng-click="navigateToQues('link', $index + 1)" ng-class="{'qSelected btn-cursor-pointer' : currentQues === [%$index+1%] , 'attempt btn-cursor-pointer' : renderEle.attempstatus === 'complete' , 'partAttempt btn-cursor-pointer' : renderEle.attempstatus === 'inprogress'}">[%$index+1%]</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12" id="viewpanel">
            <!--div class="col-md-12 margin-bottom-30">
                <a href="javascript:void(0);" class="qPrev"><img src="/images/prev_icon.png" /></a>
                                 
                <div class="qNumList">
                    <ul>
                        <li ng-repeat="renderEle in renderQuestion" ng-click="navigateToQues( 'link', $index+1)" ng-class="{'qSelected btn-cursor-pointer' : currentQues === [%$index + 1%] , 'attempt btn-cursor-pointer' : renderEle.attempstatus === 'complete' , 'partAttempt btn-cursor-pointer' : renderEle.attempstatus === 'inprogress'}">[%$index+1%]</li>                       
                    </ul>
                </div>
                <a href="javascript:void(0);" class="qNext"><img src="/images/next_icon.png" /></a>
            </div-->
            <div class="header_test_page" ng-if="renderQuestion[currentQues - 1].description && renderQuestion[currentQues - 1].descvisible === 'True'">
                <label ng-bind-html="renderQuestion[currentQues - 1].description | to_trusted"></label> 
            </div>

            <div class="col-md-12" ng-view=""></div>
            <div class="col-md-12 save_cont">
                <div class="view_inst" ng-click="viewInstructionText()" ng-readonly="">View Instruction</div> <span style="float:left;padding-left:175px;padding-top:13px;font-size:15px;font-weight:bold">Don't use the back button on the browser</span>
                <div class="scCont" id="answer_next_question">
                    <img ng-show="currentQues !== 1" ng-click="navigateToQues('pre', currentQues - 1)" src="/images/sc_prev_icon.png" class="acPrev" alt="">
                    <a href="javascript:void(0);" ng-show="currentQues !== renderQuestion.length" ng-click="navigateToQues('next', currentQues + 1)" class="scBtn">Click to continue</a>
                    <a href="javascript:void(0);" ng-show="currentQues === renderQuestion.length" ng-click="navigateToQues('next', currentQues + 1)" class="scBtn">Submit [%quessummary.task_type%]</a>
                    <img ng-show="currentQues !== renderQuestion.length" ng-click="navigateToQues('next', currentQues + 1)" src="/images/sc_next_icon.png" class="acNext" alt="">
                    <!--<img ng-show="currentQues === renderQuestion.length" src="/images/sc_next_icon.png" class="acNext" alt="">-->
                </div>
                <!--<div ng-click="userSubmitText()" ng-show="showSubmitBtn" class="view_inst_right">Submit</div>-->
            </div>
        </div>
        <div class="wrapper footer">
            <div class="main_container">
                <div class="footer_link">
                    <!--<a href="javascript:void(0)">Rules</a> | <a href="javascript:void(0)">Terms of Use</a> | <a href="javascript:void(0)">Copyright Policy </a>  -->
                </div>
                <div class="copy_right">
                    Copyright ® 2015-2016 SATS Companion | All Rights Reserved
                </div>
            </div>
        </div>
    </div>

    <!-- Instruction popup section.: Code Start -->
    <?php
    foreach ($instratctArray as $key => $val) {
        if ($key != '5') {
            ?>
            <script type="text/ng-template" id="instructionPopUp<?php echo $key ?>">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" ng-click="cancel()" >&times;</button>
                <h3 class="modal-title">Instruction</h3>
                </div>
                <div class="modal-body">
                <p><?php echo $val ?></p>
                </div>
                <div class="modal-footer">
                <button class="btn btn-primary" type="button" ng-click="ok()">OK</button>
                </div>
            </script>    
            <?php
        }
    }
    ?>    
    <!-- Instruction popup section: Code End -->
    <script type="text/ng-template" id="pressContinueinstructionPopUp">
        <div class="modal-body">
        <p>To continue, please press the green button.</p>
        </div>
        <div class="modal-footer">
        <button class="btn btn-primary" type="button" ng-click="ok()">OK</button>
        </div>
    </script>

    <!-- Forcelly submit popup section.: Code Start -->
    <script type="text/ng-template" id="popUpforcellySubmit">
        <div class="modal-header">
        <h3 class="modal-title">[%MASTERS.keywords['forcellysubmitedheader']%]</h3>
        </div>
        <div class="modal-body">
        <p>[%MASTERS.keywords['forcellysubmited']%]</p>
        </div>
        <div class="modal-footer">
        <button class="btn btn-primary" type="button" ng-click="ok()">OK</button>
        </div>
    </script>
    <!-- Forcelly submit popup section: Code End -->
    <!-- User submit popup section: Code Start -->
    <script type="text/ng-template" id="popUpUserSubmit">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" ng-click="cancel()" >&times;</button>
        <h3 class="modal-title">[%MASTERS.keywords['usersubmittedheader']%]</h3>
        </div>
        <div class="modal-body">
        <p>[%MASTERS.keywords['usersubmitted']%]</p>
        </div>
        <div class="modal-footer">
        <button class="btn btn-primary" type="button" ng-click="ok()">Yes</button>
        <button class="btn btn-warning" type="button" ng-click="cancel()">No</button>
        </div>
    </script>
    <!-- User submit popup section: Code End -->


</div>

@stop
@section('pagescripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->
{!! HTML::style('bower_components/custom/css/main.css') !!}
{!! HTML::style('css/jquery-ui.min.css') !!}
{!! HTML::style('bower_components/dotsjoin/dotsJoin.css') !!}
{!! HTML::style('bower_components/Reflection/Reflection.css') !!}
{!! HTML::style('bower_components/bootstrap/dist/css/bootstrap.min.css') !!}

{!! HTML::script('bower_components/angular/angular.js') !!}
{!! HTML::script('bower_components/angular-animate/angular-animate.js') !!}
{!! HTML::script('bower_components/angular-route/angular-route.js') !!}
{!! HTML::script('bower_components/angular-flash-alert/dist/angular-flash.js') !!}
{!! HTML::script('bower_components/angular-ui-bootstrap-bower/ui-bootstrap-tpls.js') !!}
{!! HTML::script('bower_components/nicescrollbar/jquery.nicescroll.min.js') !!}
{!! HTML::script('bower_components/raphael/raphael-min.js') !!}
<!--<script class="jsbin" src="http://cdn.jsdelivr.net/raphael/2.1.0/raphael-min.js"></script>-->
<script src="//cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML&dummy=.js"></script>
{!! HTML::script('bower_components/fabric/fabric.js') !!}
{!! HTML::script('bower_components/dotsjoin/dotsJoin.js') !!}
{!! HTML::script('bower_components/Reflection/Reflection.js?v=13') !!}
{!! HTML::script('bower_components/easypie/easypie.js') !!}
{!! HTML::script('bower_components/easypie/angular.easypiechart.js') !!}
<!-- endbuild -->

<!-- build:js({.tmp,app}) scripts/scripts.js -->

{!! HTML::script('bower_components/custom/js/mainrender.js?v=13') !!}
{!! HTML::script('bower_components/jquery.bxslider/jquery.bxslider.min.js') !!}
{!! HTML::style('css/jquery.bxslider.css') !!}
<script>
$(document).ready(function(){
    $('.slider1').bxSlider({
        slideWidth: 40,
                infiniteLoop: false,
                minSlides: 2,
                maxSlides: 10,
                speed: 3000
    });
});
</script>
<style>
    @media (min-width: 992px)
    {
       .col-md-12.qDetail .col-md-11.margin-top-5 p{ width:98%; display:inline-block;}
    }
    </style>
<!-- END PAGE LEVEL SCRIPTS -->
@stop