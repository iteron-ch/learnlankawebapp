<div class="questionrender" ng-controller="numberwordselectRenderCtrl">
    <div ng-repeat="(parentIndex, quesele) in renderQuestion[currentQues-1].questions">
        <div class="col-md-12 ques_area">
            <div class="col-md-12 qDetail">
                <label class="pull-left"><span>[%parentIndex+1 | character%]</span></label>
                <div class="col-md-11 margin-top-5" ng-bind-html="quesele.ques | to_trusted"></div>
                <div class="marks_box">
                    <div class="mark_div">[%quesele.mark%]</div>
                    <div ng-show="quesele.mark <= 1">mark</div>
                    <div ng-show="quesele.mark > 1">marks</div>
                </div>
            </div>
            <div class="col-md-12 ques_option">
                <div class="col-xs-12" id="questionID[%parentIndex%]">
                    <ol ng-if="quesele.option.view === 'vertical'" class="margin-padding-none align_left" style="list-style-type:none">
                        <li style="padding-bottom:20px;" ng-repeat="ele in quesele.option.optionvalue">
                            <span class="btn-cursor-pointer" id="[%parentIndex%]_[%$index%]" name="addDrawClass" ng-bind-html="ele.value | to_trusted"></span>
                        </li>
                    </ol>
                    <ol ng-if="quesele.option.view === 'horizontal'" class="margin-padding-none align_left" style="list-style-type:none">
                        <li style="float:left; padding-right:25px;" ng-repeat="ele in quesele.option.optionvalue">
                            <span class="btn-cursor-pointer" id="[%parentIndex%]_[%$index%]" name="addDrawClass" ng-bind-html="ele.value | to_trusted"></span>
                        </li>
                    </ol>
                </div>
            </div>
            <div class="col-xs-12 margin-top-10">
                <div class="col-xs-8">
                    <a class="reset-cursor" ng-click="clearResponse(parentIndex)" href="javascript:void(0);">Clear Answer</a>
                    <!--<a ng-click="getMappingVal(parentIndex)" href="javascript:void(0);">Show mapping</a>-->
                </div>
            </div>
            
            <div ng-if="renderQuestion[currentQues - 1].correctAns" class="col-md-12 ques_option">
                <h2 class="anshead">Correct Answer</h2>
                <div class="col-xs-12" id="correctAnsquestionID[%parentIndex%]">
                    <ol ng-if="quesele.option.view === 'vertical'" class="margin-padding-none align_left" style="list-style-type:none">
                        <li style="padding-bottom:20px;" ng-repeat="ele in quesele.option.optionvalue">
                            <span class="btn-cursor-pointer" id="correctAns[%parentIndex%]_[%$index%]" name="addDrawClass" ng-bind-html="ele.value | to_trusted"></span>
                        </li>
                    </ol>
                    <ol ng-if="quesele.option.view === 'horizontal'" class="margin-padding-none align_left" style="list-style-type:none">
                        <li style="float:left; padding-right:25px;" ng-repeat="ele in quesele.option.optionvalue">
                            <span class="btn-cursor-pointer" id="correctAns[%parentIndex%]_[%$index%]" name="addDrawClass" ng-bind-html="ele.value | to_trusted"></span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>