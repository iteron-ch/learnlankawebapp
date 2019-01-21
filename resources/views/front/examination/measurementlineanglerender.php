<div class="questionrender" ng-controller="measurementlineangleRenderCtrl">
    <div ng-repeat="(parentIndex, quesele) in renderQuestion[currentQues - 1].questions">
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
            <div class="col-md-12 ques_option">
                <div class="col-xs-12">
                    <div style="background-image: url('/questionimg/[%quesele.option.value%]');background-repeat: no-repeat;width:600px; height:500px;margin-top:20px;">
                        <canvas id="canswer[%parentIndex%]" width="600" height="500"></canvas>
                    </div>
                </div>
                <div class="col-xs-12 margin-top-10" ng-repeat="eleAnswer in renderQuestion[currentQues - 1].userresponse[parentIndex]">
                    <div class="col-xs-2">[%eleAnswer.label%]</div>
                    <div class="col-xs-2 col-xs-pull-1 margin-bottom-10">
                        <input type="text" ng-model="renderQuestion[currentQues - 1].userresponse[parentIndex][$index].value" class="btn-input-style text-center" />
                    </div>
                </div>
                <!-- render correct answer -->
                <div ng-if="renderQuestion[currentQues - 1].correctAns">
                    <h2 class="anshead">Correct Answer</h2>
                    <div class="col-xs-12 margin-top-10" ng-repeat="eleAnswer in renderQuestion[currentQues - 1].correctAns[parentIndex]">
                        <div class="col-xs-2">[%eleAnswer.label%]</div>
                        <div class="col-xs-2 col-xs-pull-1 margin-bottom-10">
                            <input type="text" ng-model="renderQuestion[currentQues - 1].correctAns[parentIndex][$index].value" class="btn-input-style text-center" />
                        </div>
                    </div>
                </div>
                <!--end -->
            </div>
        </div>
    </div>
</div>