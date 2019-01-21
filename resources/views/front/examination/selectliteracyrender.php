<div class="questionrender" ng-controller="selectliteracyRenderCtrl">
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
                <div class="col-xs-12">
                    <span style="float:left;position: relative;margin-right:5px;" ng-repeat="childEleA in quesele.option"  >
                        <span ng-class="{'spandecoration': childEleA.ischeck}" ng-bind-html="childEleA.option | to_trusted"></span>
                        </br>
                        <div class="arrow1" ng-if="childEleA.ischeck"></div>
                        <input name="answer_[%parentIndex%][%$index%]" id="answer_[%parentIndex%][%$index%]" type="text" style="width:52px;" ng-if="childEleA.ischeck">
                    </span>
                </div>
                <!-- render correct answer -->
                <div ng-if="renderQuestion[currentQues - 1].correctAns">
                    <h2 class="anshead">Correct Answer</h2>
                    <div class="col-xs-12">
                        <span style="float:left;position: relative;margin-right:5px;" ng-repeat="childEleA in quesele.option"  >
                            <span ng-class="{'spandecoration': childEleA.ischeck}" ng-bind-html="childEleA.option | to_trusted"></span>
                            </br>
                            <div class="arrow1" ng-if="childEleA.ischeck"></div>
                            <input name="Correctanswer_[%parentIndex%][%$index%]" id="Correctanswer_[%parentIndex%][%$index%]" type="text" style="width:52px;" ng-if="childEleA.ischeck">
                        </span>
                    </div>
                </div>
                <!--end -->
            </div>
        </div>
    </div>
</div>