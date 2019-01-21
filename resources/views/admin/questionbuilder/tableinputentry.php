<div class="main_panel" ng-controller="tableinputentryCtrl">
    <form name="tableinputentryForm" id="tableinputentryForm" class="margin-bottom-10">
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
                                    <div class="col-xs-10">
                                        <button type="button" ng-disabled="addQuesBtnState" class="btn btn-success" ng-click="addColumn(parentIndex)" >[%MASTERS.keywords['addcolumn']%]</button>
                                        <button type="button" ng-disabled="addQuesBtnState" class="btn btn-success" ng-click="addRow(parentIndex)" >[%MASTERS.keywords['addrow']%]</button>
                                        <span style="margin-left: 20px;">
                                            <label class="radio-inline">
                                                <input type="checkbox" id="checkboxheader[%pIndex%][%$index%]" ng-disabled="addQuesBtnState" name="checkboxheader[%pIndex%][%$index%]" ng-model="questionJSON.questions[parentIndex].headerrow" value="{{questionJSON.questions[parentIndex].header}}"/>&nbsp;Show Row Header
                                            </label>
                                            <label class="radio-inline">
                                                <input type="checkbox" id="checkboxheader[%pIndex%][%$index%]" ng-disabled="addQuesBtnState" name="checkboxheader[%pIndex%][%$index%]" ng-model="questionJSON.questions[parentIndex].headercol" value="{{questionJSON.questions[parentIndex].header}}"/>&nbsp;Show Column Header
                                            </label>
                                        </span>
                                    </div>	
                                </div>
                                
                                <div class="col-xs-12 singlemultiple-editor">
                                    <div class="col-xs-11" style="overflow-x:scroll;">						 
                                        <table id="questionId[%parentIndex%]">											
                                            <tr ng-repeat="(pIndex, subchildele) in element.option track by pIndex">
                                                <td ng-hide="(field.title && !questionJSON.questions[parentIndex].headercol && pIndex == 0) ||  (field.title && !questionJSON.questions[parentIndex].headerrow && $index == 0)" ng-repeat="field in subchildele track by $index">
                                                    <span ng-if="!field.title" class="visibility-editor">
                                                        <input type="checkbox" ng-hide="true" class="custom_multiple" ng-model="questionJSON.questions[parentIndex].option[pIndex][$index].checkvalue" value="{{questionJSON.questions[parentIndex].option[pIndex][$index].checkvalue}}" id="checkbox[%parentIndex%][%pIndex%][%$index%]Q" 
                                                               name="checkbox[%parentIndex%][%pIndex%][%$index%]Q"/>
                                                        <label style="display: flex;" for="checkbox[%parentIndex%][%pIndex%][%$index%]Q"><span style="margin-top:5px; margin-right:5px;"></span>
                                                            <input id="option_[%parentIndex%]_[%pIndex%]_[%$index%]" ng-model="questionJSON.questions[parentIndex].option[pIndex][$index].value" ng-disabled="!questionJSON.questions[parentIndex].option[pIndex][$index].checkvalue || addQuesBtnState" type="text" style="width: 86%; font-weight:normal;"/>
                                                        </label>		
                                                    </span>
                                                    <span ng-if="field.title">
                                                        <textarea ng-hide="true" ng-disabled="addQuesBtnState" ng-model="questionJSON.questions[parentIndex].option[pIndex][$index].value" name="optionText[%parentIndex%][%pIndex%][%$index%]" placeholder="Write your option here"></textarea>
                                                        <!--<input type="text" ng-disabled="addQuesBtnState" style="width: 100%; font-weight:normal;" ng-model="field.value"/>-->
                                                    </span>
                                                </td>
                                                <td>
                                                    <a ng-click="removeRow(parentIndex, pIndex)" ng-hide="element.option.length < 3 || pIndex === 0 || addQuesBtnState">
                                                        <span class="glyphicon glyphicon-remove btn-cursor-pointer padding-top-14 remove-option" tooltip="Remove" style="padding-top: 2px;padding-right: 10px;"></span>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="remove-tr-border" ng-repeat="field in element.option[0]">
                                                    <a class="pull-left" ng-click="removeColumn(parentIndex, $index)" ng-hide="element.option[0].length < 3 || addQuesBtnState" ng-if="$index != 0">
                                                        <span class="glyphicon glyphicon-remove btn-cursor-pointer padding-top-14 remove-option" tooltip="Remove" style="padding-top: 2px;padding-right: 10px;"></span>
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
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
                            <div class="row base">
                                <div class="col-xs-12">
                                    <div class="col-xs-10">						 
                                        <table>											
                                            <tr ng-repeat="(pIndex, subchildele) in questionJSON.questions[parentIndex].option track by pIndex">
                                                <td ng-hide="(field.title && !questionJSON.questions[parentIndex].headercol && pIndex == 0) ||  (field.title && !questionJSON.questions[parentIndex].headerrow && $index == 0)" ng-repeat="field in subchildele track by $index">
                                                    <span ng-if="!field.title">
                                                        <input type="checkbox" ng-hide="true" class="custom_[%questionJSON.questions[parentIndex].questype%]" name="checkbox[%parentIndex%][%pIndex%][%$index%]" ng-model="field.checkvalue"/>
                                                        <label for="checkbox[%parentIndex%][%pIndex%][%$index%]"><span></span>
                                                            <input ng-model="questionJSON.questions[parentIndex].correctAns[pIndex][$index].value" ng-disabled="questionJSON.questions[parentIndex].correctAns[pIndex][$index].checkvalue" type="text" style="width: auto; font-weight:normal;"/>
                                                        </label>		
                                                    </span>
                                                    <span ng-if="field.title">
                                                        <div style="font-weight:normal; padding-left:2px;" ng-bind-html="field.value | to_trusted"></div>
                                                    </span>
                                                </td>
                                                <td ng-show="false">
                                                    <a ng-click="removeRow(parentIndex, pIndex)" ng-hide="element.option.length < 4 || pIndex === 0">
                                                        <span class="glyphicon glyphicon-remove btn-cursor-pointer btn-font-18 pull-right" tooltip="Remove" style="padding-top: 2px;padding-right: 10px;"></span>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr ng-show="false">
                                                <td class="remove-tr-border" ng-repeat="field in questionJSON.questions[parentIndex].option[0]">
                                                    <a ng-click="removeColumn(parentIndex, $index)" ng-hide="element.option[0].length < 4" ng-if="$index != 0">
                                                        <span class="glyphicon glyphicon-remove btn-cursor-pointer btn-font-18 pull-right" tooltip="Remove" style="padding-top: 2px;padding-right: 10px;"></span>
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
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
                                        <span><input type="number" style="width: 120px; font-weight:normal;" max="[%(questionJSON.questions[parentIndex].option.length - 1) * (questionJSON.questions[parentIndex].option[0].length - 1) %]" min='1' ng-model="correctmarkEle.val"/></span>
                                        <span><input type="number" style="width: 120px; font-weight:normal;" max="[%questionJSON.questions[parentIndex].mark%]" min='1' ng-model="correctmarkEle.marks"/></span>
                                        <span class="glyphicon glyphicon-remove btn-cursor-pointer remove-option" ng-show="element.correctmark.length > 1" tooltip="Remove" ng-click="removeCorrectmark(parentIndex, $index)"></span>
                                        <span class="glyphicon glyphicon-plus btn-cursor-pointer add-option" ng-show="element.correctmark.length == $index + 1" tooltip="Add" ng-click="addCorrectmark(parentIndex)"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row base" style="margin-bottom: 20px;">
                                <div class="col-xs-12">
                                    <div class="col-xs-12">
                                        <button type="button" class="btn btn-default btn-sm pull-right ok-btn-color btn-cursor-pointer" ng-click="submittableinputentryCQ( MASTERS.keywords['submitaction1'], MASTERS.keywords['submitaction4'] )">[%MASTERS.keywords['save']%]</button>
                                        <button ng-hide="questionData.id" type="button" class="btn btn-default btn-sm pull-right margin-right-15 ok-btn-color btn-cursor-pointer" ng-click="submittableinputentryCQ(MASTERS.keywords['submitaction2'], MASTERS.keywords['submitaction4'])">[%MASTERS.keywords['save&next']%]</button>      
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
<!-- Question preview section.: Code Start -->
<script type="text/ng-template" id="tableinputentryPre">
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
                <table>											
                    <tr ng-repeat="(pIndex, subchildele) in quesArray.questions[quesIndex].option track by pIndex">
                        <td ng-hide="(field.title && !quesArray.questions[quesIndex].headercol && pIndex == 0) ||  (field.title && !quesArray.questions[quesIndex].headerrow && $index == 0)" ng-repeat="field in subchildele track by $index">
                            <span ng-if="!field.title">
                                <input type="checkbox" ng-hide="true" class="custom_[%quesArray.questions[quesIndex].questype%]" name="checkbox[%quesIndex%][%pIndex%][%$index%]" ng-model="field.checkvalue"/>
                                <label for="checkbox[%quesIndex%][%pIndex%][%$index%]"><span></span>
                                    <input ng-model="quesArray.questions[quesIndex].option[pIndex][$index].value" ng-disabled="quesArray.questions[quesIndex].option[pIndex][$index].checkvalue" type="text" style="width: auto; font-weight:normal;"/>
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
                        <td class="remove-tr-border" ng-repeat="field in quesArray.questions[quesIndex].option[0]">
                            <a ng-click="removeColumn(quesIndex, $index)" ng-hide="element.option[0].length < 4" ng-if="$index != 0">
                                <span class="glyphicon glyphicon-remove btn-cursor-pointer btn-font-18 pull-right" tooltip="Remove" style="padding-top: 2px;padding-right: 10px;"></span>
                            </a>
                        </td>
                    </tr>
                </table>
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
                <dropdown-multiselect model="selectedreason" options="MASTERS.validateReason"></dropdown-multiselect>
            </div>
        </div>
    </div>
</script>
<!-- Question preview section.: Code End -->

