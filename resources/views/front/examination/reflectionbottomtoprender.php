<div class="questionrender" id="reflectionForm" ng-controller="reflectionRenderCtrl">
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
                <div class="col-xs-12" style="padding-left: 38%;">
                    <div id="setpatternAnswer_ans_[%parentIndex%]"></div>
                    <div style="border:2px solid #888888; width:340px; position:absolute; box-shadow:0px 0px 10px #888888;"></div>
                    <div id="setpatternContainer_ques_[%parentIndex%]"></div>
                </div>
                <div class="col-xs-12 margin-top-10" ng-show="renderQuestion[currentQues-1].userresponse[parentIndex].length">
                    <a class="reset-cursor" ng-click="clearDrawing(parentIndex)" href="javascript:void(0);">Clear Answer</a>
                </div>
            </div>
            <!-- render correct answer -->
            <div ng-if="renderQuestion[currentQues - 1].correctAns" class="col-md-12 ques_option">
                <h2 class="anshead">Correct Answer</h2>
                <div class="col-xs-12" style="padding-left: 38%;">
                    <div id="correctAnssetpatternAnswer_ans_[%parentIndex%]"></div>
                    <div style="border:2px solid #888888; width:340px; position:absolute; box-shadow:0px 0px 10px #888888;"></div>
                    <div id="correctAnssetpatternContainer_ques_[%parentIndex%]"></div>
                </div>
            </div>
            <!--end -->
        </div>
    </div>
</div>