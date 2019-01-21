<div class="questionrender" ng-controller="drawlineonimageRenderCtrl">
    <div ng-repeat="(parentIndex, quesele) in renderQuestion[currentQues - 1].questions">
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
                <div class="col-xs-12 margin-top-15 margin-bottom-20" style="width: 600px; height: 500px;">
                    <div><canvas id="setansCanvasImg[%parentIndex%]" width="600" height="500"></canvas></div>
                    <div style="top: 0px;position: absolute; border:1px solid #e5e5e5;"><canvas id="setansCanvas[%parentIndex%]" width="600" height="500"></canvas></div>
                </div>
            </div>
            <div class="col-xs-12 margin-top-10" ng-show="renderQuestion[currentQues - 1].userresponse[parentIndex].length">
                <div class="col-xs-8 col-xs-push-1">
                    <a class="reset-cursor" ng-click="clearDrawLine(parentIndex)" href="javascript:void(0);">Clear Answer</a>
                    <!--<a ng-click="getMappingVal(parentIndex)" href="javascript:void(0);">Show mapping</a>-->
                </div>
            </div>
            <!-- render correct answer -->
            <div ng-if="renderQuestion[currentQues - 1].correctAns">
                <h2 class="anshead">Correct Answer</h2>
                <div class="col-md-12 ques_option">
                    <div class="col-xs-12 margin-top-15 margin-bottom-20" style="width: 600px; height: 500px;">
                        <div><canvas id="setansCanvasImgCorrectAns[%parentIndex%]" width="600" height="500"></canvas></div>
                        <div style="top: 0px;position: absolute; border:1px solid #e5e5e5;"><canvas id="setansCanvasCorrectAns[%parentIndex%]" width="600" height="500"></canvas></div>
                    </div>
                </div>
            </div>
            <!--end -->
        </div>
    </div>
</div>