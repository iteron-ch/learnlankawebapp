<div class="questionrender" ng-controller="spellingaudioRenderCtrl">
    <div ng-repeat="(parentIndex, quesele) in renderQuestion[currentQues-1].questions  track by $index">
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
            <div class="col-xs-12">
                <audio controls autoplay>
                    <source src="[%'/uploads/questionbuilder/'+quesele.option.value%]" type="audio/ogg">
                    <source src="[%'/uploads/questionbuilder/'+quesele.option.value%]" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
            </div>
        </div>
        <!-- render correct answer -->
        <div ng-if="renderQuestion[currentQues - 1].correctAns" class="col-md-12">
            <h2 class="anshead">Correct Answer</h2>
            <div class="col-md-12 qDetail">
               <div id="CorrectAns[%parentIndex%]" class="col-md-11 margin-top-5" ng-bind-html="quesele.ques | to_trusted"></div>
            </div>
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