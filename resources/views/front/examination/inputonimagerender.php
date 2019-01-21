<div class="questionrender" ng-controller="inputonimageRenderCtrl">
    <div ng-repeat="(parentIndex, quesele) in renderQuestion[currentQues-1].questions">
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
                    <div class="col-xs-12 margin-top-15 margin-bottom-20" style="width: 600px; height: 500px;">
                        <div class="fileinput-preview thumbnail" style="border:1px solid #ffffff; width:600px; height:500px; background-image: url([%backgroundimage[parentIndex]%]); background-repeat: no-repeat;"></div>
                        <span ng-repeat="optionele in quesele.option" ng-style="{'position':'absolute', 'top':optionele.position.top, 'left':optionele.position.left}">
                            <input class="text-center" ng-model="renderQuestion[currentQues-1].userresponse[parentIndex][$index].value" type="text" ng-style="{'background':'transparent', 'border':'0px solid #ffffff', 'border-radius':'5px !important', 'font-size':'25px', 'font-weight':'bold', 'color':'#000000', 'width':optionele.position.width, 'height':optionele.position.height}"></input>
                        </span>                        
                    </div>
                </div>
            </div>
            <!-- render correct answer -->
            <div ng-if="renderQuestion[currentQues - 1].correctAns">
                <h2 class="anshead">Correct Answer</h2>
                <div class="col-xs-12">
                    <div class="col-xs-12 margin-top-15 margin-bottom-20" style="width: 600px; height: 500px;">
                        <div class="fileinput-preview thumbnail" style="border:1px solid #ffffff; width:600px; height:500px; background-image: url([%backgroundimage[parentIndex]%]); background-repeat: no-repeat;"></div>
                        <span ng-repeat="optionele in quesele.option" ng-style="{'position':'absolute', 'top':optionele.position.top, 'left':optionele.position.left}">
                            <input class="text-center" ng-model="renderQuestion[currentQues-1].correctAns[parentIndex][$index].value" type="text" ng-style="{'background':'transparent', 'border':'0px solid #ffffff', 'border-radius':'5px !important', 'font-size':'25px', 'font-weight':'bold', 'color':'#000000', 'width':optionele.position.width, 'height':optionele.position.height}"></input>
                        </span>                        
                    </div>
                </div>
                
            </div>
            <!--end -->
        </div>
    </div>
</div>