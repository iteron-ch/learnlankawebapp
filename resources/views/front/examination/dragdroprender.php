<div class="questionrender" ng-controller="dragdropRenderCtrl">
    <div ng-repeat="(parentIndex, quesele) in renderQuestion[currentQues - 1].questions  track by $index">
        <div class="col-md-12 ques_area" id="setheight[%parentIndex%]">
            <div class="col-md-12 qDetail margin-bottom-20">
                <label class="pull-left"><span>[%parentIndex+1 | character%]</span></label>
                <div id="[%parentIndex%]" class="col-md-11 margin-top-5" ng-bind-html="quesele.ques | to_trusted"></div>
                <div class="marks_box">
                    <div class="mark_div">[%quesele.mark%]</div>
                    <div ng-show="quesele.mark <= 1">mark</div>
                    <div ng-show="quesele.mark > 1">marks</div>
                </div>
            </div>

            <div class="col-xs-12" ng-if="quesele.option.view === 'vertical' && !quesele.option.dragdrop">
                <div class="col-xs-12">
                    <div class="col-xs-6 col-xs-push-3 text-center">
                        <h4 ng-show="quesele.option.header.prefix !== ''"><b>[%quesele.option.header.prefix%]</b></h4>
                    </div>
                </div>
                <div class="col-xs-12"  >
                    <div class="col-xs-6 col-xs-push-3" name="sortable[%parentIndex%]" ng-class="{ 'row': quesele.option.dragdrop,'sortable row' : quesele.option.dragdrop === false}" >
                        <div class="sorting-answer custom-drag-ele" name="[%$index%]" ng-repeat="ele in quesele.option.optionvalue">	
                            <span ng-class="{'': quesele.option.dragdrop,'glyphicon glyphicon-move btn-cursor-pointer btn-font-18' : quesele.option.dragdrop === false}"></span>	
                            <!--<span style="display: inline-block;" ng-bind-html="ele.value | to_trusted"></span>-->
                            <span style="display: inline-block;" ng-bind-html="bindDropObjectSortable(parentIndex, $index) | to_trusted"></span>	
                        </div>	  
                    </div>
                </div>                
                <div class="col-xs-12" >
                    <div class="col-xs-6 col-xs-push-3 text-center">
                        <h4 ng-show="quesele.option.header.postfix !== ''"><b>[%quesele.option.header.postfix%]</b></h4>
                    </div>
                </div>
            </div> 

            <div class="col-xs-12" ng-if="quesele.option.view === 'vertical' && quesele.option.dragdrop">

                <div class="col-xs-5">
                    <div class="col-xs-12 text-center" style="margin-bottom: 5px; padding-top: 8px; min-height: 45px; padding-left: 5px;"></div>
                    <div class="col-xs-12">
                        <!--<div class="sorting-answer custom-drag-ele"  ng-repeat="ele in quesele.option.optionvalue">	
                            <span name="draggable[%parentIndex%]" style="display: inline-block;" class="draggable" ng-bind-html="ele.value | to_trusted"></span>	
                        </div>	  -->
                        <div class="custom-drag-ele" id="parent_[%parentIndex%]_[%$index%]" style="margin-bottom:5px;" ng-repeat="ele in quesele.option.optionvalue"  my-repeat-directive2>	
                            <span name="draggable[%parentIndex%]" id="drag_[%parentIndex%]_[%$index%]" style="display:inline-block; width:100%; cursor:crosshair;margin-bottom:0px;" class="sorting-answer draggable" ng-bind-html="ele.value | to_trusted"></span>	
                        </div>
                    </div>
                    <div class="col-xs-12 text-center" style="margin-bottom: 5px; padding-top: 8px; min-height: 45px; padding-left: 5px;"></div>
                </div>


                <div class="col-xs-5">
                    <div class="col-xs-12 text-center" style="margin-bottom: 5px; padding-top: 8px; min-height: 45px; padding-left: 5px;">
                        <h4 ng-show="quesele.option.header.prefix !== ''"><b>[%quesele.option.header.prefix%]</b></h4>
                    </div>
                    <div class="col-xs-12" id="dropContainer[%parentIndex%]">
                        <div name="droppable[%parentIndex%]" ng-class="getActiveClass(parentIndex, $index)" id="drop_[%parentIndex%]_[%$index%]" class="sorting-answer droppable" ng-repeat="ele in quesele.option.optionvalue">
                            <span name="[%parentIndex%]" ng-bind-html="bindDropObject(parentIndex, $index) | to_trusted"></span>	
                        </div>
                    </div>
                    <div class="col-xs-12 text-center" style="margin-bottom: 5px; padding-top: 8px; min-height: 45px; padding-left: 5px;">
                        <h4 ng-show="quesele.option.header.postfix !== ''"><b>[%quesele.option.header.postfix%]</b></h4>
                    </div>
                </div>  
            </div>

            <div class="col-xs-12" ng-if="quesele.option.view === 'horizontal' && !quesele.option.dragdrop">
                <div class="col-xs-2 heading_drag">
                    <h4 ng-show="quesele.option.header.prefix !== ''"><b>[%quesele.option.header.prefix%]</b></h4>
                </div>
                <div class="col-xs-8" name="sortable[%parentIndex%]" ng-class="{ '': quesele.option.dragdrop,'sortable row' : quesele.option.dragdrop === false}">
                    <div class="sorting-answer custom-drag-ele col-sm-2" style="width: 12.666667%;margin-right:5px;"  name="[%$index%]" ng-repeat="ele in quesele.option.optionvalue">	
                        <span ng-class="{'': quesele.option.dragdrop,'glyphicon glyphicon-move btn-cursor-pointer btn-font-18' : quesele.option.dragdrop === false,}"></span>
                        <!--<span style="display: inline-block;" ng-bind-html="ele.value | to_trusted"></span>-->
                        <span style="display: inline-block;" ng-bind-html="bindDropObjectSortable(parentIndex, $index) | to_trusted"></span>
                    </div>					
                </div>
                <div class="col-xs-2 heading_drag">
                    <h4 ng-show="quesele.option.header.postfix !== ''"><b>[%quesele.option.header.postfix%]</b></h4>
                </div>
            </div>


            <div class="col-xs-12" ng-if="quesele.option.view === 'horizontal' && quesele.option.dragdrop">
                <div class="col-xs-12">
                    <div class="col-xs-2 text-center">
                        <h4></h4>
                    </div>
                    <div class="col-xs-8">
                        <div class="col-xs-12">
                            <div class="col-xs-2 overwrite-padding" id="horizontal_[%parentIndex%]_[%$index%]" ng-repeat="ele in quesele.option.optionvalue" my-repeat-directive2>
                                <!--<div class="sorting-answer custom-drag-ele">	
                                    <span name="draggable[%parentIndex%]" style="display: inline-block;" ng-bind-html="ele.value | to_trusted" class="draggable"></span>
                                </div>-->
                                <div class="custom-drag-ele" style="margin-bottom:5px;">	
                                    <span name="draggable[%parentIndex%]" id="drag_[%parentIndex%]_[%$index%]"  style="display:inline-block; width:100%; cursor:crosshair;margin-bottom:0px;" ng-bind-html="ele.value | to_trusted" class="sorting-answer draggable"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-2 text-center">
                        <h4></h4>
                    </div>
                </div>
                <div class="col-xs-12">		
                    <div class="col-xs-2 text-center">
                        <h4 ng-show="quesele.option.header.prefix !== ''"><b>[%quesele.option.header.prefix%]</b></h4>
                    </div>
                    <div class="col-xs-8" id="dropContainer[%parentIndex%]">
                        <div class="col-xs-12">
                            <div class="col-xs-2 overwrite-padding" ng-repeat="ele in quesele.option.optionvalue">
                                <div name="droppable[%parentIndex%]" ng-class="getActiveClass(parentIndex, $index)" id="drop_[%parentIndex%]_[%$index%]" class="sorting-answer droppable">	
                                    <span name="[%parentIndex%]" style="display: inline-block;" ng-bind-html="bindDropObject(parentIndex, $index) | to_trusted" ></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-2 text-center">
                        <h4 ng-show="quesele.option.header.postfix !== ''"><b>[%quesele.option.header.postfix%]</b></h4>
                    </div>
                </div>
            </div>
            <!-- render correct answer -->
            <div ng-if="renderQuestion[currentQues-1].correctAns">
                <h2 class="anshead">Correct Answer</h2>
                
                <div class="col-xs-12" ng-if="quesele.option.view === 'vertical' && !quesele.option.dragdrop">
                    <div class="col-xs-12">
                        <div class="col-xs-6 col-xs-push-3 text-center">
                            <h4 ng-show="quesele.option.header.prefix !== ''"><b>[%quesele.option.header.prefix%]</b></h4>
                        </div>
                    </div>
                    <div class="col-xs-12"  >
                        <div class="col-xs-6 col-xs-push-3" name="sortable[%parentIndex%]" ng-class="{ 'row': quesele.option.dragdrop,'sortable row' : quesele.option.dragdrop === false}" >
                            <div class="sorting-answer custom-drag-ele" name="[%$index%]" ng-repeat="ele in quesele.option.optionvalue">	
                                <span ng-class="{'': quesele.option.dragdrop,'glyphicon glyphicon-move btn-cursor-pointer btn-font-18' : quesele.option.dragdrop === false}"></span>	
                                <!--<span style="display: inline-block;" ng-bind-html="ele.value | to_trusted"></span>-->
                                <span style="display: inline-block;" ng-bind-html="bindDropObjectSortable(parentIndex, $index,true) | to_trusted"></span>	
                            </div>	  
                        </div>
                    </div>                
                    <div class="col-xs-12" >
                        <div class="col-xs-6 col-xs-push-3 text-center">
                            <h4 ng-show="quesele.option.header.postfix !== ''"><b>[%quesele.option.header.postfix%]</b></h4>
                        </div>
                    </div>
                </div> 

                <div class="col-xs-12" ng-if="quesele.option.view === 'vertical' && quesele.option.dragdrop">
                    <div class="col-xs-5">
                        <div class="col-xs-12 text-center" style="margin-bottom: 5px; padding-top: 8px; min-height: 45px; padding-left: 5px;">
                            <h4 ng-show="quesele.option.header.prefix !== ''"><b>[%quesele.option.header.prefix%]</b></h4>
                        </div>
                        <div class="col-xs-12" id="dropContainer[%parentIndex%]">
                            <div name="droppable[%parentIndex%]" ng-class="getActiveClass(parentIndex, $index, true)" id="drop_[%parentIndex%]_[%$index%]" class="sorting-answer droppable" ng-repeat="ele in quesele.option.optionvalue">
                                <span name="[%parentIndex%]" ng-bind-html="bindDropObject(parentIndex, $index, true) | to_trusted"></span>	
                            </div>
                        </div>
                        <div class="col-xs-12 text-center" style="margin-bottom: 5px; padding-top: 8px; min-height: 45px; padding-left: 5px;">
                            <h4 ng-show="quesele.option.header.postfix !== ''"><b>[%quesele.option.header.postfix%]</b></h4>
                        </div>
                    </div>  
                </div>

                <div class="col-xs-12" ng-if="quesele.option.view === 'horizontal' && !quesele.option.dragdrop">
                    <div class="col-xs-2 heading_drag">
                        <h4 ng-show="quesele.option.header.prefix !== ''"><b>[%quesele.option.header.prefix%]</b></h4>
                    </div>
                    <div class="col-xs-8" name="sortable[%parentIndex%]" ng-class="{ '': quesele.option.dragdrop,'sortable row' : quesele.option.dragdrop === false}">
                        <div class="sorting-answer custom-drag-ele col-sm-2" style="width: 12.666667%;margin-right:5px;"  name="[%$index%]" ng-repeat="ele in quesele.option.optionvalue">	
                            <span ng-class="{'': quesele.option.dragdrop,'glyphicon glyphicon-move btn-cursor-pointer btn-font-18' : quesele.option.dragdrop === false,}"></span>
                            <!--<span style="display: inline-block;" ng-bind-html="ele.value | to_trusted"></span>-->
                            <span style="display: inline-block;" ng-bind-html="bindDropObjectSortable(parentIndex, $index, true) | to_trusted"></span>
                        </div>					
                    </div>
                    <div class="col-xs-2 heading_drag">
                        <h4 ng-show="quesele.option.header.postfix !== ''"><b>[%quesele.option.header.postfix%]</b></h4>
                    </div>
                </div>

                <div class="col-xs-12" ng-if="quesele.option.view === 'horizontal' && quesele.option.dragdrop">
                    
                    <div class="col-xs-12">		
                        <div class="col-xs-2 text-center">
                            <h4 ng-show="quesele.option.header.prefix !== ''"><b>[%quesele.option.header.prefix%]</b></h4>
                        </div>
                        <div class="col-xs-8" id="dropContainer[%parentIndex%]">
                            <div class="col-xs-12">
                                <div class="col-xs-2 overwrite-padding" ng-repeat="ele in quesele.option.optionvalue">
                                    <div name="droppable[%parentIndex%]" ng-class="getActiveClass(parentIndex, $index, true)" id="drop_[%parentIndex%]_[%$index%]" class="sorting-answer droppable">	
                                        <span name="[%parentIndex%]" style="display: inline-block;" ng-bind-html="bindDropObject(parentIndex, $index,true) | to_trusted" ></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-2 text-center">
                            <h4 ng-show="quesele.option.header.postfix !== ''"><b>[%quesele.option.header.postfix%]</b></h4>
                        </div>
                    </div>
                </div>
            </div>
            <!--end -->
        </div>
    </div>
</div>
