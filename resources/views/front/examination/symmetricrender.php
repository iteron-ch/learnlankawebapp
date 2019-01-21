<div class="questionrender" ng-controller="symmetricRenderCtrl">
    <div ng-repeat="(parentIndex, quesele) in renderQuestion[currentQues-1].questions  track by $index">
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
                <div class="col-xs-12 margin-bottom-20" style="text-align:left;">
                    <span style="margin-left:20px;">
                        <!--<input type="checkbox" ng-model="renderQuestion[currentQues-1].userresponse[parentIndex][0].ischeck" class="text-center"/>-->
                        <input type="checkbox" ng-model="renderQuestion[currentQues-1].userresponse[parentIndex][0].ischeck" name="symmetriccheckbox[%parentIndex%]" id="symmetriccheckbox[%parentIndex%][%$index%]1" class="custom_multiple ng-hide" />
			<label for="symmetriccheckbox[%parentIndex%][%$index%]1"><span>&nbsp;</span></label>
                        <img src="../../images/icon-1.gif" alt="">
                    </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span>
                        <input type="checkbox" ng-model="renderQuestion[currentQues-1].userresponse[parentIndex][1].ischeck" name="symmetriccheckbox[%parentIndex%]" id="symmetriccheckbox[%parentIndex%][%$index%]2" class="custom_multiple ng-hide" />
			<label for="symmetriccheckbox[%parentIndex%][%$index%]2"><span>&nbsp;</span></label>
                        <img src="../../images/icon-2.gif" alt="">
                    </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span>
                        <input type="checkbox" ng-model="renderQuestion[currentQues-1].userresponse[parentIndex][2].ischeck" name="symmetriccheckbox[%parentIndex%]" id="symmetriccheckbox[%parentIndex%][%$index%]3" class="custom_multiple ng-hide" />
			<label for="symmetriccheckbox[%parentIndex%][%$index%]3"><span>&nbsp;</span></label>
                        <img src="../../images/icon-3.gif" alt="">
                    </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span>
                        <input type="checkbox" ng-model="renderQuestion[currentQues-1].userresponse[parentIndex][3].ischeck" name="symmetriccheckbox[%parentIndex%]" id="symmetriccheckbox[%parentIndex%][%$index%]4" class="custom_multiple ng-hide" />
			<label for="symmetriccheckbox[%parentIndex%][%$index%]4"><span>&nbsp;</span></label>
                        <img src="../../images/icon-4.gif" alt="">
                    </span>
                </div>
                <div class="col-md-12">
                    <div class="image_box border_1_big" ng-show="renderQuestion[currentQues-1].userresponse[parentIndex][0].ischeck"></div>
                    <div class="image_box border_2_big" ng-show="renderQuestion[currentQues-1].userresponse[parentIndex][1].ischeck"></div>
                    <div class="image_box border_3_big" ng-show="renderQuestion[currentQues-1].userresponse[parentIndex][2].ischeck"></div>
                    <div class="image_box border_4_big" ng-show="renderQuestion[currentQues-1].userresponse[parentIndex][3].ischeck"></div>
                    <div style="background-image: url(/questionimg/[%quesele.imgPath%]); background-repeat: no-repeat; background-size: 100% 100%;" id="patternContainer[%parentIndex%]"></div>
                </div>
            </div>
             <!-- render correct answer -->
             <div ng-if="renderQuestion[currentQues - 1].correctAns" class="col-md-12 ques_option" style="margin-top: 220px;">  
                <h2 class="anshead">Correct Answer</h2>
                <div class="col-md-12">
                    <div class="image_box border_1_big" ng-show="renderQuestion[currentQues-1].correctAns[parentIndex][0].ischeck"></div>
                    <div class="image_box border_2_big" ng-show="renderQuestion[currentQues-1].correctAns[parentIndex][1].ischeck"></div>
                    <div class="image_box border_3_big" ng-show="renderQuestion[currentQues-1].correctAns[parentIndex][2].ischeck"></div>
                    <div class="image_box border_4_big" ng-show="renderQuestion[currentQues-1].correctAns[parentIndex][3].ischeck"></div>
                    <div style="background-image: url(/questionimg/[%quesele.imgPath%]); background-repeat: no-repeat; background-size: 100% 100%;" id="patternContainerCorrectAns[%parentIndex%]"></div>
                </div>
            </div>
             <!--end -->
        </div>
    </div>
</div>