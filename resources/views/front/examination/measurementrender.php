<div class="questionrender" ng-controller="measurementRenderCtrl">
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
                    <canvas id="measurementCanvas[%parentIndex%]" height="[%canvasHeigth%]" width="[%canvasWidth%]"></canvas>
                </div>
            </div>
            <div class="col-xs-12 margin-top-10" ng-show="renderQuestion[currentQues-1].userresponse[parentIndex]">
                <div class="col-xs-6"><a class="reset-cursor" ng-click="clearDrawing(parentIndex)" href="javascript:void(0);">Clear Answer</a></div>
            </div>

            <!-- render correct answer -->
            <div ng-if="renderQuestion[currentQues - 1].correctAns" class="col-xs-12 margin-top-10">
                <h2 class="anshead">Correct Answer</h2>
                <canvas id="measurementCanvasAns[%parentIndex%]" height="[%canvasHeigth%]" width="[%canvasWidth%]"></canvas>
                <!--<div class="col-md-6" >[%renderQuestion[currentQues-1].correctAns[parentIndex]%]</div>-->
            </div>
            <!--end -->
        </div>
    </div>
</div>