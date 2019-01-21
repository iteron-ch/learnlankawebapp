<div class="questionrender" ng-controller="drawinggraphsRenderCtrl">
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
                <div class="col-xs-12">
                    <div id="setanswer[%parentIndex%]" class="col-xs-12 margin-top-15 margin-bottom-20">
                        <span  class="draggable pull-left" style="border: 1px solid #000000; background-color:#edf6ff; margin:10px; padding:5px 10px 8px 10px;" ng-repeat="dragele in quesele.option">[%dragele.value%]</span>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div id="dropanswer[%parentIndex%]" class="col-xs-12 margin-top-15 margin-bottom-20" style="width:600px; height:500px;">
                        <div class="fileinput-preview thumbnail" style="border:1px solid #ffffff; width:600px; height:500px; background-image: url([%backgroundimage[parentIndex]%]);"></div>
                        <span ng-repeat="optionele in quesele.option" ng-style="{'float':'left', 'position':'absolute', 'top':optionele.position.top, 'left':optionele.position.left}">
                            <div class="droppable" id="label_[%parentIndex%]_[%$index%]" type="text" ng-style="{'display': 'table-cell', 'float': 'none', 'vertical-align': 'middle', 'text-align': 'center', 'font-weight':'bold', 'width':optionele.position.width, 'height':optionele.position.height}">[%renderQuestion[currentQues-1].userresponse[parentIndex][$index].value%]</div>
                        </span>
                    </div>
                </div>
                <!-- render correct answer -->
                <div ng-if="renderQuestion[currentQues - 1].correctAns" class="input_row col-xs-push-1">
                    <h2 class="anshead">Correct Answer</h2>
                    <div class="col-xs-12">
                        <div id="dropanswerCorrectAns[%parentIndex%]" class="col-xs-12 margin-top-15 margin-bottom-20" style="width:600px; height:500px;">
                            <div class="fileinput-preview thumbnail" style="border:1px solid #ffffff; width:600px; height:500px; background-image: url([%backgroundimage[parentIndex]%]);"></div>
                            <span ng-repeat="optionele in quesele.option" ng-style="{'float':'left', 'position':'absolute', 'top':optionele.position.top, 'left':optionele.position.left}">
                                <div class="droppable" id="CorrectAnslabel_[%parentIndex%]_[%$index%]" type="text" ng-style="{'display': 'table-cell', 'float': 'none', 'vertical-align': 'middle', 'text-align': 'center', 'font-weight':'bold', 'width':optionele.position.width, 'height':optionele.position.height}">[%renderQuestion[currentQues-1].correctAns[parentIndex][$index].value%]</div>
                            </span>
                        </div>
                    </div>
                </div>
                <!--end -->
            </div>
        </div>
    </div>
</div>