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
                <div class="col-xs-12">
                    <span ng-show="quesele.option.mirrorline === 'right' || quesele.option.mirrorline === 'left'" style="border:2px solid #6f6f00; float:left;height:304px;">
                        <span>
                            <canvas id="ansCanvasleft[%parentIndex%]" width="200" height="300" ng-class="{'canvas-left': quesele.option.mirrorline === 'right'}" /> 
                            <canvas id="ansCanvasright[%parentIndex%]" width="200" height="300" ng-class="{'canvas-right': quesele.option.mirrorline === 'left'}"/>     
                        </span>
                    </span>
                </div>
                <div class="col-xs-12 margin-top-10" ng-show="renderQuestion[currentQues-1].userresponse[parentIndex].length">
                    <a class="reset-cursor" ng-click="clearDrawing(parentIndex)" href="javascript:void(0);">Clear Answer</a>
                </div>
            </div>
        </div>
    </div>
</div>