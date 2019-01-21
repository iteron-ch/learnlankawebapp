<div class="questionrender" ng-controller="booleanRenderCtrl">
    <div ng-repeat="(parentIndex, quesele) in renderQuestion[currentQues - 1].questions  track by $index">
        <div class="col-md-12 ques_area">
            <div class="col-md-12 qDetail">
                <label class="pull-left"><span>[%parentIndex+1 | character%]</span></label>
                <div id="[%parentIndex%]" class="col-md-11 margin-top-5" ng-bind-html="quesele.ques | to_trusted"></div>
                <div class="marks_box">
                    <div class="mark_div">[%quesele.mark%]</div>
                    <div ng-show="quesele.mark <= 1">mark</div>
                    <div ng-show="quesele.mark > 1">marks</div>
                </div>
            </div>
            <div class="ques_option">
                <div class="col-xs-12" ng-repeat="ele in quesele.option">
                    <div style="display: flex;">
                        <div style="padding-right: 10px; display: inline-block;">
                            <input type="radio" name="booleanradio[%parentIndex%]" id="booleanradio[%parentIndex%][%$index%]" ng-model='renderQuestion[currentQues - 1].userresponse[parentIndex][0].main' value="[%$index%]" class="custom_single ng-hide" />
                                   <label for="booleanradio[%parentIndex%][%$index%]"><span>&nbsp;</span></label>
                        </div>
                        <div style="display: inline-block;" ng-bind-html="ele.option | to_trusted"></div>
                    </div>
                </div>

                <div class="col-xs-12 ques_option_heading" ng-show="quesele.showsmultiple">
                    <div style="display: inline-block;" ng-bind-html="quesele.elsequestext | to_trusted" ></div>
                </div>
                <div class="ques_option" style="padding-left:35px;">
                    <div class="col-xs-12 margin-top-10" style="display: flex;" ng-show="quesele.showsmultiple" ng-repeat="childEle in renderQuestion[currentQues - 1].userresponse[parentIndex][0].justifypart">
                        <div style="padding-right: 10px; display: inline-block;">
                            <input type="checkbox" ng-model="renderQuestion[currentQues - 1].userresponse[parentIndex][0].justifypart[$index].ischeck" name="booleancheckbox[%parentIndex%]" id="booleancheckbox[%parentIndex%][%$index%]" class="custom_multiple ng-hide" />
                            <label for="booleancheckbox[%parentIndex%][%$index%]"><span>&nbsp;</span></label>
                        </div>
                        <div style="display: inline-block;" ng-bind-html="renderQuestion[currentQues - 1].userresponse[parentIndex][0].justifypart[$index].value | to_trusted" ></div>
                    </div>
                </div>
            </div>
            <!-- render correct answer -->
            
            <div ng-if="renderQuestion[currentQues - 1].correctAns" class="ques_option">
                <h2 class="anshead">Correct Answer</h2>
                <div class="col-xs-12" ng-repeat="ele in quesele.option">
                    <div style="display: flex;">
                        <div style="padding-right: 10px; display: inline-block;">
                            <input type="radio" name="correctAnsbooleanradio[%parentIndex%]" id="correctAnsbooleanradio[%parentIndex%][%$index%]" ng-model='renderQuestion[currentQues - 1].correctAns[parentIndex][0].main' value="[%$index%]" class="custom_single ng-hide" />
                                   <label for="correctAnsbooleanradio[%parentIndex%][%$index%]"><span>&nbsp;</span></label>
                        </div>
                        <div style="display: inline-block;" ng-bind-html="ele.option | to_trusted"></div>
                    </div>
                </div>

                <div class="col-xs-12 ques_option_heading" ng-show="quesele.showsmultiple">
                    <div style="display: inline-block;" ng-bind-html="quesele.elsequestext | to_trusted" ></div>
                </div>
                <div class="ques_option" style="padding-left:35px;">
                    <div class="col-xs-12 margin-top-10" style="display: flex;" ng-show="quesele.showsmultiple" ng-repeat="childEle in renderQuestion[currentQues - 1].correctAns[parentIndex][0].justifypart">
                        <div style="padding-right: 10px; display: inline-block;">
                            <input type="checkbox" ng-model="renderQuestion[currentQues - 1].correctAns[parentIndex][0].justifypart[$index].ischeck" name="correctAnsbooleancheckbox[%parentIndex%]" id="correctAnsbooleancheckbox[%parentIndex%][%$index%]" class="custom_multiple ng-hide" />
                            <label for="correctAnsbooleancheckbox[%parentIndex%][%$index%]"><span>&nbsp;</span></label>
                        </div>
                        <div style="display: inline-block;" ng-bind-html="renderQuestion[currentQues - 1].correctAns[parentIndex][0].justifypart[$index].value | to_trusted" ></div>
                    </div>
                </div>
            </div>
            
            <!--end -->
        </div>
    </div>
</div>
