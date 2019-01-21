<div class="questionrender shadingshape" ng-controller="shadingshapeRenderCtrl">
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
                <div class="col-xs-12 setAnsShape" id="setAnsShape[%parentIndex%]">
                    <div class="col-xs-12">
                        <table cellspacing="0" cellpadding="0">
                            <tr ng-repeat="(trAIndex, eleAtr) in  renderQuestion[currentQues - 1].userresponse[parentIndex] track by $index">
                                <td class="[%renderQuestion[currentQues-1].userresponse[parentIndex][trAIndex][tdAIndex].class%]" id="correct_[%parentIndex%]_[%trAIndex%]_[%tdAIndex%]" ng-repeat="(tdAIndex, eleAtd) in  eleAtr track by $index">
                                    <span>&nbsp;</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <!-- render correct answer -->
            <div ng-if="renderQuestion[currentQues - 1].correctAns">
                <h2 class="anshead">Correct Answer</h2>
                <div class="col-md-12 ques_option">
                    <div class="col-xs-12 setAnsShape" id="setAnsShapeCorrectAns[%parentIndex%]">
                        <div class="col-xs-12">
                            <table cellspacing="0" cellpadding="0">
                                <tr ng-repeat="(trAIndex, eleAtr) in  renderQuestion[currentQues - 1].correctAns[parentIndex] track by $index">
                                    <td class="[%renderQuestion[currentQues-1].correctAns[parentIndex][trAIndex][tdAIndex].class%]" id="correctCorrectAns_[%parentIndex%]_[%trAIndex%]_[%tdAIndex%]" ng-repeat="(tdAIndex, eleAtd) in  eleAtr track by $index">
                                        <span>&nbsp;</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--end -->
        </div>
    </div>
</div>