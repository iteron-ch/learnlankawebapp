<div class="questionrender" ng-controller="joiningdotsRenderCtrl">
    <div ng-repeat="(parentIndex, quesele) in renderQuestion[currentQues - 1].questions  track by $index">
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
                <div style="background-image: url(/questionimg/[%quesele.imgPath%]);background-repeat: no-repeat;background-size: 100% 100%;" id="patternContainer[%parentIndex%]"></div>
            </div>
            <div class="col-xs-12 margin-top-10" ng-show="renderQuestion[currentQues - 1].userresponse[parentIndex].length">
                <a class="reset-cursor" ng-click="clearDrawing(parentIndex)" href="javascript:void(0);">Clear Answer</a>
            </div>
            <!-- render correct answer -->
            <div ng-if="renderQuestion[currentQues - 1].correctAns">
                <h2 class="anshead">Correct Answer</h2>
                <div class="col-md-12 ques_option">                        
                    <div style="background-image: url(/questionimg/[%quesele.imgPath%]);background-repeat: no-repeat;background-size: 100% 100%;" id="patternContainerCorrectAns[%parentIndex%]"></div>
                </div>
            </div>
            <!--end -->
        </div>
    </div>
</div>