@extends('admin.layout._iframe')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<style>
    .p_margin p {margin:0px;}
    .marks_box {
        position: absolute;
        width: 60px;
        height: 35px;
        text-align: center;
        background: #c6cde6 !important;
        top: 0px;
        right: 0px;
        border-radius: 10px !important;
        color: #000;
        font-weight: bold;
    }
</style>
<div class="row" ng-app="printPopUp" ng-controller="printPopUpCtrl">
    <div class="portlet" ng-init="initData({{$questionsData}})" >
        <button class="btn btn-primary pull-right" type="button" ng-click="printPage('printQuestioObj')">Print</button>
        <div class="portlet-body" id="printQuestioObj">
            <img src="/images/demo.png" border="1" width='100%' height='820'/>
            <div ng-if="checkMode === 'question'" ng-repeat="(setIndex, setele) in MASTERS.questions" style="clear:both; padding:5px; margin-top:30px;">
                <div class="p_margin" style="margin-top:10px;" ng-if="setele.description && setele.descvisible === 'True'">
                    <label ng-bind-html="setele.description | to_trusted" style="background: #ffffff !important; border-radius: 8px !important; float: left; font-size: 15px; margin: 0 !important; padding: 7px 10px; width: 98%; margin-right:20px !important; margin-left:20px !important;"></label> 
                </div>
                <div ng-repeat="( quesIndex, quesele ) in setele.questions" style="margin-bottom:34px; float: left; width:100%">
                    <div class="col-md-12" style="margin-top:20px;">
                        <div class="col-md-12 p_margin" style="margin-bottom:15px; float: left; width:100%">
                            <label class="pull-left" style="background-color:#000000 !important; border-radius: 8px !important; float: left; margin-right: 10px; padding: 7px 10px;"><span style="color:#ffffff !important; font-size:15px !important;font-weight:bold !important; ">[%setIndex+1%].[%quesIndex+1%]&nbsp;</span></label>
                            <div id="[%quesIndex%]" style="font-weight:bold; background-color:#c6cde6 !important; border-radius:8px !important; float:left; font-size:15px; font-weight:bold; margin: 0 !important; padding: 7px 10px; width: 82%;" class="col-md-11" ng-bind-html="quesele.ques | to_trusted"></div>
                            <div class="marks_box">
                                <div class="mark_div">[%quesele.mark%]</div>
                                <div ng-show="quesele.mark <= 1">mark</div>
                                <div ng-show="quesele.mark > 1">marks</div>
                            </div>
                        </div>
                        <div ng-if="setele.question_type === 28" class="col-md-12" style="margin-top:20px;">
                            <div class="col-xs-12">
                                <img src="/questionimg/[%quesele.option.value%]" border="1" width='300' height='250'/>
                            </div>
                            <div class="col-xs-12 margin-top-10" ng-repeat="eleAnswer in setele.userresponse[quesIndex]">
                                <div class="col-xs-2">[%eleAnswer.label%]</div>
                                <div class="col-xs-2 col-xs-pull-1">
                                    <input type="text"/>
                                </div>
                            </div>
                        </div>

                        <div ng-if="setele.question_type === 2" class="col-md-12" style="margin-top:20px;">
                            <div class="col-xs-12" ng-repeat="ele in setele.questions[$index].option">
                                <div style="display: flex;">
                                    <input style="opacity:1;" type="checkbox" name="multiplechoicecheckbox">
                                    <div class="col-xs-12 text-left" style="display: inline-block;" ng-bind-html="ele.option | to_trusted"></div>
                                </div>
                            </div>
                        </div>
                        <div ng-if="setele.question_type === 1" class="col-md-12" style="margin-top:10px;">
                            <div class="col-xs-12" ng-repeat="ele in setele.questions[$index].option">
                                <div style="display: flex;">
                                    <input style="opacity:1; margin-left:-7px;" type="radio"  name="singlechoiceradio">
                                    <div class="col-xs-12 text-left" style="display: inline-block;" ng-bind-html="ele.option | to_trusted"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--Answer booklet section : start-->
            <h1 ng-if="checkMode === 'answer'" style="color:blue !important;font-weight: bold;padding-left: 35px;">Answer Booklet</h1>
            <div ng-if="checkMode === 'answer'" ng-repeat="(setIndex, setele) in MASTERS.questions" style="clear:both; padding:5px;">
                <div class="p_margin" style="margin-top:10px;" ng-if="setele.description && setele.descvisible === 'True'">
                    <label ng-bind-html="setele.description | to_trusted" style="background: #ffffff !important; border-radius: 8px !important; float: left; font-size: 15px; margin: 0 !important; padding: 7px 10px; width:98%;margin-right:20px !important; margin-left:20px !important;"></label> 
                </div>
                <div ng-repeat="( quesIndex, quesele ) in setele.questions" style="margin-bottom:34px; float: left; width:100%">
                    <div class="col-md-12" style="margin-top:20px;">
                        <div class="col-md-12 p_margin" style="margin-bottom:15px; float: left; width:100%">
                            <label class="pull-left" style="background-color:#000000 !important; border-radius: 8px !important; float: left; margin-right: 10px; padding: 7px 10px;"><span style="color:#ffffff !important; font-size:15px !important;font-weight:bold !important; ">[%setIndex+1%].[%quesIndex+1%]&nbsp;</span></label>
                            <div id="[%quesIndex%]" style="font-weight:bold; background:#c6cde6 !important; border-radius:8px !important; float:left; font-size:15px; font-weight:bold; margin: 0 !important; padding: 7px 10px; width: 82%;" class="col-md-11" ng-bind-html="quesele.ques | to_trusted"></div>
                            <div class="marks_box">
                                <div class="mark_div">[%quesele.mark%]</div>
                                <div ng-show="quesele.mark <= 1">mark</div>
                                <div ng-show="quesele.mark > 1">marks</div>
                            </div>
                        </div>
                        <div ng-if="setele.question_type === '28'" class="col-md-12" style="margin-top:20px;">
                            <div class="col-xs-12">
                                <img src="/questionimg/[%quesele.option.value%]" border="1" width='300' height='250'/>
                            </div>
                            <div class="col-xs-12 margin-top-10" ng-repeat="eleAnswer in setele.userresponse[quesIndex]">
                                <div class="col-xs-2">[%eleAnswer.label%]</div>
                                <div class="col-xs-2 col-xs-pull-1">
                                    <input type="text" ng-model="quesele.correctAns[$index].value" />
                                </div>
                            </div>
                        </div>

                        <div ng-if="setele.question_type === 2" class="col-md-12" style="margin-top:20px;">
                            <div class="col-xs-12" ng-repeat="ele in setele.questions[$index].option">
                                <div style="display: flex;">
                                    <input style="opacity:1;" type="checkbox" name="multiplechoicecheckbox" ng-model="quesele.correctAns[$index].ischeck">
                                    <div class="col-xs-12 text-left" style="display: inline-block;" ng-bind-html="ele.option | to_trusted"></div>
                                </div>
                            </div>
                        </div>
                        <div ng-if="setele.question_type === 1" class="col-md-12" style="margin-top:20px;">
                            <div class="col-xs-12" ng-repeat="ele in setele.questions[$index].option">
                                <div style="display: flex;">
                                    <input style="opacity:1; margin-left:-7px;" type="radio"  name="singlechoiceradio" value='0' ng-model="quesele.correctAns">
                                    <div class="col-xs-12 text-left" style="display: inline-block;" ng-bind-html="ele.option | to_trusted"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--Answer booklet section : end-->
            
        </div>
        <!--end profile-settings-->
    </div>
</div>

<!-- END PAGE CONTENT-->
@stop
@section('pagescripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->
{!! HTML::script('bower_components/angular/angular.js') !!}
{!! HTML::script('bower_components/angular-animate/angular-animate.js') !!}
{!! HTML::script('bower_components/angular-route/angular-route.js') !!}
{!! HTML::script('bower_components/angular-flash-alert/dist/angular-flash.js') !!}
<!-- endbuild -->

<!-- build:js({.tmp,app}) scripts/scripts.js -->

{!! HTML::script('bower_components/custom/js/printquestion.js') !!}