@extends('front.layout._examination')

@section('content')
<!-- BEGIN PAGE CONTENT-->
	

<div id="quetionrendering" style="display:none;" class="row" ng-app="quesRender" ng-controller="renderCtrl" ng-init="setDefaultValue({{ $questions }})">
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
                        </div>
                        <!--<div class="mark_allo">[%quessummary.totalmarks%]</div>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 slider_head">
            
            <div class="col-md-12" style="margin-bottom:10px;">
                <div class="qNumList ">
                    <div class="slider1">
			<!--<div class="slide" ng-repeat="renderEle in renderQuestion" ng-click="navigateToQues( 'link', $index+1)" ng-class="{'qSelected btn-cursor-pointer' : currentQues === [%$index + 1%] , 'attemptcorrect btn-cursor-pointer' : renderEle.attempiscorrect === '1' , 'attemptnotcorrect btn-cursor-pointer' : renderEle.attempiscorrect === '0'}">[%$index+1%]</div>-->
                        <div class="slide" ng-repeat="renderEle in renderQuestion" ng-click="navigateToQues( 'link', $index+1)" ng-class=" currentQues === [%$index + 1%] ? ( renderEle.attempiscorrect === '1' ? 'qSelectedcorrect btn-cursor-pointer' : 'qSelectednotcorrect btn-cursor-pointer')  : ( renderEle.attempiscorrect === '1' ? 'attemptcorrect btn-cursor-pointer' : 'attemptnotcorrect btn-cursor-pointer')">[%$index+1%]</div>				
                    </div>
                </div>
             </div>
	</div>
	
        <div class="col-md-12" style="position:relative;" id="viewpanel">
            <div class="fade-pannel"></div>
            <!--div class="col-md-12 margin-bottom-30">
                <a href="javascript:void(0);" class="qPrev"><img src="/images/prev_icon.png" /></a>
				 
                <div class="qNumList">
                    <ul>
                        <li ng-repeat="renderEle in renderQuestion" ng-click="navigateToQues( 'link', $index+1)" ng-class="{'qSelected btn-cursor-pointer' : currentQues === [%$index + 1%] , 'attempt btn-cursor-pointer' : renderEle.attempstatus === 'complete' , 'partAttempt btn-cursor-pointer' : renderEle.attempstatus === 'inprogress'}">[%$index+1%]</li>                       
                    </ul>
                </div>
                <a href="javascript:void(0);" class="qNext"><img src="/images/next_icon.png" /></a>
            </div-->
            <div class="header_test_page" ng-if="renderQuestion[currentQues-1].description && renderQuestion[currentQues-1].descvisible === 'True'">
                <label ng-bind-html="renderQuestion[currentQues-1].description | to_trusted"></label> 
            </div>

            <div class="col-md-12" ng-view=""></div>
            <div class="col-md-12 save_cont">
                <div class="scCont">
                    <img ng-click="navigateToQues( 'pre', currentQues - 1 )" src="/images/sc_prev_icon.png" class="acPrev" alt="">
                    <img ng-show="currentQues !== renderQuestion.length" ng-click="navigateToQues( 'next', currentQues + 1 )" src="/images/sc_next_icon.png" class="acNext" alt="">
                    <img ng-show="currentQues === renderQuestion.length" src="/images/sc_next_icon.png" class="acNext" alt="">
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
    <script type="text/ng-template" id="instructionPopUp">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" ng-click="cancel()" >&times;</button>
            <h3 class="modal-title">Instruction</h3>
        </div>
        <div class="modal-body">
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="button" ng-click="ok()">OK</button>
        </div>
    </script>
    <!-- Instruction popup section: Code End -->
    
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
{!! HTML::script('bower_components/Reflection/Reflection.js') !!}
{!! HTML::script('bower_components/easypie/easypie.js') !!}
{!! HTML::script('bower_components/easypie/angular.easypiechart.js') !!}
<!-- endbuild -->

<!-- build:js({.tmp,app}) scripts/scripts.js -->

{!! HTML::script('bower_components/custom/js/mainresultrender.js?v=2') !!}
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
<style>.anshead{padding: 10px 20px;
    background: rgba(60, 118, 61, 0.29);
    border-radius: 10px;
    float:left;
    width:100%;
    font-weight:bold;
    }

h2 {
    font-size: 22px;
}

    @media (min-width: 992px)
    {
       .col-md-12.qDetail .col-md-11.margin-top-5 p{ width:98%; display:inline-block;}
    }
    .fade-pannel{
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 1400px;
        background: black;
        opacity: .01;
        z-index: 500;
    }
    </style>
<!-- END PAGE LEVEL SCRIPTS -->
@stop