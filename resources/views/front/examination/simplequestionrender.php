<div class="questionrender" ng-controller="simplequesRenderCtrl">
    <div ng-repeat="(parentIndex, quesele) in renderQuestion[currentQues - 1].questions  track by $index">
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
        </div>
        <div class="input_row col-xs-push-1">
            <input type="text" class="input_text" ng-model="renderQuestion[currentQues - 1].userresponse[parentIndex].value">
        </div>
        <!-- render correct answer -->
        <div ng-if="renderQuestion[currentQues - 1].correctAns" class="input_row col-xs-push-1">
            <h2 class="anshead">Correct Answer</h2>
            <input type="text" class="input_text" ng-model="renderQuestion[currentQues - 1].correctAns[parentIndex].value">
            <!--<div class="col-xs-12" ng-repeat="ele in renderQuestion[currentQues - 1].correctAns[parentIndex]">
                <input type="text" class="input_text" ng-model="renderQuestion[currentQues-1].correctAns[parentIndex][$index].value"><br><br>
            </div>-->
        </div>
        <!--end -->
    </div>
</div>
<script>
$(document).ready(function(){
    setTimeout("spellcheckFunction()","1000");
});
function spellcheckFunction(){
    $('input[type="text"], textarea').attr('spellcheck', 'false');
}
</script>