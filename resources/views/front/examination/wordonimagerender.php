<div class="questionrender" ng-controller="wordonimageRenderCtrl">
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
                <div class="col-xs-8 col-xs-push-2 margin-bottom-10">
                    <div class="col-xs-12 margin-bottom-20">
                        <div style="width:600px;height:500px;">
                            <img ng-src="/questionimg/[%quesele.option.value%]"/>
                        </div>
                    </div>
                    <div class="col-xs-12" ng-repeat="eleAnswer in renderQuestion[currentQues - 1].userresponse[parentIndex]">
                        <div class="col-xs-12 margin-bottom-10 text-left">[%eleAnswer.label%]&nbsp;<input ng-model="renderQuestion[currentQues - 1].userresponse[parentIndex][$index].value" type="text" class="btn-input-style text-center" /></div>
                    </div>

                </div>
                
            </div>
            <!-- render correct answer -->
            <div ng-if="renderQuestion[currentQues - 1].correctAns" class="col-xs-12 margin-bottom-20">
                <h2 class="anshead">Correct Answer</h2>
                <div class="col-xs-12" ng-repeat="eleAnswer in renderQuestion[currentQues - 1].correctAns[parentIndex]">
                   <div class="col-xs-12 margin-bottom-10 text-left">[%eleAnswer.label%]&nbsp;<input ng-model="renderQuestion[currentQues - 1].correctAns[parentIndex][$index].value" type="text" class="btn-input-style text-center" /></div>
                </div>
            </div>
                <!--end -->
        </div>
    </div>
</div>