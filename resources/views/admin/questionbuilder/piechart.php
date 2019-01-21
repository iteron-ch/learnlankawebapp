<div class="main_panel" ng-controller="piechartCtrl">
    <form name="piechartForm" id="piechartForm" class="margin-bottom-10">
        <div class="row">
            <div class="col-xs-12">
                <div class="row form-header" >
                    <div class="col-xs-12">
                        <div class="col-xs-12 checkbox">
                            <label><input type="checkbox" ng-model="oneAtATime">[%MASTERS.keywords['singlequestionexpand']%]</label>
                        </div>						
                    </div>
                </div>
            </div>
        </div>
        <!--Description section start here!-->
        <div class="row">
            <div class="col-xs-12 animate-hide" style="margin:10px 10px 10px 0px;">
                <label style="float:right;margin-right:15px;">
                    <input type="checkbox" value='true' ng-model='questionJSON.descvisible'>[%MASTERS.keywords['description']%]
                </label>
                <div class="col-xs-12 questionareawidth">
                    <textarea ng-model="questionJSON.description" name="descriptionText" placeholder="Write your Section description here"></textarea>
                </div>					
            </div>
        </div>
        <!--Question section end here!-->

        <!--Question section start here!-->
        <div class="row">
            <accordion close-others="oneAtATime">
                <div class="col-xs-12" ng-repeat="(parentIndex, element) in questionJSON.questions">
                    <accordion-group is-open="isFirstOpen" class="accordion-margin">
                        <accordion-heading>
                            <i class="pull-left glyphicon" ng-class="{'glyphicon-minus': isFirstOpen, 'glyphicon-plus': !isFirstOpen}"></i>&nbsp;[%'Question '+( parentIndex + 1 )%] 
                        </accordion-heading>
                        <div class="row form-header-sub" >
                            <div class="col-xs-12">
                                <div class="col-xs-5">
                                    <div style="display: inline-block;">[%MASTERS.keywords['marks']%]</div>
                                    <div style="display: inline-block;">
                                        <input  ng-disabled="addQuesBtnState" tooltip="[%MASTERS.keywords['markstooltip']%]" type="number" min="1" max="99" ng-model="questionJSON.questions[parentIndex].mark" name="[%'mark' + parentIndex%]" id="[%'mark' + parentIndex%]" size="8" class="text-center form-control marks-input-each">
                                        <span class="glyphicon glyphicon-info-sign btn-cursor-pointer" style="position:relative; top:-6px; right:8px; border-radius:5px; color:#000;"></span>
                                    </div>
                                </div>
                                <div class="col-xs-7 padding-top-5">
                                    <button type="button" class="btn btn-default btn-sm pull-right add-btn btn-cursor-pointer btn-custom-size" ng-disabled="addQuesBtnState" tooltip="[%MASTERS.keywords['addtooltip']%]" ng-click="questionAdd();">[%MASTERS.keywords['add']%]</button>				
                                    <button type="button" class="btn btn-default btn-sm pull-right delete-btn btn-cursor-pointer btn-custom-size" ng-show="questionJSON.questions.length > 1" tooltip="[%MASTERS.keywords['deletetooltip']%]" ng-click="removeQuestion(parentIndex);">[%MASTERS.keywords['delete']%]</button>				
                                    <button type="button" class="btn btn-default btn-sm pull-right edit-btn btn-cursor-pointer btn-custom-size" tooltip="[%MASTERS.keywords['edittooltip']%]" ng-click="editQuestion();">[%MASTERS.keywords['edit']%]</button>				
                                    <button type="button" class="btn btn-default btn-sm pull-right view-btn btn-cursor-pointer btn-custom-size" tooltip="[%MASTERS.keywords['previewtooltip']%]" ng-click="previewQuestion(parentIndex, 'lg');">[%MASTERS.keywords['preview']%]</button>				
                                    <button type="button" class="btn btn-default btn-sm pull-right setanswer-btn btn-cursor-pointer btn-custom-size" tooltip="[%MASTERS.keywords['setanswertooltip']%]" ng-click="setAnswer(parentIndex);">[%MASTERS.keywords['setanswer']%]</button>				
                                </div>
                            </div>
                        </div>
                        <div class="row" ng-class="{'margin-bottom-10': showAnswerEle && showAnswerEle == (parentIndex + 1)}">
                            <div class="row base">
                                <div class="col-xs-12">
                                    <div class="col-xs-12 questionareawidth">
                                        <textarea ng-model="questionJSON.questions[parentIndex].ques" name="questionText[%parentIndex+1%]" placeholder="Write your question here"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row base margin-bottom-20">                                
                                <div class="col-xs-12 margin-bottom-20">
                                    <div class="col-xs-12">
                                        <div class="fileinput" ng-class="questionJSON.id ? 'fileinput-exists' : 'fileinput-new'" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 300px; height: 200px;" ng-bind-html="imageBind(parentIndex)"></div>
                                            <div>
                                                <span class="btn default btn-file">
                                                    <span class="fileinput-new" ng-disabled="addQuesBtnState">
                                                        Select image </span>
                                                    <span class="fileinput-exists" ng-disabled="addQuesBtnState">
                                                        Change </span>
                                                    <input type="file" class="question_image" onchange="angular.element(this).scope().bindClick(this)" id ="[%parentIndex%]" name="...">
                                                </span>
                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput" ng-disabled="addQuesBtnState">Remove</a>
                                            </div>
                                        </div>   
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" ng-show="showAnswerEle && showAnswerEle === (parentIndex + 1)">
                            <hr>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="col-xs-10 set-asnser-heading">
                                        <h4>[%MASTERS.keywords['setyouranswerhere']%]</h4>
                                    </div>				
                                </div>
                            </div>
                            <div class="row base">
                                <div class="col-xs-12">
                                    <div class="col-xs-6">
                                        <div id="setanswer[%parentIndex%]" style="background-image: url('/questionimg/[%questionJSON.questions[parentIndex].option.value%]'); background-repeat: no-repeat;width:600px; height:500px;margin-top:20px;"></div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="col-xs-12 angular">
                                            <span class="chart" easypiechart percent="percent" options="options"></span>
                                            <div class="list piechart-bullet-bold" ng-click="update( 100, parentIndex )" style="top:-20px; left:118px;"></div>
                                            <!--<div class="list piechart-bullet" ng-click="update( 0, parentIndex )" style="top:-20px; left:118px;"></div>-->
                                            <div class="list piechart-bullet" ng-click="update( 5, parentIndex )" style="top:-14px; left:160px;"></div>
                                            <div class="list piechart-bullet" ng-click="update( 10, parentIndex )" style="top:2px; left:190px;"></div>
                                            <div class="list piechart-bullet" ng-click="update( 15, parentIndex )" style="top:28px; left:215px;"></div>
                                            <div class="list piechart-bullet" ng-click="update( 20, parentIndex )" style="top:60px; left:232px;"></div>
                                            <div class="list piechart-bullet-bold" ng-click="update( 25, parentIndex )" style="top:96px; left:240px;"></div>
                                            <div class="list piechart-bullet" ng-click="update( 30, parentIndex )" style="top:132px; left:236px;"></div>
                                            <div class="list piechart-bullet" ng-click="update( 35, parentIndex )" style="top:165px; left:222px;"></div>
                                            <div class="list piechart-bullet" ng-click="update( 40, parentIndex )" style="top:195px; left:195px;"></div>
                                            <div class="list piechart-bullet" ng-click="update( 45, parentIndex )" style="top:215px; left:162px;"></div>
                                            <div class="list piechart-bullet-bold" ng-click="update( 50, parentIndex )" style="top:225px; left:118px;"></div>
                                            <div class="list piechart-bullet" ng-click="update( 55, parentIndex )" style="top:220px; left:80px;"></div>
                                            <div class="list piechart-bullet" ng-click="update( 60, parentIndex )" style="top:202px; left:49px;"></div>
                                            <div class="list piechart-bullet" ng-click="update( 65, parentIndex )" style="top:176px; left:20px;"></div>
                                            <div class="list piechart-bullet" ng-click="update( 70, parentIndex )" style="top:142px; left:2px;"></div>
                                            <div class="list piechart-bullet-bold" ng-click="update( 75, parentIndex )" style="top:100px; left:-4px;"></div>
                                            <div class="list piechart-bullet" ng-click="update( 80, parentIndex )" style="top:66px; left:1px;"></div>
                                            <div class="list piechart-bullet" ng-click="update( 85, parentIndex )" style="top:35px; left:15px;"></div>
                                            <div class="list piechart-bullet" ng-click="update( 90, parentIndex )" style="top:8px; left:42px;"></div>
                                            <div class="list piechart-bullet" ng-click="update( 95, parentIndex )" style="top:-10px; left:76px;"></div>
                                        </div>
                                        <div class="col-xs-12">
                                            <input type="text" style="margin-left:60px;" id="[%'chart'+ parentIndex%]" ng-model="percent" ng-change="update( percent, parentIndex )" class="btn-input-style text-center" />
                                        </div>
                                    </div>	
                                </div>
                            </div>
                            <hr>
                            <div class="row base">
                                <div class="col-xs-12">
                                    <div class="col-xs-11">
                                        <div style="display:inline-block; width:120px;"><b>[%MASTERS.keywords['correctanswers']%]</b></div>
                                        <div style="display:inline-block; width:120px;"><b>[%MASTERS.keywords['marks']%]</b></div>
                                    </div>
                                </div>
                                <div class="col-xs-12 margin-bottom-10" ng-repeat="correctmarkEle in element.correctmark">
                                    <div class="col-xs-11">
                                        <span><input type="number" style="width: 120px; font-weight:normal;" max="[%questionJSON.questions[parentIndex].correctAns.length%]" min='1' ng-model="correctmarkEle.val"/></span>
                                        <span><input type="number" style="width: 120px; font-weight:normal;" max="[%questionJSON.questions[parentIndex].mark%]" min='1' ng-model="correctmarkEle.marks"/></span>
                                        <span class="glyphicon glyphicon-remove btn-cursor-pointer remove-option" ng-show="element.correctmark.length > 1" tooltip="Remove" ng-click="removeCorrectmark(parentIndex, $index)"></span>
                                        <span class="glyphicon glyphicon-plus btn-cursor-pointer add-option" ng-show="element.correctmark.length == $index + 1" tooltip="Add" ng-click="addCorrectmark(parentIndex)"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row base" style="margin-bottom: 20px;">
                                <div class="col-xs-12">
                                    <div class="col-xs-12">
                                        <button type="button" class="btn btn-default btn-sm pull-right ok-btn-color btn-cursor-pointer" ng-click="submitpiechartCQ( MASTERS.keywords['submitaction1'], MASTERS.keywords['submitaction4'] )">[%MASTERS.keywords['save']%]</button>
                                        <button ng-hide="questionData.id" type="button" class="btn btn-default btn-sm pull-right margin-right-15 ok-btn-color btn-cursor-pointer" ng-click="submitpiechartCQ(MASTERS.keywords['submitaction2'], MASTERS.keywords['submitaction4'])">[%MASTERS.keywords['save&next']%]</button>      
                                        <button type="button" class="btn btn-default btn-sm pull-right margin-right-15 view-btn btn-cursor-pointer" ng-click="previewQuestion(parentIndex, 'lg');">[%MASTERS.keywords['previewtooltip']%]</button>				
                                        <button type="button" class="btn btn-default btn-sm active pull-right margin-right-15 cancel-btn-color btn-cursor-pointer" ng-click="refreshQuestionSection(MASTERS.keywords['submitaction1'], MASTERS.keywords['submitaction3'])">[%MASTERS.keywords['cancel']%]</button>                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </accordion-group>
                </div>
            </accordion>
        </div>
        <!--Question section end here!-->
    </form>
</div>

<!-- Single Choice question preview section.: Code Start -->
<script type="text/ng-template" id="piechartPre">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" ng-click="cancel()" >&times;</button>
        <h3 class="modal-title">[%MASTERS.keywords['previewquestion']%]</h3>
    </div>
    <div class="modal-body">
    <div class="row">
        <div class="col-xs-12 margin-bottom-15" ng-class="quesArray.description ? 'desc-background' : ''" ng-bind-html="quesArray.description | to_trusted" ></div>
        <div class="col-xs-12 ques-font-weight" ng-bind-html="quesArray.questions[quesIndex].ques | to_trusted" ></div>
    </div>
    <div class="row margin-bottom-20">
        <div class="col-xs-12">
            <div class="col-xs-6">
                <div id="preanswer[%quesIndex%]" style="background-image: url('/questionimg/[%quesArray.questions[quesIndex].option.value%]'); background-size: 100% 100%;background-repeat: no-repeat;width:600px; height:500px;margin-top:20px;"></div>
            </div>
            <div class="col-xs-6">
                <div class="col-xs-12 angular">
                    <span class="chart" easypiechart percent="percentpre" options="options"></span>
                    <div class="list piechart-bullet-bold" ng-click="updatepre( 100, quesIndex )" style="top:-20px; left:118px;"></div>
                    <!--<div class="list piechart-bullet" ng-click="updatepre( 0, quesIndex )" style="top:-20px; left:118px;"></div>-->
                    <div class="list piechart-bullet" ng-click="updatepre( 5, quesIndex )" style="top:-14px; left:160px;"></div>
                    <div class="list piechart-bullet" ng-click="updatepre( 10, quesIndex )" style="top:2px; left:190px;"></div>
                    <div class="list piechart-bullet" ng-click="updatepre( 15, quesIndex )" style="top:28px; left:215px;"></div>
                    <div class="list piechart-bullet" ng-click="updatepre( 20, quesIndex )" style="top:60px; left:232px;"></div>
                    <div class="list piechart-bullet-bold" ng-click="updatepre( 25, quesIndex )" style="top:96px; left:240px;"></div>
                    <div class="list piechart-bullet" ng-click="updatepre( 30, quesIndex )" style="top:132px; left:236px;"></div>
                    <div class="list piechart-bullet" ng-click="updatepre( 35, quesIndex )" style="top:165px; left:222px;"></div>
                    <div class="list piechart-bullet" ng-click="updatepre( 40, quesIndex )" style="top:195px; left:195px;"></div>
                    <div class="list piechart-bullet" ng-click="updatepre( 45, quesIndex )" style="top:215px; left:162px;"></div>
                    <div class="list piechart-bullet-bold" ng-click="updatepre( 50, quesIndex )" style="top:225px; left:118px;"></div>
                    <div class="list piechart-bullet" ng-click="updatepre( 55, quesIndex )" style="top:220px; left:80px;"></div>
                    <div class="list piechart-bullet" ng-click="updatepre( 60, quesIndex )" style="top:202px; left:49px;"></div>
                    <div class="list piechart-bullet" ng-click="updatepre( 65, quesIndex )" style="top:176px; left:20px;"></div>
                    <div class="list piechart-bullet" ng-click="updatepre( 70, quesIndex )" style="top:142px; left:2px;"></div>
                    <div class="list piechart-bullet-bold" ng-click="updatepre( 75, quesIndex )" style="top:100px; left:-4px;"></div>
                    <div class="list piechart-bullet" ng-click="updatepre( 80, quesIndex )" style="top:66px; left:1px;"></div>
                    <div class="list piechart-bullet" ng-click="updatepre( 85, quesIndex )" style="top:35px; left:15px;"></div>
                    <div class="list piechart-bullet" ng-click="updatepre( 90, quesIndex )" style="top:8px; left:42px;"></div>
                    <div class="list piechart-bullet" ng-click="updatepre( 95, quesIndex )" style="top:-10px; left:76px;"></div>
                </div>
                <!--<div class="col-xs-12">
                    <input type="text" style="margin-left:60px;" id="[%'chartpre'+ quesIndex%]" ng-model="percentpre" ng-change="updatepre( percentpre, quesIndex )" class="btn-input-style text-center" />
                </div>-->
            </div>	
        </div>
    </div>
    <div class="modal-footer">
        <div class="form-body">
            <div class="form-group" style="width:auto; float:right; margin-bottom:0px;">
                <button ng-disabled="!selectedreason" ng-show="isvalidatevisisble" class="btn btn-success" type="button" ng-click="validateQuestion()">Validate</button>
                <button class="btn btn-warning" type="button" ng-click="cancel()">[%MASTERS.keywords['close']%]</button>
            </div>
            <div ng-show="isvalidatevisisble" class="form-group" style="width:auto; float:right; margin-bottom:0px;">
                <!--<select class="form-control pull-right" style="width: 220px; margin-right:30px;" name="reason_id" id="reason_id" ng-model="selectedreason" ng-options="v.id as v.name for (k,v) in MASTERS.validateReason">
                    <option value=''>Select Reason</option>
                </select>-->
                <dropdown-multiselect model="selectedreason" options="MASTERS.validateReason"></dropdown-multiselect>
            </div>
        </div>
    </div>
</script>
<!-- Single Choice question preview section.: Code End -->

