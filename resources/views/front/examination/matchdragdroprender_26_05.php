<div class="questionrender" ng-controller="matchdragdropRenderCtrl">
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
            
            <div class="col-md-12 ques_option match_drag_bo_width">                        
                <div class="col-xs-8 col-xs-push-2 margin-bottom-10" id="setheight[%parentIndex%]">
                    <div id="svgbasics[%parentIndex%]" class="svgbasics"></div>
                    <div id="pnlAllIn[%parentIndex%]" class="pnlAllIn">
                        <div class="col-xs-12">
                            <div class="col-xs-4 text-center">
                                <h4><b>[%quesele.header.left%]</b></h4>
                            </div>
                            <div class="col-xs-4 col-xs-push-2 text-center">
                                 <h4><b>[%quesele.header.right%]</b></h4>
                            </div>
                        </div>
                        <div id="leftPanel[%parentIndex%]" class="leftPanel">
                            <div class="person [%leftele.cls%]" id="l_[%$index%]_[%parentIndex%]" ng-repeat="leftele in leftPersons[parentIndex] track by $index">
                                <input type="hidden" value="[%leftele.id%]" />
                                <ul class="name"><li><h2 ng-bind-html="leftele.name | to_trusted"></h2></li></ul>
                            </div>
                        </div>
                        <div id="rightPanel[%parentIndex%]" class="rightPanel">
                            <div class="person [%rightele.cls%]" id="r_[%$index%]_[%parentIndex%]" ng-repeat="rightele in rightPersons[parentIndex]  track by $index" my-repeat-directive>
                                <input type="hidden" value="[%rightele.id%]" />
                                <ul class="name"><li><h2 ng-bind-html="rightele.name | to_trusted"></h2></li></ul>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>			       
                </div>
                <div class="col-xs-12 margin-top-10">
                    <div class="col-xs-8 col-xs-push-1">
                        <a class="reset-cursor" ng-click="clearMapping(parentIndex)" href="javascript:void(0);">Clear Answer</a>
                        <!--<a ng-click="getMappingVal(parentIndex)" href="javascript:void(0);">Show mapping</a>-->
                    </div>
                </div>
            </div>
            
            <!-- render correct answer -->
            <div ng-if="renderQuestion[currentQues - 1].correctAns" class="col-md-12 ques_option match_drag_bo_width">
                <h2 class="anshead">Correct Answer</h2>
                <div class="col-xs-8 col-xs-push-2 margin-bottom-10" id="correctAnssetheight[%parentIndex%]">
                    <div id="correctAnssvgbasics[%parentIndex%]" class="svgbasics"></div>
                    <div id="correctAnspnlAllIn[%parentIndex%]" class="pnlAllIn">
                        <div class="col-xs-12">
                            <div class="col-xs-4 text-center">
                                <h4><b>[%quesele.header.left%]</b></h4>
                            </div>
                            <div class="col-xs-4 col-xs-push-2 text-center">
                                 <h4><b>[%quesele.header.right%]</b></h4>
                            </div>
                        </div>
                        <div id="correctAnsleftPanel[%parentIndex%]" class="leftPanel">
                            <div class="person [%leftele.cls%]" id="correctAnsl_[%$index%]_[%parentIndex%]" ng-repeat="leftele in leftPersonscorrectAns[parentIndex] track by $index" style="min-height: 58px;">
                                <input type="hidden" value="[%leftele.id%]" />
                                <ul class="name"><li><h2 ng-bind-html="leftele.name | to_trusted"></h2></li></ul>
                            </div>
                        </div>
                        <div id="correctAnsrightPanel[%parentIndex%]" class="rightPanel">
                            <div class="person [%rightele.cls%]" id="correctAnsr_[%$index%]_[%parentIndex%]" ng-repeat="rightele in rightPersonscorrectAns[parentIndex]  track by $index" my-repeat-directive style="min-height: 58px;">
                                <input type="hidden" value="[%rightele.id%]" />
                                <ul class="name"><li><h2 ng-bind-html="rightele.name | to_trusted"></h2></li></ul>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>			       
                </div>
            </div>
            <!--end -->
        </div>
    </div>
</div>