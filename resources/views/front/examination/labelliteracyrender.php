<div class="questionrender" ng-controller="labelliteracyRenderCtrl">
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
            <div class="col-xs-12 margin-bottom-20" id="question[%parentIndex%]">
                <div class="col-xs-12 margin-bottom-5 margin-top-15" ng-if="quesele.literacyType == 'drag'">
                    <span class="draggable" ng-repeat="ele in quesele.answerOption" style="border:1px solid #000000; background-color:#edf6ff; margin:10px; padding:5px 10px 8px 10px; z-index: 1;">[%ele.value%]</span>	
                </div>
                <div class="col-xs-12 margin-top-15">
                    <span class="box_center check_box_center" ng-repeat="childEleA in quesele.option"  >
                        <span ng-class="{'spandecoration': childEleA.ischeck}" ng-bind-html="childEleA.option | to_trusted"></span>
                        <div class="arrow1" ng-if="childEleA.ischeck"></div>
                        <input name="answer_[%parentIndex%][%$index%]" id="answer_[%parentIndex%][%$index%]" ng-hide="true" type="checkbox" class="custom_multiple" ng-model="renderQuestion[currentQues - 1].userresponse[parentIndex][$index]" ng-if="childEleA.ischeck && quesele.literacyType == 'tick'">
                        <label ng-if="childEleA.ischeck && quesele.literacyType == 'tick'" for="answer_[%parentIndex%][%$index%]"><span></span></label>
                        <input class="droppable" name="answer_[%parentIndex%][%$index%]" id="answer_[%parentIndex%]_[%$index%]" type="text" style="width:52px;" ng-model="renderQuestion[currentQues - 1].userresponse[parentIndex][$index]" ng-if="childEleA.ischeck && quesele.literacyType == 'drag'">
                    </span>
                </div>
            </div>
            <!-- render correct answer -->
            <div ng-if="renderQuestion[currentQues - 1].correctAns" class="col-xs-12 margin-bottom-20">
                <div class="col-xs-12 margin-bottom-20"><h2 class="anshead">Correct Answer</h2></div>
                <div class="col-xs-12 margin-bottom-20" id="questionCorrectAns[%parentIndex%]">
                    <div class="col-xs-12 margin-bottom-5 margin-top-15" ng-if="quesele.literacyType == 'drag'">
                        <span class="draggable" ng-repeat="ele in quesele.answerOption" style="border:1px solid #000000; background-color:#edf6ff; margin:10px; padding:5px 10px 8px 10px; z-index: 1;">[%ele.value%]</span>	
                    </div>
                    <div class="col-xs-12 margin-top-15">
                        <span class="box_center check_box_center" ng-repeat="childEleA in quesele.option"  >
                            <span ng-class="{'spandecoration': childEleA.ischeck}" ng-bind-html="childEleA.option | to_trusted"></span>
                            <div class="arrow1" ng-if="childEleA.ischeck"></div>
                            <input name="answerCorrectAns_[%parentIndex%][%$index%]" id="answerCorrectAns_[%parentIndex%][%$index%]" ng-hide="true" type="checkbox" class="custom_multiple" ng-model="renderQuestion[currentQues - 1].correctAns[parentIndex][$index]" ng-if="childEleA.ischeck && quesele.literacyType == 'tick'">
                            <label ng-if="childEleA.ischeck && quesele.literacyType == 'tick'" for="answerCorrectAns_[%parentIndex%][%$index%]"><span></span></label>
                            <input class="droppable" name="answerCorrectAns_[%parentIndex%][%$index%]" id="answerCorrectAns_[%parentIndex%]_[%$index%]" type="text" style="width:52px;" ng-model="renderQuestion[currentQues - 1].correctAns[parentIndex][$index]" ng-if="childEleA.ischeck && quesele.literacyType == 'drag'">
                        </span>
                    </div>
                </div>
            </div>
            <!--end -->
        </div>
    </div>
</div>