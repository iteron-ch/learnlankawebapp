<div class="questionrender" ng-controller="fillintheBlanksRenderCtrl">
    <div ng-repeat="(parentIndex, quesele) in renderQuestion[currentQues - 1].questions  track by $index">
        <div class="col-md-12 ques_area">
            <div class="col-md-12 qDetail">
                <label class="pull-left"><span>[%parentIndex+1 | character%]</span></label>
                <div id="[%parentIndex%]" class="col-md-11 input_traget_css" ng-bind-html="quesele.ques | to_trusted"></div>
                <!-- render correct answer -->
                <div ng-if="renderQuestion[currentQues - 1].correctAns">
                    <h2 class="anshead">Correct Answer</h2>
                    <div id="correctAns[%parentIndex%]" class="col-md-11 input_traget_css" ng-bind-html="quesele.ques | to_trusted"></div>
                </div>
                <!--end -->
                <div class="marks_box">
                    <div class="mark_div">[%quesele.mark%]</div>
                    <div ng-show="quesele.mark <= 1">mark</div>
                    <div ng-show="quesele.mark > 1">marks</div>
                </div>
            </div>
        </div>
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