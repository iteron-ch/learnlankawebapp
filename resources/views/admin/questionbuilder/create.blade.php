@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/questionbuilder.manage_questionbuilder'), 'trait_1' => $trait['trait_1'], 'trait_1_link' => $trait['trait_1_link'], 'trait_2' => $trait['trait_2']])
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row" ng-app="questionBuilder" ng-controller="MainCtrl" ng-init="setDefaultValue({{ $questionBuilderData}}, {{ $question}})">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    {{ $page_title }}
                </div>
                <div class="actions">
                    <a href="javascript:void(0);" ng-click="refreshQuestionSection( MASTERS.keywords['submitaction1'], MASTERS.keywords['submitaction3'] )" class="btn btn-default btn-sm">
                        <i class="glyphicon glyphicon-chevron-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="portlet-body form" id="startquetionbuilder" style="display:none;">
                <!-- BEGIN FORM-->
                <form action="#" class="horizontal-form" name="qfrm" id="qfrm">
                    <div class="row">
                        <div class="col-xs-12" style="margin-top: 12px;">
                            <accordion close-others="false">
                                <accordion-group is-open="isCriteriaOpen" class="accordion-margin">
                                    <accordion-heading>
                                        <i class="pull-left glyphicon" ng-class="{'glyphicon-minus': isCriteriaOpen, 'glyphicon-plus': !isCriteriaOpen}"></i>&nbsp;Set Question Criteria
                                    </accordion-heading>
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <select ng-disabled="questionData.id" name="ks_id" class="form-control" ng-model="selected.keystage" ng-change="updateDropdownValues({type:'keystage'})" ng-options="k as v for (k,v) in MASTERS.keystage">
                                                        <option value=''>Select Key Stage</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group" ng-show="selected.keystage">
                                                    <select ng-disabled="questionData.id" name="yg_id" class="form-control" ng-model="selected.yeargroup" ng-change="updateDropdownValues({type:'yeargroup'})" ng-options="k as v for (k,v) in MASTERS.yeargroup[selected.keystage]">
                                                        <option value=''>Select Year Group</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group" ng-show="selected.yeargroup">
                                                    <select ng-disabled="questionData.id" name="subject" class="form-control" ng-change="updateDropdownValues({type:'subject'})" ng-model="selected.subject" ng-options="k as v for (k,v) in MASTERS.subject">
                                                        <option value=''>Select Subject</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group" ng-show="selected.subject">
                                                    <select ng-disabled="questionData.id" name="group" class="form-control" ng-model="selected.setgroup" ng-change="updateDropdownValues({type:'setgroup'})" ng-options="k as v for (k,v) in MASTERS.setgroup">
                                                        <option value=''>Set Group</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2"  ng-if="selected.setgroup && selected.setgroup != 'Revision'">
                                                <div class="form-group">
                                                    <select ng-disabled="questionData.id" name="qs_id" class="form-control" ng-model="selected.setname" ng-change="updateDropdownValues({type:'setname'})" ng-options="v.id as v.set_name for (k,v) in MASTERS.questionset | filter:{subject:selected.subject} | filter:{ks_id:selected.keystage} | filter:{year_group:selected.yeargroup}">
                                                        <option value="">Select Question Set</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2" ng-if="selected.setname && selected.setgroup != 'Revision'">
                                                <div class="form-group">
                                                    <select ng-disabled="questionData.id" name="paper_id" class="form-control" ng-model="selected.paperset" ng-change="updateDropdownValues({type:'paperset'})" ng-options="k as v.name for (k,v) in MASTERS.paperset[selected.subject]">
                                                        <option value=''>Select Paper</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <!--<h3 class="form-section" ng-show="selected.paperset">Set Question Type</h3>-->
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group" ng-show="selected.paperset || selected.setgroup == 'Revision'">
                                                    <select ng-disabled="questionData.id" name="difficulty_id" class="form-control" ng-change="updateDropdownValues({type:'difficulty'})" ng-model="selected.difficulty" ng-options="k as v for (k,v) in MASTERS.difficulty">
                                                        <option value=''>Select Difficulty</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group" ng-show="selected.difficulty">
                                                    <select ng-disabled="questionData.id" id="strand_id" name="strands_id" class="form-control" ng-change="updateDropdownValues({type:'strand'})" ng-model="selected.strand" ng-options="v.id as v.name for (k,v) in MASTERS.strand[selected.subject]">
                                                        <option value=''>Select Strand</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group" ng-show="selected.strand">
                                                    <select ng-disabled="questionData.id" id="substrand_id" name="substrands_id" class="form-control" ng-change="updateDropdownValues({type:'substrand'})"  ng-model="selected.substrand" ng-options="v.id as v.name for (k,v) in MASTERS.substrand[selected.strand]">
                                                        <option value=''>Select Sub Strand</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group"ng-show="selected.substrand" >
                                                    <select ng-disabled="questionData.id" id="question_type_id" name="question_type_id" class="form-control" ng-model="selected.questiontype" ng-change="changeView(selected.questiontype)" ng-options="v.id as v.name for (k,v) in MASTERS.tempquestiontype[selected.subject]">
                                                        <option value=''>Select Question Type</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 form-group">
                                                <input type="text" ng-maxlength="30" class=" form-control" ng-model="selected.quesnote" class="form-control" placeholder="Question Note" vld-max-length=500></input>
                                            </div>
                                        </div>
                                    </div>
                                </accordion-group>
                            </accordion>
                        </div>
                    </div>
                    <div class="row form-header-sub-create" ng-show="selected.keystage">
                            <div class="col-md-12">
                                <div class="col-md-8 margin-top-5">
                                    <span class="accordion-header-selected"  ng-show="selected.keystage" tooltip="Key Stage: [%MASTERS.keystage[selected.keystage]%]">[%MASTERS.keystage[selected.keystage]%]</span>
                                        <span class="accordion-header-selected"  ng-show="selected.yeargroup" tooltip="Year Group: [%MASTERS.yeargroup[selected.keystage][selected.yeargroup]%]">[%MASTERS.yeargroup[selected.keystage][selected.yeargroup]%]</span>
                                        <span class="accordion-header-selected"  ng-show="selected.subject" tooltip="Subject: [%MASTERS.subject[selected.subject]%]">[%MASTERS.subject[selected.subject]%]</span>
                                        <span class="accordion-header-selected"  ng-show="selected.setgroup" tooltip="Set Group: [%MASTERS.setgroup[selected.setgroup]%]">[%MASTERS.setgroup[selected.setgroup]%]</span>
                                        <span class="accordion-header-selected"  ng-show="selected.setname" tooltip="Set Name: [%returnSetName%]" ng-bind-html="bindSetName(MASTERS.questionset,selected.setname)"></span>
                                        <span class="accordion-header-selected"  ng-show="selected.paperset && selected.paperset != '0'" tooltip="Paper Set: [%MASTERS.paperset[selected.subject][selected.paperset].name%]">[%MASTERS.paperset[selected.subject][selected.paperset].name%]</span>
                                        <span class="accordion-header-selected"  ng-show="selected.difficulty" tooltip="Difficulty: [%MASTERS.difficulty[selected.difficulty]%]">[%MASTERS.difficulty[selected.difficulty]%]</span>
                                        <span class="accordion-header-selected"  ng-show="selected.strand" tooltip="Strand: [%returnStrand%]" ng-bind-html="bindStrand(MASTERS.strand[selected.subject],selected.strand)"></span>
                                        <span class="accordion-header-selected"  ng-show="selected.substrand" tooltip="Substrand: [%returnSubstrand%]" ng-bind-html="bindSubstrand(MASTERS.substrand[selected.strand],selected.substrand)"></span>
                                        <span class="accordion-header-selected"  ng-show="selected.questiontype" tooltip="Question Type: [%returnSelQuesType%]" ng-bind-html="bindSelectQuestion(selected.questiontype)"></span>
                                </div>
                                <div class="form-group col-md-4" style="margin-bottom:0px;" ng-show="selected.difficulty && selected.strand && selected.substrand">
                                    
                                    <div class="question-width-90 pull-right">
                                        <input name="qid_s2" type="text" ng-model="selected.dynamicId2" class="form-control">
                                    </div>
                                    
                                    <div class="question-width-90 pull-right">
                                        <input name="qid_s1" id="qid_s1" type="text" ng-model="selected.dynamicId1" class="form-control" readonly>
                                    </div>
                                    
                                    <div class="question-width-90 pull-right">
                                        <input ng-disabled="questionData.id" name="qid_f" type="text" ng-model="selected.populatedID" class="form-control" readonly>
                                    </div>
                                    
                                    <label class="control-label pull-right" style="margin-top:6px;">Question ID&nbsp;&nbsp;</label>                                    
                                    
                                </div>
                            </div>
                        </div>
                </form>
                <!-- END FORM-->
                <!-- View's container: Code start -->
                <div ng-view=""></div>
                <!-- View's container: Code end -->

                <!-- flash message: Code start -->
                <div flash-message="5000"></div>
                <!-- flash message: Code end -->

            </div>

        </div>
    </div>
    <!-- Confirm Popup section.: Code Start -->
    <script type="text/ng-template" id="confirmPopUp">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" ng-click="cancel()" >&times;</button>
            <h3 class="modal-title">Please Confirm!</h3>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to save this question?</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="button" ng-click="ok()">Save Question</button>
            <!--<button class="btn btn-warning" type="button" ng-click="cancel()">No</button>-->
        </div>
    </script>
    <!-- Confirm Popup section.: Code End -->
    <!-- Confirmation popup before back to list.: Code Start -->
    <script type="text/ng-template" id="confirmPopUpBack">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" ng-click="cancel()" >&times;</button>
            <h3 class="modal-title">Please Confirm!</h3>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to leave this page?</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="button" ng-click="ok()">Yes</button>
            <button class="btn btn-warning" type="button" ng-click="cancel()">No</button>
        </div>
    </script>
    <!-- Confirmation popup before back to list.: Code End -->
    
     <!-- Confirm Popup for question validate section.: Code Start -->
    <script type="text/ng-template" id="confirmvalidatorPopUp">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" ng-click="cancel()" >&times;</button>
            <h3 class="modal-title">Please Confirm!</h3>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to validate this question?</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="button" ng-click="ok()">Validate</button>
        </div>
    </script>
    <!-- Confirm Popup for question validate section.: Code End -->

</div>
<!-- END PAGE CONTENT-->
@stop
@section('pagescripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->
{!! HTML::style('bower_components/custom/css/main.css') !!}
{!! HTML::style('css/jquery-ui.min.css') !!}
{!! HTML::style('bower_components/dotsjoin/dotsJoin.css') !!}
{!! HTML::style('bower_components/Reflection/Reflection.css') !!}

{!! HTML::script('bower_components/angular/angular.js') !!}
{!! HTML::script('bower_components/angular-animate/angular-animate.js') !!}
{!! HTML::script('bower_components/angular-route/angular-route.js') !!}
{!! HTML::script('bower_components/ckeditor/ckeditor.js') !!}

{!! HTML::script('bower_components/angular-flash-alert/dist/angular-flash.js') !!}
{!! HTML::script('bower_components/angular-ui-bootstrap-bower/ui-bootstrap-tpls.js') !!}
{!! HTML::script('bower_components/raphael/raphael-min.js') !!}
<!--<script class="jsbin" src="http://cdn.jsdelivr.net/raphael/2.1.0/raphael-min.js"></script>-->
<!-- endbuild -->
<script src="//cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML&dummy=.js"></script>
{!! HTML::script('bower_components/fabric/fabric.js') !!}
{!! HTML::script('bower_components/dotsjoin/dotsJoin.js') !!}
{!! HTML::script('bower_components/Reflection/Reflection.js') !!}
{!! HTML::script('bower_components/easypie/easypie.js') !!}
{!! HTML::script('bower_components/easypie/angular.easypiechart.js') !!}
{!! HTML::script('bower_components/angular/angularjs-dropdown-multiselect.js') !!}


<!-- build:js({.tmp,app}) scripts/scripts.js -->

{!! HTML::script('bower_components/custom/js/main.js') !!}
{!! HTML::script('bower_components/custom/js/singlechoice.js') !!}
{!! HTML::script('bower_components/custom/js/multiplechoice.js') !!}
{!! HTML::script('bower_components/custom/js/fillintheblanks.js') !!}
{!! HTML::script('bower_components/custom/js/matchdragdrop.js') !!}
{!! HTML::script('bower_components/custom/js/measurement.js') !!}
{!! HTML::script('bower_components/custom/js/simplequestion.js') !!}
{!! HTML::script('bower_components/custom/js/selectliteracy.js') !!}
{!! HTML::script('bower_components/custom/js/labelliteracy.js') !!}
{!! HTML::script('bower_components/custom/js/insertliteracy.js') !!}
{!! HTML::script('bower_components/custom/js/wordonimage.js') !!}
{!! HTML::script('bower_components/custom/js/underlineliteracy.js') !!}
{!! HTML::script('bower_components/custom/js/numberwordselect.js') !!}
{!! HTML::script('bower_components/custom/js/singlemultipleentry.js') !!}
{!! HTML::script('bower_components/custom/js/dragdrop.js') !!}
{!! HTML::script('bower_components/custom/js/drawinggraph.js') !!}
{!! HTML::script('bower_components/custom/js/boolean.js') !!}
{!! HTML::script('bower_components/custom/js/spellingaudio.js') !!}
{!! HTML::script('bower_components/custom/js/drawlineonimage.js') !!}
{!! HTML::script('bower_components/custom/js/joiningdots.js') !!}
{!! HTML::script('bower_components/custom/js/reflection.js') !!}
{!! HTML::script('bower_components/custom/js/shadingshape.js') !!}
{!! HTML::script('bower_components/custom/js/measurementlineangle.js') !!}
{!! HTML::script('bower_components/custom/js/piechart.js') !!}
{!! HTML::script('bower_components/custom/js/symmetric.js') !!}
{!! HTML::script('bower_components/custom/js/inputonimage.js') !!}
{!! HTML::script('bower_components/custom/js/tableinputentry.js') !!}
<!-- END PAGE LEVEL SCRIPTS -->


<script>
    var question = '<?php echo $question; ?>';
    //console.log(question);
    $(document).ready(function () {
        var qAct = '<?php echo isset($qAct)?$qAct:''; ?>';
        if(qAct!='')
            getAutoQuestionId();
        
        $("#question_type_id").change(function(){
                getAutoQuestionId()
            });
        });
        function  getAutoQuestionId(){
                if($("#question_type_id").val()=='') {
                        return false;
                    }
                    var postData = $("#qfrm").serializeArray();
                    $.ajax({
                        url: '{{ route('questionbuilder.questionautonumber') }}',
                        method: 'POST',
                        data:postData,
                        cache: false,
                        beforeSend: function() {
                        },
                        success: function(response) { 
                            $('#qid_s1').val(response*1+1);
                            $('#qid_s1').trigger('input');
                        },
                        error: function(xhr, textStatus, errorThrown) {
                        },
                        complete: function() {
                        }
                    });
            
        }
</script>
@stop