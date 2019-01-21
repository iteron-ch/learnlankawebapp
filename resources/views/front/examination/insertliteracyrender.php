<div class="questionrender" ng-controller="insertliteracyRenderCtrl">
    <div ng-repeat="(parentIndex, quesele) in renderQuestion[currentQues - 1].questions">
        <div class="col-md-12 ques_area">
            <div class="col-md-12 qDetail">
                <label class="pull-left"><span>[%parentIndex+1 | character%]</span></label>
                <div class="col-md-11 margin-top-5">Correct the sentences <span style="background: #cbedff;color: #333;" ng-bind-html="quesele.ques | to_trusted" ></span></div>
                <div class="marks_box">
                    <div class="mark_div">[%quesele.mark%]</div>
                    <div ng-show="quesele.mark <= 1">mark</div>
                    <div ng-show="quesele.mark > 1">marks</div>
                </div>
            </div>
            <div class="col-xs-12 symble_drag">
                <span  class="draggable" ng-repeat="dragele in dragObjArray">[%dragele%]</span>
            </div>
            <div class="col-xs-12 droppable_box">
                <div class="col-xs-11" id="textEditor[%parentIndex%]" ng-bind-html="bindHtmlText(quesele.ques, parentIndex) | to_trusted" ></div>
            </div>
            <!-- render correct answer -->
            <div ng-if="renderQuestion[currentQues - 1].correctAns">
                <h2 class="anshead">Correct Answer</h2>
                <div class="col-xs-12 droppable_box">
                    <div class="col-xs-11" id="textEditorCorrectAns[%parentIndex%]" ng-bind-html="bindHtmlText(quesele.ques, parentIndex) | to_trusted" ></div>
                </div>
            </div>
            <!--end -->
        </div>
    </div>
</div>