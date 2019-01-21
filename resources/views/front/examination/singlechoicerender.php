<div class="questionrender" ng-controller="singleChoiceRenderCtrl">
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
                <div class="col-xs-12" ng-repeat="ele in renderQuestion[currentQues-1].questions[$index].option">
                    <div style="display: flex;">
                        
                        <input type="radio" name="singlechoiceradio[%parentIndex%]" id="singlechoiceradio[%parentIndex%][%$index%]" value=[%$index%] ng-model="renderQuestion[currentQues-1].userresponse[parentIndex]" ng-click="correctanswer(parentIndex, $index)" class="custom_single ng-hide" / >
                        <label for="singlechoiceradio[%parentIndex%][%$index%]"><span>&nbsp;</span></label>
                        <!--<input type="radio" name="singlechoiceradio[%parentIndex%]" value=[%$index%] ng-model="renderQuestion[currentQues-1].userresponse[parentIndex]" ng-click="correctanswer(parentIndex, $index)">-->
                        <div class="col-xs-12 text-left" style="display: inline-block;" ng-bind-html="ele.option | to_trusted"></div>
                    </div>
                </div>
            </div>
            <!-- render correct answer -->
            <div ng-if="renderQuestion[currentQues-1].correctAns" class="col-md-12 ques_option">
                <h2 class="anshead">Correct Answer</h2>
                <div class="col-xs-12" ng-repeat="ele in renderQuestion[currentQues-1].questions[$index].option">
                    <div style="display: flex;">
                        <input type="radio" name="singlechoiceradioCorrectAns[%parentIndex%]" id="singlechoiceradioCorrectAns[%parentIndex%][%$index%]" value=[%$index%] ng-model="renderQuestion[currentQues-1].correctAns[parentIndex]" class="custom_single ng-hide" />
                        <label for="singlechoiceradioCorrectAns[%parentIndex%][%$index%]"><span>&nbsp;</span></label>
                        <!--<input type="radio" name="singlechoiceradio[%parentIndex%]" value=[%$index%] ng-model="renderQuestion[currentQues-1].userresponse[parentIndex]" ng-click="correctanswer(parentIndex, $index)">-->
                        <div class="col-xs-12 text-left" style="display: inline-block;" ng-bind-html="ele.option | to_trusted"></div>
                    </div>
                </div>
            </div>
             <!--end -->
        </div>
    </div>
</div>