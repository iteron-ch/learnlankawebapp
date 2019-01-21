<div class="main_panel" ng-controller="inputonimageCtrl">
    <form name="inputonimageForm" id="inputonimageForm" class="margin-bottom-10">
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
                                    <button type="button" class="btn btn-default btn-sm pull-right edit-btn btn-cursor-pointer btn-custom-size" tooltip="[%MASTERS.keywords['edittooltip']%]" ng-click="editQuestion( parentIndex );">[%MASTERS.keywords['edit']%]</button>				
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
                                    <button type="button" class="btn btn-default btn-sm margin-left-15 ok-btn-color btn-cursor-pointer" ng-click="inputBoxAdd(parentIndex)">[%MASTERS.keywords['add']%]</button>		
                                    <button type="button" class="btn btn-default btn-sm delete-btn btn-cursor-pointer" ng-click="inputBoxDelete(parentIndex)">[%MASTERS.keywords['delete']%]</button>				
                                </div>
                                <div class="col-xs-12 margin-bottom-20">
                                    <div class="col-xs-10 margin-bottom-20" id="quesDrag[%parentIndex%]">
                                        <div class="fileinput"  id="fileinput[%parentIndex%]" ng-class="questionJSON.id ? 'fileinput-exists' : 'fileinput-new'" data-provides="fileinput" style="width:600px; height:500px;">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width:600px; height:500px;" ng-bind-html="imageBind(parentIndex)"></div>
                                            <span class="draggable" id="option_[%parentIndex%]_[%$index%]" ng-repeat="optionele in questionJSON.questions[parentIndex].option" ng-style="{'float':'left', 'z-index':'1', 'padding-left':'12px', 'position':'absolute', 'top':optionele.position.top, 'left':optionele.position.left}">
                                                <span class="icon" style="float: left; margin: 1px; position:absolute; left:0px; top:px;">*</span>
                                                <div type="text" class="resizable" id="[%optionele.label%]" class="ui-state-active" ng-style="{'float':'left', 'opacity':'0.5', 'border':'1px solid red', 'width':optionele.position.width, 'height':optionele.position.height}">[%optionele.label%]</div>
                                            </span>
                                            
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
                                    <div class="col-xs-12" ng-repeat="(optionindex, eledragoption) in questionJSON.questions[parentIndex].option">
                                        <div class="col-xs-2">[%eledragoption.label%]</div>
                                        <div class="col-xs-10 col-xs-pull-1 margin-bottom-10">
                                            <input type="text" ng-model="questionJSON.questions[parentIndex].correctAns[optionindex][$index].value" class="btn-input-style text-center" ng-repeat="elecorrectAns in questionJSON.questions[parentIndex].correctAns[optionindex]" style="margin-right:10px;"/>
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
                                        <span><input type="number" style="width: 120px; font-weight:normal;" max="[%questionJSON.questions[parentIndex].correctAns.length*3%]" min='1' ng-model="correctmarkEle.val"/></span>
                                        <span><input type="number" style="width: 120px; font-weight:normal;" max="[%questionJSON.questions[parentIndex].mark%]" min='1' ng-model="correctmarkEle.marks"/></span>
                                        <span class="glyphicon glyphicon-remove btn-cursor-pointer remove-option" ng-show="element.correctmark.length > 1" tooltip="Remove" ng-click="removeCorrectmark(parentIndex, $index)"></span>
                                        <span class="glyphicon glyphicon-plus btn-cursor-pointer add-option" ng-show="element.correctmark.length == $index + 1" tooltip="Add" ng-click="addCorrectmark(parentIndex)"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row base" style="margin-bottom: 20px;">
                                <div class="col-xs-12">
                                    <div class="col-xs-12">
                                        <button type="button" class="btn btn-default btn-sm pull-right ok-btn-color btn-cursor-pointer" ng-click="submitinputonimageCQ( MASTERS.keywords['submitaction1'], MASTERS.keywords['submitaction4'] )">[%MASTERS.keywords['save']%]</button>
                                        <button ng-hide="questionData.id" type="button" class="btn btn-default btn-sm pull-right margin-right-15 ok-btn-color btn-cursor-pointer" ng-click="submitinputonimageCQ(MASTERS.keywords['submitaction2'], MASTERS.keywords['submitaction4'])">[%MASTERS.keywords['save&next']%]</button>      
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
<script type="text/ng-template" id="inputonimagePre">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" ng-click="cancel()" >&times;</button>
        <h3 class="modal-title">[%MASTERS.keywords['previewquestion']%]</h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-xs-12 margin-bottom-15" ng-class="quesArray.description ? 'desc-background' : ''" ng-bind-html="quesArray.description | to_trusted" ></div>
            <div class="col-xs-12 ques-font-weight" ng-bind-html="quesArray.questions[quesIndex].ques | to_trusted" ></div>

            <div class="col-xs-12"></div>
            <div class="col-xs-12">
                <div class="col-xs-12 margin-top-15 margin-bottom-20" style="width: 600px; height: 500px;">
                    <div class="fileinput-preview thumbnail" style="border:1px solid #ffffff; width:600px; height:500px; background-image: url([%backgroundimage%]); background-repeat: no-repeat;"></div>
                    <span ng-repeat="optionele in quesArray.questions[quesIndex].option" ng-style="{'position':'absolute',  'top':optionele.position.top, 'left':optionele.position.left}">
                        <input type="text" class="text-center" ng-style="{'background':'transparent', 'border':'0px solid #ffffff', 'border-radius':'5px !important', 'font-size':'25px', 'font-weight':'bold', 'color':'#000000', 'width':optionele.position.width, 'height':optionele.position.height}"></input>
                    </span>
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
                <dropdown-multiselect model="selectedreason" options="MASTERS.validateReason"></dropdown-multiselect>
            </div>
        </div>
    </div>
</script>
<!-- Single Choice question preview section.: Code End -->

