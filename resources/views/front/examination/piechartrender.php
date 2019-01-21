<div class="questionrender" ng-controller="piechartRenderCtrl">
    <div ng-repeat="(parentIndex, quesele) in renderQuestion[currentQues-1].questions">
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
            <div class="col-md-12 ques_option">
                <div class="col-xs-12">
                    <div class="col-xs-6">
                        <div id="preanswer[%quesIndex%]" style="background-image: url('/questionimg/[%quesele.option.value%]');background-repeat: no-repeat;width:600px; height:500px;margin-top:20px;"></div>
                    </div>
                    <div class="col-xs-6">
                        <div class="col-xs-10 col-xs-push-2 angular">
                            <span style="float:left;" class="chart" easypiechart percent="percentr[parentIndex]" options="options"></span>
                            <div class="list piechart-bullet-bold" style="top:-20px; left:118px;" ng-click="updatechart( 100, parentIndex )"></div>
                            <div class="list piechart-bullet" ng-click="updatechart( 5, parentIndex )" style="top:-14px; left:160px;"></div>
                            <div class="list piechart-bullet" ng-click="updatechart( 10, parentIndex )" style="top:2px; left:190px;"></div>
                            <div class="list piechart-bullet" ng-click="updatechart( 15, parentIndex )" style="top:28px; left:215px;"></div>
                            <div class="list piechart-bullet" ng-click="updatechart( 20, parentIndex )" style="top:60px; left:232px;"></div>
                            <div class="list piechart-bullet-bold" ng-click="updatechart( 25, parentIndex )" style="top:96px; left:240px;"></div>
                            <div class="list piechart-bullet" ng-click="updatechart( 30, parentIndex )" style="top:132px; left:236px;"></div>
                            <div class="list piechart-bullet" ng-click="updatechart( 35, parentIndex )" style="top:165px; left:222px;"></div>
                            <div class="list piechart-bullet" ng-click="updatechart( 40, parentIndex )" style="top:195px; left:195px;"></div>
                            <div class="list piechart-bullet" ng-click="updatechart( 45, parentIndex )" style="top:215px; left:162px;"></div>
                            <div class="list piechart-bullet-bold" ng-click="updatechart( 50, parentIndex )" style="top:225px; left:118px;"></div>
                            <div class="list piechart-bullet" ng-click="updatechart( 55, parentIndex )" style="top:220px; left:80px;"></div>
                            <div class="list piechart-bullet" ng-click="updatechart( 60, parentIndex )" style="top:202px; left:49px;"></div>
                            <div class="list piechart-bullet" ng-click="updatechart( 65, parentIndex )" style="top:176px; left:20px;"></div>
                            <div class="list piechart-bullet" ng-click="updatechart( 70, parentIndex )" style="top:142px; left:2px;"></div>
                            <div class="list piechart-bullet-bold" ng-click="updatechart( 75, parentIndex )" style="top:100px; left:-4px;"></div>
                            <div class="list piechart-bullet" ng-click="updatechart( 80, parentIndex )" style="top:66px; left:1px;"></div>
                            <div class="list piechart-bullet" ng-click="updatechart( 85, parentIndex )" style="top:35px; left:15px;"></div>
                            <div class="list piechart-bullet" ng-click="updatechart( 90, parentIndex )" style="top:8px; left:42px;"></div>
                            <div class="list piechart-bullet" ng-click="updatechart( 95, parentIndex )" style="top:-10px; left:76px;"></div>
                        </div>
                        <!--<div class="col-xs-10">
                            <input type="text" id="[%'chart'+ parentIndex%]" ng-model="percentr[parentIndex]" ng-change="updatechart( percentr[parentIndex], parentIndex )" class="btn-input-style text-center" />
                        </div>-->
                    </div>	
                </div>
            </div>
            <!-- render correct answer -->
            <div class="col-md-12 ques_option" ng-if="renderQuestion[currentQues - 1].correctAns">
                <h2 class="anshead">Correct Answer</h2>
                <div class="col-xs-12">
                    <div class="col-xs-6">
                        <div class="col-xs-10 col-xs-push-2 angular">
                            <span style="float:left;" class="chart" easypiechart percent="percentrCorrectAns[parentIndex]" options="options"></span>
                            <div class="list piechart-bullet-bold" style="top:-20px; left:118px;"></div>
                            <!--<div class="list piechart-bullet" ng-click="updatechart( 0, parentIndex )" style="top:-20px; left:118px;"></div>-->
                            <div class="list piechart-bullet" style="top:-14px; left:160px;"></div>
                            <div class="list piechart-bullet" style="top:2px; left:190px;"></div>
                            <div class="list piechart-bullet" style="top:28px; left:215px;"></div>
                            <div class="list piechart-bullet" style="top:60px; left:232px;"></div>
                            <div class="list piechart-bullet-bold" style="top:96px; left:240px;"></div>
                            <div class="list piechart-bullet" style="top:132px; left:236px;"></div>
                            <div class="list piechart-bullet" style="top:165px; left:222px;"></div>
                            <div class="list piechart-bullet" style="top:195px; left:195px;"></div>
                            <div class="list piechart-bullet" style="top:215px; left:162px;"></div>
                            <div class="list piechart-bullet-bold" style="top:225px; left:118px;"></div>
                            <div class="list piechart-bullet" style="top:220px; left:80px;"></div>
                            <div class="list piechart-bullet" style="top:202px; left:49px;"></div>
                            <div class="list piechart-bullet" style="top:176px; left:20px;"></div>
                            <div class="list piechart-bullet" style="top:142px; left:2px;"></div>
                            <div class="list piechart-bullet-bold" style="top:100px; left:-4px;"></div>
                            <div class="list piechart-bullet" style="top:66px; left:1px;"></div>
                            <div class="list piechart-bullet" style="top:35px; left:15px;"></div>
                            <div class="list piechart-bullet" style="top:8px; left:42px;"></div>
                            <div class="list piechart-bullet" style="top:-10px; left:76px;"></div>
                        </div>
                        <!--<div class="col-xs-10">
                            <input type="text" id="[%'chart'+ parentIndex%]" ng-model="percentrCorrectAns[parentIndex]" ng-change="updatechart( percentrCorrectAns[parentIndex], parentIndex )" class="btn-input-style text-center" />
                        </div>-->
                    </div>	
                </div>
            </div>
            <!--end -->
        </div>
    </div>
</div>