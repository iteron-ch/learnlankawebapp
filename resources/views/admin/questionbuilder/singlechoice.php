<div class="main_panel" ng-controller="singleChoiceCtrl">
    <form name="singlechoiceForm" id="singlechoiceForm" class="margin-bottom-10">
        <div class="row">
            <div class="col-xs-12">
                <div class="row form-header" >
                    <div class="col-xs-12">
                        <div class="col-xs-6 checkbox">
                            <label><input type="checkbox" ng-model="oneAtATime">[%MASTERS.keywords['singlequestionexpand']%]</label>
                        </div>
                        <div class="col-xs-6 pull-right padding-top-07">
                            <button type="button" class="btn btn-default btn-sm pull-right view-btn btn-cursor-pointer" tooltip="Preview" ng-click="previewSet('lg');">PREVIEW</button>
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
                                    <!--<div class="col-xs-1">
                                        <label style="float: right;padding-top: 5px;">Ques [%parentIndex+1%].</label>
                                    </div>-->
                                    <div class="col-xs-12 questionareawidth">
                                        <textarea ng-hide="true" ng-model="questionJSON.questions[parentIndex].ques" name="questionText[%parentIndex+1%]" placeholder="Write your question here"></textarea>
                                    </div>	
                                </div>
                            </div>
                            <div class="row base margin-bottom-20" ng-repeat="childEle in element.option">
                                <div class="col-xs-12">
                                    <div class="col-xs-10" style="display: flex;">
                                        <!--<div class="padding-top-10" style="display:inline-block; padding-right: 10px;"><input ng-disabled=true type="radio"></div>-->
                                        <div style="display:inline-block; width: 98%;" >
                                            <textarea ng-hide="true" id="option[%parentIndex+1%][%$index+1%]" ng-model="questionJSON.questions[parentIndex].option[$index].option" style="display:none;" name="option[%parentIndex+1%][%$index+1%]" placeholder="Option write here"></textarea>
                                        </div>
                                    </div>	
                                    <div class="col-xs-1">
                                        <span class="glyphicon glyphicon-remove btn-cursor-pointer padding-top-14 remove-option" ng-show="element.option.length > 2 && !addQuesBtnState" tooltip="Remove" ng-click="removeOption(parentIndex, $index)"></span>
                                        <span class="glyphicon glyphicon glyphicon-plus btn-cursor-pointer padding-top-14 add-option" ng-show="element.option.length == $index + 1 && !addQuesBtnState" tooltip="Add" ng-click="addOption(parentIndex)"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" ng-show="showAnswerEle && showAnswerEle == (parentIndex + 1)">
                            <hr>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="col-xs-10 set-asnser-heading">
                                        <h4>[%MASTERS.keywords['setyouranswerhere']%]</h4>
                                    </div>				
                                </div>
                            </div>
                            <div class="row base" ng-repeat="childEleA in element.option">
                                <div class="col-xs-12">
                                    <div class="col-xs-10">
                                        <div style="display: flex;">
                                            <div style="padding-right: 10px; display: inline-block;">
                                                <input type="radio" name="singlechoiceradio[%parentIndex%]" id="singlechoiceradio[%parentIndex%][%$index%]" value=[%$index%]  ng-model='questionJSON.questions[parentIndex].correctAns' ng-change="changeSelectedOption(parentIndex, $index)" class="custom_single ng-hide" / >
                                                <label for="singlechoiceradio[%parentIndex%][%$index%]"><span>&nbsp;</span></label>
                                                <!--<input type="radio" name="singlechoiceradio[%parentIndex%]" ng-model='questionJSON.questions[parentIndex].correctAns' value="[%$index%]" ng-change="changeSelectedOption(parentIndex, $index)">-->
                                            </div>
                                            <div style="display: inline-block;" ng-bind-html="childEleA.option | to_trusted" ></div>
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
                                        <span><input type="number" style="width: 120px; font-weight:normal;" max="1" min='1' ng-model="correctmarkEle.val"/></span>
                                        <span><input type="number" style="width: 120px; font-weight:normal;" max="[%questionJSON.questions[parentIndex].mark%]" min='1' ng-model="correctmarkEle.marks"/></span>
                                        <!--<span class="glyphicon glyphicon-remove btn-cursor-pointer remove-option" ng-show="element.correctmark.length > 1" tooltip="Remove" ng-click="removeCorrectmark(parentIndex, $index)"></span>
                                        <span class="glyphicon glyphicon-plus btn-cursor-pointer add-option" ng-show="element.correctmark.length == $index + 1" tooltip="Add" ng-click="addCorrectmark(parentIndex)"></span>-->
                                    </div>
                                </div>
                            </div>
                            <div class="row base" style="margin-bottom: 20px;">
                                <div class="col-xs-12">
                                    <div class="col-xs-12">
                                        <button type="button" class="btn btn-default btn-sm pull-right ok-btn-color btn-cursor-pointer" ng-click="submitSingleCQ( MASTERS.keywords['submitaction1'], MASTERS.keywords['submitaction4'] )">[%MASTERS.keywords['save']%]</button>
                                        <button ng-hide="questionData.id" type="button" class="btn btn-default btn-sm pull-right margin-right-15 ok-btn-color btn-cursor-pointer" ng-click="submitSingleCQ(MASTERS.keywords['submitaction2'], MASTERS.keywords['submitaction4'])">[%MASTERS.keywords['save&next']%]</button>      
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
<script type="text/ng-template" id="singleQuestionPre">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" ng-click="cancel()" >&times;</button>
        <h3 class="modal-title">[%MASTERS.keywords['previewquestion']%]</h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-xs-12 margin-bottom-15" ng-class="quesArray.description ? 'desc-background' : ''" ng-bind-html="quesArray.description | to_trusted" ></div>
            <div class="col-xs-12 margin-bottom-15 ques-font-weight" ng-bind-html="quesArray.questions[quesIndex].ques | to_trusted" ></div>
        </div>
        <div class="row margin-bottom-20">
            <div class="col-xs-12" ng-repeat="ele in quesArray.questions[quesIndex].option">
                <div style="display: flex;">
                    <div style="padding-right: 10px; display: inline-block;">
                        <!--<input type="radio" style="display: inline-block;" name="singlechoiceradio">-->
                        <input type="radio" name="singlechoiceradio[%parentIndex%]" id="singlechoiceradio[%parentIndex%][%$index%]" class="custom_single ng-hide" / >
                        <label for="singlechoiceradio[%parentIndex%][%$index%]"><span>&nbsp;</span></label>
                    </div>
                    <div style="display: inline-block;" ng-bind-html="ele.option | to_trusted"></div>
                </div>
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

<!-- Single Choice question preview section.: Code Start -->
<script type="text/ng-template" id="singleSetQuestionPre">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" ng-click="cancel()" >&times;</button>
        <h3 class="modal-title">[%MASTERS.keywords['previewquestion']%]</h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-xs-12 margin-bottom-15" ng-class="quesArray.description ? 'desc-background' : ''" ng-bind-html="quesArray.description | to_trusted" ></div>
        </div>
        <div class="row margin-bottom-20" ng-repeat="quesele in quesArray.questions track by $index">
            <div class="col-xs-12 margin-bottom-15 ques-font-weight" ng-bind-html="quesele.ques | to_trusted" ></div>
            <div class="col-xs-12" ng-repeat="ele in quesele.option">
                <div style="display: flex;">
                    <div style="padding-right: 10px; display: inline-block;">
                        <input type="radio" style="display: inline-block;" name="singlechoiceradio">
                    </div>
                    <div style="display: inline-block;" ng-bind-html="ele.option | to_trusted"></div>
                </div>
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

