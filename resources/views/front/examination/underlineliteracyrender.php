<div class="questionrender" ng-controller="underlineliteracyRenderCtrl">
    <div ng-repeat="(parentIndex, quesele) in renderQuestion[currentQues - 1].questions">
        <div class="col-md-12 ques_area">
            <div class="col-md-12 qDetail">
                <label class="pull-left"><span>[%parentIndex+1 | character%]</span></label>
                <div class="col-md-11 margin-top-5">
                    Select correct word or phrase and click Underline button.
                </div>
                <div class="marks_box">
                    <div class="mark_div">[%quesele.mark%]</div>
                    <div ng-show="quesele.mark <= 1">mark</div>
                    <div ng-show="quesele.mark > 1">marks</div>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="col-xs-11 margin-top-15"></div>
            </div>
            <div class="col-xs-12">
                <div class="col-xs-11 margin-top-15">
                    <span id="textEditor[%parentIndex%]" ng-bind-html="renderQuestion[currentQues - 1].userresponse[parentIndex].value | to_trusted" style="display:inline-block;"></span>
                    <span><button type="button" ng-click="underlinetext()" class="btn btn-default btn-sm btn-cursor-pointer ok-btn-color" style="display:inline-block;">Underline</button></span>
                </div>

            </div>
            <div class="col-xs-12 margin-top-10">
                <div class="col-xs-8">
                    <a class="reset-cursor" ng-click="resetUnderline(parentIndex)" href="javascript:void(0);">Clear Answer</a>
                    <!--<a ng-click="getMappingVal(parentIndex)" href="javascript:void(0);">Show mapping</a>-->
                </div>
            </div>
            <!-- render correct answer -->
            <div ng-if="renderQuestion[currentQues - 1].correctAns">
                <div class="col-xs-12">
                    <div class="col-xs-11 margin-top-15"></div>
                </div>
                <h2 class="anshead">Correct Answer</h2>
                <div class="col-xs-12">
                    <div class="col-xs-11 margin-top-15">
                        <span id="textEditor[%parentIndex%]" ng-bind-html="renderQuestion[currentQues - 1].correctAns[parentIndex].value | to_trusted" style="display:inline-block;"></span>
                    </div>

                </div>
            </div>
            <!--end -->
        </div>
    </div>
</div>