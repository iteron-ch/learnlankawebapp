@extends('admin.layout._iframe_print')
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
    }
    .pad_left{padding-left:68px;}
    .pad_left_10{padding-left:10px;}
    .angular .list{ position:absolute; cursor:pointer; font-size:12px; font-weight:bold;}
    .piechart-bullet{height:6px; width:6px; background:#333444 !important; border-radius: 3px !important;}
    .piechart-bullet-bold{height:15px; width:15px; background:#000  !important; border-radius: 10px !important;}   

    .patt-wrap{position:relative; cursor:pointer;}
    .patt-wrap ul, .patt-wrap li{
        list-style: none !important;
        margin:0;
        padding: 0;
    }
    .questionpatter{ float:left !important; box-sizing:border-box;}
    .patt-circ{
        position:relativ !importante;
        float: left !important;
        box-sizing: border-box !important;
        -moz-box-sizing: border-box !important;
    }
    .questionpatter patt-holder{

    }
    page-header { display: table-header-group; }

    .patt-dots{
        background: #3382c0 !important;
        width: 2px !important;
        height: 2px !important;
        border-radius:2px !important;
        position:absolute !important;
        top:50% !important;
        left:50% !important;
        /*margin-top:-2px;
        margin-left:-2px;*/
    }
    .questionpatter {
        display: inline-block !important;
        /* width:217px !important;*/
    }
    .patt-lines{
        border-radius:5px;
        height:2px;
        background:#3382c0 !important;
        position:absolute;
        transform-origin:2px 2px;
        -ms-transform-origin:2px 2px; /* IE 9 */
        -webkit-transform-origin:2px 2px;
    }
    .col-md-12 {
        width: 100%;
        float: left;
    }
    .arrow1 {
        width: 2px;
        background-color: #000000 !important;
        height: 32px;
        margin: -20px auto 0;
    } 
    input[type=text] {
        background-color:#fff !important;
    }
/*Shading shape css start*/
.shadingshape td { background-color: white  !important; cursor: pointer; border:1px solid black; text-align: left; vertical-align: middle; width: 64px; height: 60px; text-align: center;}
.shadingshape table{width: auto; border-collapse: collapse;}
.shadingshape td.first-cell span {display: inline-block;width: 0;height: 0;border-style: solid;border-width: 0 0 59px 63px;border-color: transparent transparent #888888 transparent;}
.shadingshape td.second-cell span {display: inline-block;width: 0;height: 0;border-style: solid;border-width: 0 63px 59px 0;border-color: transparent transparent #888888 transparent;}
.shadingshape td.third-cell { background-color:#888888 !important; border-radius: 30px !important;}
.shadingshape td.forth-cell { background-color: #6b788b  !important;}
.shadingshape td.fifth-cell span{display:inline-block; width:0; height:0; border-style:solid; border-width: 0 63px 59px 0; border-color: transparent #888888 transparent transparent;}
.shadingshape td.sixth-cell span{display:inline-block; width:0; height:0; border-style:solid; border-width: 59px 63px 0 0; border-color: #888888 transparent transparent transparent;}
.shadingshape td.seventh-cell { background-color: #888888  !important;}
.shadingshape td span{vertical-align: top}

    
    @media print {
        @page {
            margin: 0mm;
        }
        body {font-size:11px!important;}
        div .page-break{
            page-break-inside: avoid;
        }
        .page-break { margin-top: 50px;display: block; page-break-before: always;page-break-after: always; }
    }

</style>

<div class="row" ng-app="printPopUp" ng-controller="printPopUpCtrl" style="margin-top: 50px;">

    <div style="width:100%;margin:0 auto;text-align:center;">

        <div style="float:left;">
            <!--left part-->
            <div style="float:left;width:89px;background:#1871C2!important;height:200px;margin-left:25px;margin-top:10px;"></div>
            <!--left ends-->
            <!--right part-->
            <div style="float:right;width:73%">
                <img src="/images/logo.png" alt=""/>
                <?php if (isset($subject)) { ?>
                    <p style="font-size:22px;color:#585958;text-align:left;font-weight:bold;"><?php echo $subject ?></p>
                    <?php
                }
                ?>
                <p style="font-size:22px;color:#585958;text-align:left;">Key Stage <?php echo $key_stage ?></p>
                <p style="font-size:22px;color:#585958;text-align:left;">Year Group <?php echo $year_group ?></p>
                <?php if (isset($paper)) { ?>
                    <p style="font-size:22px;color:#585958;text-align:left;"><?php echo $subjectPaperArray[$paper]['name'] ?></p>
                <?php } ?>
                <?php if (isset($strand_name)) { ?>
                    <p style="font-size:22px;color:#585958;text-align:left;"><?php echo $strand_name ?></p>
                    <p style="font-size:22px;color:#585958;text-align:left;"><?php echo $sub_strand_name ?></p>

                <?php } ?>


                <table class="table" style="width:100%" border="1">

                    <tbody>
                        <tr><td style="text-align: left;" colspan="4">First Name</td></tr>
                        <tr><td style="text-align: left;" colspan="4">Last Name</td></tr>
                        <tr><td style="text-align: left;">School Class</td></tr>
                        <?php if (isset($paper)) { ?>
                            <tr><td colspan="2" style="text-align:left; border-left:1px solid #fff; border-right:1px solid #fff;">Time : <?php echo $subjectPaperArray[$paper]['time'] / (60) ?> Minutes</td><td colspan="2" style=" border-bottom:1px solid  #ddd;">Calculator Not Allowed</td></tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
            <!--right ends-->
        </div>
    </div>
    <div class="portlet" ng-init="initData({{$questionsData}})" >
        <button class="btn btn-primary pull-right" type="button" ng-click="printPage('printQuestioObj')" id="print_btn">Print</button>
        <div class="portlet-body" id="printQuestioObj">

<!-- <img src="/images/demo.png" border="1" width='100%' height='820'/> -->
            <div ng-if="checkMode === 'question' || checkMode === 'revision_question'" ng-repeat="(setIndex, setele) in MASTERS.questions" style="clear:both;">
                <div ng-repeat="( quesIndex, quesele ) in setele.questions" style="margin-bottom:30px; float: left; width:100%;" class="page-break">

                    <!--<div ng-if="quesIndex === 0" class="p_margin" ng-if="setele.description">
                        <label ng-bind-html="setele.description | to_trusted" style="background: #c6cde6 !important; border-radius: 8px !important; float: left; font-size: 15px;font-weight:bold; margin: 0 !important; padding: 3px 10px 3px 16px; width: 98%; margin-right:20px !important; margin-left:20px !important;"></label> 
                    </div>-->

                    <div class="col-md-12">
                        <div class="col-md-12 p_margin" style=" float: left; width:100%">
                            <label class="pull-left" style="background:#000000 !important; border-radius: 8px !important; float: left; margin-right: 10px; padding: 7px 10px; position: relative;"><span style="color:#ffffff !important; font-size:11px !important;font-weight:bold !important; ">Question [%setIndex+1%].[%quesIndex+1%]&nbsp;</span>
                                <span style="display: block; position: absolute; font-size: 9px; width: 100%; bottom: -13px;">	&copy; SatsCompanion</span>
                            </label>
                            <div class="p_margin" ng-if="setele.description">
                                <div id="[%quesIndex%]" style="font-weight:bold; background:#c6cde6 !important; border-radius:8px !important;margin-left:116px; font-size:15px; font-weight:normal; padding: 7px 10px; width: 70%;" class="col-md-11" ng-bind-html="setele.description | to_trusted"></div>
                            </div>    
                            <div class="p_margin" ng-if="setele.description!=''">
                                <div id="[%quesIndex%]" style="font-weight:bold; border-radius:8px !important;background:#fff !important; margin-left:116px; margin-top:2px;font-size:11px; font-weight:normal; padding: 7px 10px; width: 70%;" class="col-md-11" ng-bind-html="quesele.ques | to_trusted"></div>
                            </div>
                            <div class="p_margin" ng-if="setele.description==''">
                                <div id="[%quesIndex%]" style="font-weight:bold; border-radius:8px !important;background:#c6cde6 !important; margin-left:116px; margin-top:2px;font-size:11px; font-weight:normal; padding: 7px 10px; width: 70%;" class="col-md-11" ng-bind-html="quesele.ques | to_trusted"></div>
                            </div>
                           
                            <div class="marks_box">
                                <div class="mark_div" style="font-size:11px;">[%quesele.mark%]</div>
                                <div ng-show="quesele.mark <= 1" style="font-size:11px;">mark</div>
                                <div ng-show="quesele.mark > 1" style="font-size:11px;">marks</div>
                            </div>
                            
                        </div>


                        <div ng-if="setele.question_type === 23" class="col-md-12" style="margin-top:20px;margin-left:100px;">
                            <div class="col-xs-12">
                                <div ng-controller="reflectionCtrl" ng-init="init(setIndex, quesIndex)">
                                    <div class="questionpatter" id="prepatternAnswer_ans_[%setele.id%]_[%quesIndex%]" ></div>
                                    <div class="questionpatter" style="border-left:1px solid #888888; height:100%; position:absolute;box-shadow: 0px 0px 10px #888888;"></div>
                                    <div class="questionpatter" id="prepatternContainer_ques_[%setele.id%]_[%quesIndex%]"></div>
                                </div>
                            </div>
                        </div>

                        <div ng-if="setele.question_type === 24" class="col-md-12" style="margin-top:20px;padding-left:120px;">
                            <div ng-controller="reflectionCtrl" ng-init="init(setIndex, quesIndex)">
                                <div class="col-xs-12">
                                    <div id="prepatternAnswer_ans_[%setele.id%]_[%quesIndex%]"></div>
                                    <div style="border:2px solid #888888; width:340px; position:absolute; box-shadow:0px 0px 10px #888888;"></div>
                                    <div id="prepatternContainer_ques_[%setele.id%]_[%quesIndex%]"></div>
                                </div>
                            </div>
                        </div>

                        <div ng-if="setele.question_type === 25" class="col-md-12" style="margin-top:20px;padding-left:120px;">
                            <div ng-controller="reflectionCtrl" ng-init="init(setIndex, quesIndex)">
                                <div class="col-xs-12">
                                    <div id="prepatternContainer_ques_[%setele.id%]_[%quesIndex%]"></div>
                                    <div style="border:2px solid #888888; width:340px; position:absolute; box-shadow:0px 0px 10px #888888;"></div>
                                    <div id="prepatternAnswer_ans_[%setele.id%]_[%quesIndex%]"></div>
                                </div>
                            </div>
                        </div>

                        <div ng-if="setele.question_type === 26" class="col-md-12" style="margin-top:20px;padding-left:0px;">
                            <div ng-controller="reflectionCtrl" ng-init="init(setIndex, quesIndex)">
                                <div class="questionpatter" id="prepatternAnswer_ans_[%setele.id%]_[%quesIndex%]" style="float:left !important"></div>
                                <div class="questionpatter" style="float:left"><img src="/images/leftdiagonal.jpg" width="130px" height="340px"></div>
                                <!--<div class="questionpatter" style="background-image: url(/images/leftdiagonal.jpg); height:340px; width:130px;display: list-item;list-style-position: inside; list-style-image: url(/images/leftdiagonal.jpg);"></div>-->
                                <div class="questionpatter"  style="float:left !important" id="prepatternContainer_ques_[%setele.id%]_[%quesIndex%]"></div>
                            </div>
                        </div>

                        <div ng-if="setele.question_type === 27" class="col-md-12" style="margin-top:20px;padding-left:0px;">
                            <div ng-controller="reflectionCtrl" ng-init="init(setIndex, quesIndex)">
                                <div class="questionpatter" id="prepatternContainer_ques_[%setele.id%]_[%quesIndex%]"></div>
                                <div class="questionpatter" style="float:left"><img src="/images/rightdiagonal.jpg" width="130px" height="340px"></div>
                                <!--<div class="questionpatter" style="border-left:2px solid #888888; height:100%; position:absolute;box-shadow: 0px 0px 10px #888888;" ></div>-->
                                <div class="questionpatter" id="prepatternAnswer_ans_[%setele.id%]_[%quesIndex%]"></div>
                            </div>
                        </div>

                        <div ng-if="setele.question_type === 17" class="col-md-12" style="margin-top:20px;padding-left:95px;">
                            <div ng-controller="reflectionCtrl" ng-init="init(setIndex, quesIndex)">
                                <div class="questionpatter" id="prepatternContainer_ques_[%setele.id%]_[%quesIndex%]"></div>
                                <div class="questionpatter" style="border-left:2px solid #888888; height:100%; position:absolute;box-shadow: 0px 0px 10px #888888;"></div>
                                <div class="questionpatter" id="prepatternAnswer_ans_[%setele.id%]_[%quesIndex%]"></div>
                            </div>
                        </div>

                        <div ng-if="setele.question_type === 28" class="col-md-12" style="margin-top:20px;padding-left:114px;">
                            <div class="col-xs-12">
                                <img src="/questionimg/[%quesele.option.value%]" border="1"/>
                            </div>
                            <div class="col-xs-12 pad_left margin-top-10" ng-repeat="eleAnswer in setele.userresponse[quesIndex]">
                                <div class="col-xs-2">[%eleAnswer.label%]</div>
                                <div class="col-xs-2 col-xs-pull-1">
                                    <input type="text"/>
                                </div>
                            </div>
                        </div>

                        <div ng-if="setele.question_type === 2" class="col-md-12" style="margin-top:10px;margin-left:10px;">
                            <div class="col-xs-12 pad_left" ng-repeat="ele in setele.questions[$index].option">
                                <div  style="display: flex; padding-left:40px;">
                                    <input style="opacity:1;" type="checkbox" name="multiplechoicecheckbox">
                                    <div class="col-xs-12 pad_left_10 text-left" style="display: inline-block;" ng-bind-html="ele.option | to_trusted"></div>
                                </div>
                            </div>
                        </div>
                        <div ng-if="setele.question_type === 1" class="col-md-12" style="margin-top:10px;margin-left:10px;">
                            <div class="col-xs-12 pad_left" ng-repeat="ele in setele.questions[$index].option">
                                <div  style="display: flex;padding-left:40px;">
                                    <input style="opacity:1; margin-left:-7px;" type="radio"  name="singlechoiceradio">
                                    <div class="col-xs-12 pad_left_10 text-left" style="display: inline-block;" ng-bind-html="ele.option | to_trusted"></div>
                                </div>
                            </div>
                        </div>

                        <div ng-if="setele.question_type === 3" class="col-md-12" style="margin-top:10px;">
                            <div class="col-xs-12" ng-bind-html="quesArray.questions[quesIndex].ques | to_trusted" ></div>
                        </div>
                        <div ng-if="setele.question_type === 4" class="col-md-12" style="margin-top:10px;">
                            <!-- <div class="row base">
                                <div class="col-xs-12 pad_left">
                                    <div class="col-xs-3 text-center"><b>[%setele.questions[quesIndex].header.left%]</b></div>
                                    <div class="col-xs-3 text-center"><b>[%setele.questions[quesIndex].header.right%]</b></div>
                                </div>
                            </div> -->
                            <div class="row margin-bottom-20">
                                <div class="col-xs-12 pad_left" id="presetheight[%setele.id%]">
                                    <div id="svgbasics"></div>
                                    <div id="pnlAllIn" style="float:left;width:80%">
                                        <div class="col-xs-12">
                                            <div class="col-xs-4 text-center">
                                                <h4><b>[%setele.questions[quesIndex].header.left%]</b></h4>
                                            </div>
                                            <div class="col-xs-4 col-xs-push-2 text-center">
                                                 <h4><b>[%setele.questions[quesIndex].header.right%]</b></h4>
                                            </div>
                                        </div>
                                        <div id="leftPanel" style="float:left;width:45%;margin:0;padding:0 0 0 25px;">
                                            <div class="person [%leftele.cls%]" ng-repeat="leftele in setele.questions[quesIndex].option" style="width: 220px;">
                                                <input type="hidden" value="[%leftele.id%]" />
                                                <ul class="name" style="float:left;margin:0;padding:0;width:100%;"><li style="float:left;width:100%;float:left;width:100%;margin:0;padding:0;"><h2 ng-bind-html="leftele.left | to_trusted"></h2></li></ul>
                                            </div>
                                        </div>
                                        <div id="rightPanel" style="float:right;width:45%;margin:0;padding:0 0 0 25px;">
                                            <div class="person [%rightele.cls%]" id="r_[%quesIndex%]_[%setele.id%]_[%$index%]"  style="width: 220px;" ng-repeat="rightele in setele.questions[quesIndex].option" my-repeat-directive>
                                                <input type="hidden" value="[%rightele.id%]" />
                                                <ul class="name" style="float:left;margin:0;padding:0;width:100%;"><li style="float:left;width:100%;margin:0;padding:0;"><h2 ng-bind-html="rightele.right | to_trusted"></h2></li></ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>			       
                                </div>
                            </div>
                        </div>
                        <div ng-if="setele.question_type === 7" class="col-md-12" >
                            <div class="col-xs-11 margin-top-15" style="margin-bottom:12px;padding-left:20px;">
                                <span  class="draggable" style="border: 1px solid #000000; background-color:#edf6ff; padding: 1px 10px 8px 10px; margin:5px" ng-repeat="dragele in dragObjArray">[%dragele%]</span>
                            </div>
                        </div>

                        <div ng-if="setele.question_type === 8" class="col-md-12" style="margin-top:10px;">
                            <div class="col-xs-12">

                            </div>
                        </div>

                        <div ng-if="setele.question_type === 9" class="col-md-12" style="margin-top:10px;margin-left:80px">
                            <div class="col-xs-12">
                                
                            </div>
                        </div>
                        <div ng-if="setele.question_type === 6" class="col-md-12" style="margin-top:10px;margin-left:114px">        

                            <div class="col-xs-4 ">
                                <input type="text" class="form-control margin-bottom-10" style="height: 28px;padding-top:10px;">
                            </div>                        
                        </div>    

                        <div ng-if="setele.question_type === 10" class="col-md-12" style="margin-top:10px;margin-bottom:10px;">                   
                            <div class="col-xs-12 pad_left">

                                <table>											
                                    <tr ng-repeat="(pIndex, subchildele) in setele.questions[quesIndex].option track by pIndex">
                                        <td ng-repeat="field in subchildele track by $index">
                                            <span ng-if="!field.title">
                                                <input type="checkbox" ng-hide="true" class="custom_[%setele.questions[quesIndex].questype%]" id="checkbox[%pIndex%][%$index%]" 
                                                       ng-click="updateValuestemp(setele.questions[quesIndex].questype, parentIndex, pIndex, $index)" name="checkbox[% pIndex %]"/>
                                                <label for="checkbox[%pIndex%][%$index%]"><span></span>
                                                    <div ng-show="setele.questions[quesIndex].questype != 'input'" class="pull-right" style="font-weight:normal; padding-left:2px;" ng-bind-html="field.value | to_trusted"></div>
                                                    <input ng-show="setele.questions[quesIndex].questype == 'input'" type="text" style="width: auto; font-weight:normal;" />
                                                </label>		
                                            </span>
                                            <span ng-if="field.title">
                                                <div style="font-weight:normal; padding-left:2px;" ng-bind-html="field.value | to_trusted"></div>
                                            </span>
                                        </td>
                                        <td ng-show="false">
                                            <a ng-hide="element.option.length < 4 || pIndex === 0">
                                                <span class="glyphicon glyphicon-remove btn-cursor-pointer btn-font-18 pull-right" tooltip="Remove" style="padding-top: 2px;padding-right: 10px;"></span>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr ng-show="false">
                                        <td class="remove-tr-border" ng-repeat="field in setele.questions[quesIndex].option[0]">
                                            <a ng-hide="setele.questions[quesIndex].option[0].length < 4" ng-if="$index != 0">
                                                <span class="glyphicon glyphicon-remove btn-cursor-pointer btn-font-18 pull-right" tooltip="Remove" style="padding-top: 2px;padding-right: 10px;"></span>
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>



                        <div ng-if="setele.question_type === 11" class="col-md-12" style="margin-top:10px;">
                            <div class="row" id="setheight[%quesIndex%]">
                                
                                <div class="" ng-if="setele.questions[quesIndex].option.view === 'vertical' && !setele.questions[quesIndex].option.dragdrop">
                                    <div class="col-xs-6 col-xs-push-3 text-center">
                                        <b>[%setele.questions[quesIndex].option.header.prefix%]</b>
                                    </div>
                                    <div class="col-xs-12" >
                                        <div class="col-xs-6 col-xs-push-3" ng-class="{ 'row': setele.questions[quesIndex].option.dragdrop,'sortable row' : setele.questions[quesIndex].option.dragdrop === false}" >
                                            <div class="sorting-answer custom-drag-ele"  ng-repeat="ele in setele.questions[quesIndex].option.optionvalue">	
                                                <span ng-class="{'': setele.questions[quesIndex].option.dragdrop,'glyphicon glyphicon-sort btn-cursor-pointer btn-font-18' : quesArray.questions[quesIndex].option.dragdrop === false}"></span>	
                                                <span style="display: inline-block;" ng-bind-html="ele.value | to_trusted"></span>	
                                            </div>	  
                                        </div>
                                    </div>                
                                    <div class="col-xs-12 pad_left" >
                                        <div class="col-xs-6 col-xs-push-3 text-center">
                                            <b>[%setele.questions[quesIndex].option.header.postfix%]</b>
                                        </div>
                                    </div>
                                </div> 

                                <div class="col-xs-12 pad_left" ng-if="setele.questions[quesIndex].option.view === 'vertical' && setele.questions[quesIndex].option.dragdrop">

                                    <div class="col-xs-5" ng-class="{ 'row': setele.questions[quesIndex].option.dragdrop,'sortable row' : setele.questions[quesIndex].option.dragdrop === false}" >
                                        <div class="col-xs-12 text-center" style="margin-bottom: 5px; padding-top: 8px; min-height: 25px; padding-left: 5px;"></div>
                                        <div class="col-xs-12">
                                            <div class="custom-drag-ele" id="[%quesIndex%]" style="margin-bottom:5px;" ng-repeat="ele in setele.questions[quesIndex].option.optionvalue" my-repeat-directive2>	
                                                <span style="display:inline-block; width:100%!important; cursor:crosshair;margin-bottom:0px;" class="sorting-answer draggable " ng-bind-html="ele.value | to_trusted"></span>	
                                            </div>                        
                                        </div>
                                        <div class="col-xs-12 text-center" style="margin-bottom: 5px; padding-top: 8px; min-height: 25px; padding-left: 5px;"></div>
                                    </div>


                                    <div class="col-xs-5">
                                        <div class="col-xs-12 text-center" style="margin-bottom: 5px; padding-top: 8px; min-height: 25px; padding-left: 5px;">
                                            <b>[%setele.questions[quesIndex].option.header.prefix%]</b>
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="sorting-answer droppable" style="min-height:40px;" ng-repeat="ele in setele.questions[quesIndex].option.optionvalue">
                                                <span class="pull-right "></span>	
                                            </div>
                                        </div>
                                        <div class="col-xs-12 text-center" style="margin-bottom: 5px; padding-top: 8px; min-height: 25px; padding-left: 5px;">
                                            <b>[%setele.questions[quesIndex].option.header.postfix%]</b>
                                        </div>
                                    </div>  
                                </div>

                                <div class="col-xs-12 pad_left" ng-if="setele.questions[quesIndex].option.view == 'horizontal' && !setele.questions[quesIndex].option.dragdrop">
                                    <div class="col-xs-2 text-center">
                                        <b>[%setele.questions[quesIndex].option.header.prefix%]</b>
                                    </div>
                                    <div class="col-xs-8" ng-class="{ '': setele.questions[quesIndex].option.dragdrop,'sortable row' : setele.questions[quesIndex].option.dragdrop === false}">
                                        <div class="sorting-answer custom-drag-ele col-sm-2" style="margin-right:5px;float:left"  ng-repeat="ele in setele.questions[quesIndex].option.optionvalue">	
                                            <span ng-class="{'': setele.questions[quesIndex].option.dragdrop,'glyphicon glyphicon-move btn-cursor-pointer btn-font-18' : setele.questions[quesIndex].option.dragdrop === false,}"></span>
                                            <span style="display: inline-block;" ng-bind-html="ele.value | to_trusted" ng-class="{ 'draggable': setele.questions[quesIndex].option.dragdrop}"></span>
                                        </div>					
                                    </div>
                                    <div class="col-xs-2 text-center">
                                        <b>[%setele.questions[quesIndex].option.header.postfix%]</b>
                                    </div>
                                </div>

                                <div class="col-xs-12 pad_left" ng-if="setele.questions[quesIndex].option.view == 'horizontal' && setele.questions[quesIndex].option.dragdrop" >
                                    <div class="col-xs-12">
                                        <div class="col-xs-2 text-center"></div>
                                        <div class="col-xs-8">
                                            <div class="col-xs-12">
                                                <div class="" id="[%quesIndex%]" ng-repeat="ele in setele.questions[quesIndex].option.optionvalue" my-repeat-directive2>
                                                    <div class="custom-drag-ele" style="margin-bottom:5px;">	
                                                        <span class="sorting-answer" style="display: inline-block;width: 100%;cursor:crosshair;margin-bottom:0px;" ng-bind-html="ele.value | to_trusted" ng-class="{ 'draggable': setele.questions[quesIndex].option.dragdrop}"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-2 text-center"></div>
                                    </div>
                                    <div class="col-xs-12 pad_left">		
                                        <div class="col-xs-2 text-center">
                                            <b>[%setele.questions[quesIndex].option.header.prefix%]</b>
                                        </div>
                                        <div class="col-xs-8">
                                            <div class="col-xs-12">
                                                <div class="col-sm-2 overwrite-padding" ng-repeat="ele in setele.questions[quesIndex].option.optionvalue">
                                                    <div class="sorting-answer droppable" style="min-height:25px;">	
                                                        <span style="display: inline-block;" ></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-2 text-center">
                                            <b>[%setele.questions[quesIndex].option.header.postfix%]</b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div ng-if="setele.question_type === 12" class="col-md-12" style="margin-top:10px;">  
                            <div class="col-xs-12 pad_left">
                                <ol ng-if="setele.questions[quesIndex].option.view === 'vertical'" class="margin-padding-none" style="list-style-type:none;padding-left:40px;">
                                    <li style="padding-bottom:20px;" ng-repeat="ele in setele.questions[quesIndex].option.optionvalue">
                                        <span class="btn-cursor-pointer" name="addDrawClass" ng-bind-html="ele.value | to_trusted"></span>
                                    </li>
                                </ol>
                                <ol ng-if="setele.questions[quesIndex].option.view === 'horizontal'" class="margin-padding-none" style="list-style-type:none;padding-left:40px;">
                                    <li style="float:left; padding-right:25px;" ng-repeat="ele in setele.questions[quesIndex].option.optionvalue">
                                        <span class="btn-cursor-pointer" name="addDrawClass" ng-bind-html="ele.value | to_trusted"></span>
                                    </li>
                                </ol>
                            </div>  
                        </div> 

                        <div ng-if="setele.question_type === 13" class="col-md-12" style="margin-top:10px;padding-left:55px;">     
                            <div class="row">
                                <div class="col-xs-11 " ng-repeat="ele in setele.questions[quesIndex].option">
                                    <div style="display: flex;padding-left:100px">
                                        <div style="padding-right: 10px; display: inline-block;">
                                            <input type="radio" name="booleanradio[%parentIndex%]" id="booleanradio[%parentIndex%][%$index%]" value="[%ele.option%]" ng-click="setShowmode();" class=" ng-show" / >
                                                   <label for="booleanradio[%parentIndex%][%$index%]"><span>&nbsp;</span></label>
                                        </div>
                                        <div style="display: inline-block;" ng-bind-html="ele.option | to_trusted"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row base" ng-show="setele.questions[quesIndex].showsmultiple">
                                <div class="col-xs-12 pad_left">
                                    <div style="display: inline-block;" ng-bind-html="setele.questions[quesIndex].elsequestext | to_trusted" ></div>
                                </div>
                            </div>

                            <div class="row base" ng-show="setele.questions[quesIndex].showsmultiple" ng-repeat="childEle in setele.questions[quesIndex].elseques">
                                <div class="col-xs-12" style="display: flex;padding-left:114px;">
                                    <div style="padding-right: 10px; display: inline-block;">
                                        <input type="checkbox" name="booleancheckbox[%parentIndex%]" id="booleancheckbox[%parentIndex%][%$index%]" class="ng-show" />
                                        <label for="booleancheckbox[%parentIndex%][%$index%]"><span>&nbsp;</span></label>
                                    </div>
                                    <div style="display: inline-block;" ng-bind-html="setele.questions[quesIndex].elseques[$index].value | to_trusted" ></div>
                                </div>	
                            </div>                        
                        </div>
                        <div ng-if="setele.question_type === 14" class="col-md-12" style="margin-top:10px;padding-left:114px;"> 
                            <div class="col-xs-12">
                                <span style="float:left;position: relative;margin-right:5px;" ng-repeat="childEleA in setele.questions[quesIndex].option"  >
                                    <span ng-class="{'spandecoration': childEleA.ischeck}" ng-bind-html="childEleA.option | to_trusted"></span>

                                    <div style="width:2px;background-color:#000000 !important;height: 32px;margin:-1px auto 0;" ng-if="childEleA.ischeck"></div>
                                    <input name="answer_[%quesIndex%][%$index%]" id="answer_[%quesIndex%][%$index%]" type="text" style="width:52px;" ng-if="childEleA.ischeck">
                                </span>
                            </div>                        
                        </div> 


                        <div ng-if="setele.question_type === 15" class="col-md-12" style="margin-top:10px;margin-left:114px;">               
                            <div class="row margin-bottom-20">
                                <div class="col-xs-9" ng-if="setele.questions[quesIndex].literacyType == 'drag'" style="margin-top:10px;margin-bottom: 10px; padding-left:114px;">

                                    <span class="draggable " ng-repeat="ele in setele.questions[quesIndex].answerOption" class="" style="border: 1px solid #000000; padding:5px; margin:5px; z-index: 1;">[%ele.value%]</span>	
                                </div>
                                <div class="col-xs-12 pad_left">
                                    <span style="float:left;position: relative;margin-right:5px; text-align:center" ng-repeat="childEleA in setele.questions[quesIndex].option"  >
                                        <span ng-class="{'spandecoration': childEleA.ischeck}" ng-bind-html="childEleA.option | to_trusted"></span>
                                        <div style="width:2px;background-color:#000000 !important;height: 32px;margin: -1px auto 0;" ng-if="childEleA.ischeck"></div>
                                        <input name="answer_[%parentIndex%][%$index%]" id="answer_[%parentIndex%][%$index%]" type="checkbox" class="" ng-if="childEleA.ischeck && setele.questions[quesIndex].literacyType == 'tick'">
                                        <label ng-if="childEleA.ischeck && setele.questions[quesIndex].literacyType == 'tick'" for="answer_[%parentIndex%][%$index%]"><span style="margin: -11px 3px 0 4px; !important"></span></label>
                                        <input class="droppable" name="answer_[%parentIndex%][%$index%]" id="answer_[%parentIndex%][%$index%]" type="text" style="width:52px; border:1px solid #2386DB" ng-if="childEleA.ischeck && setele.questions[quesIndex].literacyType == 'drag'">
                                    </span>
                                </div>
                            </div>                        
                        </div> 
                        <div ng-if="setele.question_type === 16" class="col-md-12" style="margin-top:10px;">
                            <div class="row margin-bottom-20">
                                <div class="col-xs-12 pad_left margin-bottom-20">
                                    <div style="width:600px;height:500px;">
                                        <img ng-src="/questionimg/[%setele.questions[quesIndex].option.value%]"/>
                                    </div>
                                </div>
                                <div class="col-xs-12 pad_left" ng-repeat="eleAnswer in setele.questions[quesIndex].correctAns">
                                    <div class="col-xs-4">
                                        [%eleAnswer.label%]
                                    </div>
                                    <div class="col-xs-4 col-xs-pull-1 margin-bottom-10">
                                        <input type="text" class="btn-input-style text-center" style="height:20px;"/>
                                    </div>
                                </div>
                            </div>
                        </div>     

                        <div ng-if="setele.question_type === 18" class="col-md-12" style="margin-top:10px;margin-left:10px;">
                            <div ng-controller="joiningdotsCtrl" ng-init="init(setIndex, quesIndex)" >
                                <div class="col-xs-12">
                                    <div class="fileinput-preview" style="background-image:url(/questionimg/[%setele.questions[quesIndex].imgPath%]) !important;background-size: 100% 100% !important;background-repeat: no-repeat; !important" id="prepatternContainer_[%setele.id%]_[%quesIndex%]"></div>
                                </div>                        
                            </div>                        
                        </div> 
                        <div ng-if="setele.question_type === 19" class="col-md-12" style="margin-top:10px;margin-left:10px;">

                            <div ng-controller="drawlineonimageCtrl" ng-init="init(setIndex, quesIndex)" style="margin:0 auto;width:600px;">

                                <div class="col-xs-12 margin-top-15 margin-bottom-20" style="width: 600px; height: 500px;">
                                    <div><canvas id="preCanvasImg_[%setele.id%]_[%quesIndex%]" width="600" height="500"></canvas></div>
                                    <div style="top: 0px;position: absolute; border:1px solid #e5e5e5;">
                                        <canvas id="preCanvas_[%setele.id%]_[%quesIndex%]" width="600" height="500"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div ng-if="setele.question_type === 20" class="col-md-12 pad_left" style="margin-top:10px;">

                            <div ng-controller="measurementCtrl" ng-init="init(setIndex, quesIndex)" style="margin:0 auto;width:600px;">
                                <div class="col-xs-12">
                                    <div class="col-xs-10">
                                        <canvas id="measurementCanvasPre_[%setele.id%]_[%quesIndex%]" height="[%canvasHeigth%]" width="[%canvasWidth%]"></canvas>
                                    </div>
                                </div>  
                            </div>

                        </div>          
                        <div ng-if="setele.question_type === 21" class=" col-md-12" style="margin-top:10px;">                        
                            <div class="row margin-bottom-20" style="margin:0 auto;width:600px;">
                                <div class="col-xs-12 shadingshape" id="preShadingShape[%quesIndex%]">
                                    <div class="col-xs-12" style="padding-left:114px;">
                                        <table cellspacing="0" cellpadding="0">
                                            <tr ng-repeat="(trPIndex, elePtr) in  setele.questions[quesIndex].option.optionval track by $index">
                                                <td class="[%setele.questions[quesIndex].option.optionval[trPIndex][tdPIndex].class%]" id="correct_[%quesIndex%]_[%trPIndex%]_[%tdPIndex%]" ng-repeat="(tdPIndex, elePtd) in  elePtr track by $index">
                                                    <span>&nbsp;</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>                        
                        </div>                        
                        <div ng-if="setele.question_type === 22" class="col-md-12" style="margin-top:10px;">
                            <div class="col-xs-12" style="margin:0 auto;width:600px;">
                                <div id="droppreanswer" class="col-xs-12" style="width: 600px;">
                                    <div class="fileinput-preview" style="border:1px solid #ffffff; width:600px; display: list-item;list-style-position: inside; background-image: url([%'/questionimg/'+ setele.questions[quesIndex].imgPath%]); list-style-image: url([%'/questionimg/'+ setele.questions[quesIndex].imgPath%])"></div>
                                    <span ng-repeat="optionele in setele.questions[quesIndex].option" style="float:left; position:absolute; top:[%optionele.position.top%]; left:[%optionele.position.left%];">
                                        <div class="droppable" id="label_[%quesIndex%]_[%$index%]" type="text" style="float:left; text-align:center; font-weight:bold; width:[%optionele.position.width%]; height:[%optionele.position.height%];"></div>
                                    </span>
                                </div>
                            </div> 
                        </div>

                        <div ng-if="setele.question_type === 29" class="col-md-12" style="margin-top:10px;">  
                            <div class="col-md-12" style="margin:0 auto;width:350px;">
                                <div class="col-xs-6">
                                    <div id="preanswer[%quesIndex%]" style="background-image: url('/questionimg/[%quesele.option.value%]');background-repeat: no-repeat;width:600px; height:500px;margin-top:20px;display: list-item;list-style-position: inside;list-style-image: url('/questionimg/[%quesele.option.value%]')"></div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-xs-6" style="padding-left:114px;">
                                    <div class="col-xs-12 angular ">
                                        <span class="chart" easypiechart percent="percentpre" options="options"></span>
                                        <div class="list piechart-bullet-bold" ng-click="updatepre(100, quesIndex)" style="top:-20px; left:118px;"></div>
                                        <div class="list piechart-bullet" ng-click="updatepre(5, quesIndex)" style="top:-14px; left:160px;"></div>
                                        <div class="list piechart-bullet" ng-click="updatepre(10, quesIndex)" style="top:2px; left:190px;"></div>
                                        <div class="list piechart-bullet" ng-click="updatepre(15, quesIndex)" style="top:28px; left:215px;"></div>
                                        <div class="list piechart-bullet" ng-click="updatepre(20, quesIndex)" style="top:60px; left:232px;"></div>
                                        <div class="list piechart-bullet-bold" ng-click="updatepre(25, quesIndex)" style="top:96px; left:240px;"></div>
                                        <div class="list piechart-bullet" ng-click="updatepre(30, quesIndex)" style="top:132px; left:236px;"></div>
                                        <div class="list piechart-bullet" ng-click="updatepre(35, quesIndex)" style="top:165px; left:222px;"></div>
                                        <div class="list piechart-bullet" ng-click="updatepre(40, quesIndex)" style="top:195px; left:195px;"></div>
                                        <div class="list piechart-bullet" ng-click="updatepre(45, quesIndex)" style="top:215px; left:162px;"></div>
                                        <div class="list piechart-bullet-bold" ng-click="updatepre(50, quesIndex)" style="top:225px; left:118px;"></div>
                                        <div class="list piechart-bullet" ng-click="updatepre(55, quesIndex)" style="top:220px; left:80px;"></div>
                                        <div class="list piechart-bullet" ng-click="updatepre(60, quesIndex)" style="top:202px; left:49px;"></div>
                                        <div class="list piechart-bullet" ng-click="updatepre(65, quesIndex)" style="top:176px; left:20px;"></div>
                                        <div class="list piechart-bullet" ng-click="updatepre(70, quesIndex)" style="top:142px; left:2px;"></div>
                                        <div class="list piechart-bullet-bold" ng-click="updatepre(75, quesIndex)" style="top:100px; left:-4px;"></div>
                                        <div class="list piechart-bullet" ng-click="updatepre(80, quesIndex)" style="top:66px; left:1px;"></div>
                                        <div class="list piechart-bullet" ng-click="updatepre(85, quesIndex)" style="top:35px; left:15px;"></div>
                                        <div class="list piechart-bullet" ng-click="updatepre(90, quesIndex)" style="top:8px; left:42px;"></div>
                                        <div class="list piechart-bullet" ng-click="updatepre(95, quesIndex)" style="top:-10px; left:76px;"></div>
                                    </div>

                                </div>
                            </div>

                        </div>       

                        <div ng-if="setele.question_type === 30" class="col-md-12" style="margin-top:10px;padding-left:114px;">  
                            <div ng-controller="symmetricCtrl" ng-init="init(setIndex, quesIndex)">
                                <div class="col-xs-12 margin-bottom-20">
                                    <span style="margin-left:20px;">
                                        <input type="checkbox"  ng-model="tempcorrectAns[0].ischeck" class="text-center"/>
                                        <img src="/images/icon-1.gif" alt="">
                                    </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <span>
                                        <input type="checkbox" ng-model="tempcorrectAns[1].ischeck" class="text-center"/>
                                        <img src="/images/icon-2.gif" alt="">
                                    </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <span>
                                        <input type="checkbox"  ng-model="tempcorrectAns[2].ischeck" class="text-center"/>
                                        <img src="/images/icon-3.gif" alt="">
                                    </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <span>
                                        <input type="checkbox" ng-model="tempcorrectAns[3].ischeck" class="text-center"/>
                                        <img src="/images/icon-4.gif" alt="">
                                    </span>
                                </div>
                                <div class="col-xs-12">
                                    <div class="image_box border_1_big" ng-show="tempcorrectAns[0].ischeck"></div>
                                    <div class="image_box border_2_big" ng-show="tempcorrectAns[1].ischeck"></div>
                                    <div class="image_box border_3_big" ng-show="tempcorrectAns[2].ischeck"></div>
                                    <div class="image_box border_4_big" ng-show="tempcorrectAns[3].ischeck"></div>
                                    <div style="width:600px; height:500px;background-image: url([%'/questionimg/'+ setele.questions[quesIndex].imgPath%]);background-size: 100% 100%;background-repeat: no-repeat;" id="prepatternContainerSymetry_[%setele.id%]_[%quesIndex%]"></div>
                                </div>
                            </div>                        
                        </div>

                        <div ng-if="setele.question_type === 31" class="col-md-12" style="margin-top:10px;">  
                            <div class="row">
                                <div class="col-xs-12"></div>
                                <div class="col-xs-12">
                                    <div class="col-xs-12 margin-top-15 margin-bottom-20" style="width: 600px;">
                                        <div class="fileinput-preview thumbnail" style="border:1px solid #ffffff;width:600px  !important; height:500px  !important; background-image: url([%'/questionimg/'+ setele.questions[quesIndex].imgPath%]) !important; background-repeat: no-repeat  !important;"></div>
                                        <span ng-repeat="optionele in setele.questions[quesIndex].option" style="position:absolute;  top:[%optionele.position.top%]; left:[%optionele.position.left%];">
                                            <input type="text" class="text-center" style="background:transparent; border:0px solid #ffffff; border-radius:5px !important; font-size:25px; font-weight:bold; color:#000000; width:[%optionele.position.width%]; height:[%optionele.position.height%];"></input>
                                        </span>

                                    </div>
                                </div>
                            </div>                        
                        </div>
                        <div ng-if="setele.question_type === 32" class="col-md-12" style="margin-top:10px;">  
                            <div class="row margin-bottom-20">
                                <div class="col-xs-12">
                                    <table class="inputentryrenderAns">           
                                        <tr ng-repeat="(pIndex, subchildele) in setele.questions[quesIndex].option track by pIndex">
                                            <td ng-hide="(field.title && !setele.questions[quesIndex].headercol && pIndex == 0) || (field.title && !setele.questions[quesIndex].headerrow && $index == 0)" ng-repeat="field in subchildele track by $index">
                                                <span ng-if="!field.title">
                                                    <input type="checkbox" class="custom_[%setele.questions[quesIndex].questype%]" name="checkbox[%quesIndex%][%pIndex%][%$index%]" ng-model="field.checkvalue"/>
                                                    <label for="checkbox[%quesIndex%][%pIndex%][%$index%]"><span></span>
                                                        <input ng-model="setele.questions[quesIndex].option[pIndex][$index].value" ng-disabled="setele.questions[quesIndex].option[pIndex][$index].checkvalue" type="text" style="width: auto; font-weight:normal;"/>
                                                    </label>  
                                                </span>
                                                <span ng-if="field.title">
                                                    <div style="font-weight:normal; padding-left:2px;" ng-bind-html="field.value | to_trusted"></div>
                                                </span>
                                            </td>
                                            <td ng-show="false">
                                                <a ng-click="removeRow(quesIndex, pIndex)" ng-hide="element.option.length < 4 || pIndex === 0">
                                                    <span class="glyphicon glyphicon-remove btn-cursor-pointer btn-font-18 pull-right" tooltip="Remove" style="padding-top: 2px;padding-right: 10px;"></span>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr ng-show="false">
                                            <td class="remove-tr-border" ng-repeat="field in setele.questions[quesIndex].option[0]">
                                                <a ng-click="removeColumn(quesIndex, $index)" ng-hide="element.option[0].length < 4" ng-if="$index != 0">
                                                    <span class="glyphicon glyphicon-remove btn-cursor-pointer btn-font-18 pull-right" tooltip="Remove" style="padding-top: 2px;padding-right: 10px;"></span>
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>                        
                        </div>


                    </div>
                </div>
            </div> 

        </div>
        <!--end profile-settings-->
    </div>
         
    <!-- END PAGE CONTENT-->
    @stop
    @section('pagescripts')
    {!! HTML::style('bower_components/custom/css/main.css') !!}
    {!! HTML::style('bower_components/dotsjoin/dotsJoin.css') !!}
    {!! HTML::style('bower_components/Reflection/Reflection.css') !!}
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    {!! HTML::script('bower_components/angular/angular.js') !!}
    {!! HTML::script('bower_components/angular-animate/angular-animate.js') !!}
    {!! HTML::script('bower_components/angular-route/angular-route.js') !!}
    {!! HTML::script('bower_components/angular-flash-alert/dist/angular-flash.js') !!}
    <!-- endbuild -->
    <script src="//cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML&dummy=.js"></script>
    <!-- build:js({.tmp,app}) scripts/scripts.js -->

    {!! HTML::script('bower_components/custom/js/printquestion.js') !!}

    {!! HTML::script('bower_components/fabric/fabric.js') !!}
    {!! HTML::script('bower_components/dotsjoin/dotsJoin.js') !!}
    {!! HTML::script('bower_components/Reflection/Reflection.js') !!}
    {!! HTML::script('bower_components/easypie/easypie.js') !!}
    {!! HTML::script('bower_components/easypie/angular.easypiechart.js') !!}
    <!-- END PAGE CONTENT-->
    @stop