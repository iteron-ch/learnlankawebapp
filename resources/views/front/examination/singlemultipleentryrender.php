<div class="questionrender" ng-controller="singlemultipleentryRenderCtrl">
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
                <table>											
                    <tr ng-repeat="(pIndex, subchildele) in renderQuestion[currentQues - 1].userresponse[parentIndex].option track by pIndex">
                        <td ng-hide="(field.title && !renderQuestion[currentQues - 1].questions[parentIndex].headercol && pIndex == 0) || (field.title && !renderQuestion[currentQues - 1].questions[parentIndex].headerrow && $index == 0)" ng-repeat="field in subchildele track by $index">
                            <span ng-if="!field.title && quesele.questype != 'input'">
                                <input type="checkbox" ng-hide="true" class="custom_[%quesele.questype%]" id="checkbox[%parentIndex%][%pIndex%][%$index%]" 
                                       name="checkbox[%parentIndex%][%pIndex%][%$index%]" ng-click="updateValues(quesele.questype, parentIndex, pIndex, $index)" ng-model="field.checkvalue"/>
                                <label style="float:left;" for="checkbox[%parentIndex%][%pIndex%][%$index%]" ng-click="updateValues(quesele.questype, parentIndex, pIndex, $index)"><span ng-click="updateValues(quesele.questype, parentIndex, pIndex, $index)"></span>
                                    <div style="font-weight:normal; padding-left:2px;" class="pull-right" ng-bind-html="field.value | to_trusted"></div>
                                </label>		
                            </span>
                            <span ng-if="!field.title && quesele.questype == 'input'">
                                <input ng-show="quesele.questype == 'input'" type="text" style="width: auto; font-weight:normal;" ng-model="field.checkvalue" />
                            </span>
                            <span ng-if="field.title">
                                <div style="font-weight:normal; padding-left:2px;" ng-bind-html="field.value | to_trusted"></div>
                            </span>
                        </td>
                        <td ng-show="false">
                            <a ng-click="removeRow(parentIndex, pIndex)" ng-hide="element.option.length < 4 || pIndex === 0">
                                <span class="glyphicon glyphicon-remove btn-cursor-pointer btn-font-18 pull-right" tooltip="Remove" style="padding-top: 2px;padding-right: 10px;"></span>
                            </a>
                        </td>
                    </tr>
                    <tr ng-show="false">
                        <td class="remove-tr-border" ng-repeat="field in questionJSON.questions[parentIndex].option[0]">
                            <a ng-click="removeColumn(parentIndex, $index)" ng-hide="element.option[0].length < 4" ng-if="$index != 0">
                                <span class="glyphicon glyphicon-remove btn-cursor-pointer btn-font-18 pull-right" tooltip="Remove" style="padding-top: 2px;padding-right: 10px;"></span>
                            </a>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- render correct answer -->
            <div ng-if="renderQuestion[currentQues - 1].correctAns" class="col-md-12">
                <h2 class="anshead">Correct Answer</h2>
                <div class="col-md-12 ques_option">
                <table>											
                    <tr ng-repeat="(pIndex, subchildele) in renderQuestion[currentQues - 1].correctAns[parentIndex].option track by pIndex">
                        <td ng-hide="(field.title && !renderQuestion[currentQues - 1].questions[parentIndex].headercol && pIndex == 0) || (field.title && !renderQuestion[currentQues - 1].questions[parentIndex].headerrow && $index == 0)" ng-repeat="field in subchildele track by $index">
                            <span ng-if="!field.title && quesele.questype != 'input'">
                                <input type="checkbox" ng-hide="true" class="custom_[%quesele.questype%]" id="checkbox[%parentIndex%][%pIndex%][%$index%]" 
                                       name="checkbox[%parentIndex%][%pIndex%][%$index%]" ng-click="updateValues(quesele.questype, parentIndex, pIndex, $index)" ng-model="field.checkvalue"/>
                                <label style="float:left;" for="checkbox[%parentIndex%][%pIndex%][%$index%]" ng-click="updateValues(quesele.questype, parentIndex, pIndex, $index)"><span ng-click="updateValues(quesele.questype, parentIndex, pIndex, $index)"></span>
                                    <div style="font-weight:normal; padding-left:2px;" class="pull-right" ng-bind-html="field.value | to_trusted"></div>
                                </label>		
                            </span>
                            <span ng-if="!field.title && quesele.questype == 'input'">
                                <input ng-show="quesele.questype == 'input'" type="text" style="width: auto; font-weight:normal;" ng-model="field.checkvalue" />
                            </span>
                            <span ng-if="field.title">
                                <div style="font-weight:normal; padding-left:2px;" ng-bind-html="field.value | to_trusted"></div>
                            </span>
                        </td>
                        <td ng-show="false">
                            <a ng-click="removeRow(parentIndex, pIndex)" ng-hide="element.option.length < 4 || pIndex === 0">
                                <span class="glyphicon glyphicon-remove btn-cursor-pointer btn-font-18 pull-right" tooltip="Remove" style="padding-top: 2px;padding-right: 10px;"></span>
                            </a>
                        </td>
                    </tr>
                    <tr ng-show="false">
                        <td class="remove-tr-border" ng-repeat="field in questionJSON.questions[parentIndex].option[0]">
                            <a ng-click="removeColumn(parentIndex, $index)" ng-hide="element.option[0].length < 4" ng-if="$index != 0">
                                <span class="glyphicon glyphicon-remove btn-cursor-pointer btn-font-18 pull-right" tooltip="Remove" style="padding-top: 2px;padding-right: 10px;"></span>
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
            </div>
            <!--end -->
            
        </div>
    </div>
</div>