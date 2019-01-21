@extends('admin.layout._default')

@section('content')

<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => $page_heading, 'trait_1' => $trait['trait_1'], 'trait_1_link' => $trait['trait_1_link'], 'trait_2' => $trait['trait_2']])
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row" ng-app="awardCreate" ng-controller="awardCtrl" ng-init="setAwardValues({{ $studentaward }}, {{ $studentawardData }})">
     <div class="col-md-12">
        <!-- BEGIN VALIDATION STATES-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">{{ $page_title }}</div>
                <div class="actions">
                    <a href="{{ route('studentaward.index') }}" class="btn btn-default btn-sm">
                        <i class="glyphicon glyphicon-chevron-left"></i> {!! trans('admin/admin.back') !!} 
                    </a>
                </div> 
            </div>
            <div class="portlet-body form" id="awardrendering" style="display:none;">
                <!-- BEGIN FORM-->
                
                <div class="row">
                    <div class="col-xs-12" style="margin-top: 12px;">
                        <div class="form-body">
                            <div class="form-group col-xs-12">
                                <label class="control-label col-xs-2 text-right">Title<span class="required">* </span></label>
                                <div class="col-xs-10">
                                    <input ng-model="awardJson.title" class="form-control" name="title" type="text">
                                </div>
                            </div>
                            <div class="form-group col-xs-12">
                                <label class="control-label col-xs-2 text-right">Upload Image<span class="required">* </span></label>
                                <div class="col-xs-10">
                                    <div class="fileinput"  ng-class="awardJson.id ? 'fileinput-exists' : 'fileinput-new'" data-provides="fileinput" style="width: 200px; height: 150px;">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;" ng-bind-html="autoimageBind()"></div>
                                        <div>
                                            <span class="btn default btn-file">
                                                <span class="fileinput-new" ng-disabled="addQuesBtnState">
                                                    Select image </span>
                                                <span class="fileinput-exists" ng-disabled="addQuesBtnState">
                                                    Change </span>
                                                <input type="file" class="question_image" onchange="angular.element(this).scope().bindClick(this)" name="...">
                                            </span>
                                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput" >Remove</a>
                                        </div>
                                    </div>   
                                </div>
                            </div>
                            <div class="form-group col-xs-12">
                                <label class="control-label col-xs-2 text-right">Set Position</label>
                                <div class="col-xs-10">
                                    <table>
                                        <tr>
                                            <th>Field</th><th>Visible</th><th>Font</th><th>Size</th><!--font-Size--><th>XPos</th><th>YPos</th><th></th>
                                          </tr>
                                          <tr ng-repeat=" element in awardJson.position track by $index">
                                              <td><b>[%element.label%]</b></td>
                                            <td>
                                                <input ng-model="awardJson.position[$index].visible" class="form-control" name="visible" type="checkbox">
                                            </td>
                                            <td>
                                                <select ng-disabled="!awardJson.position[$index].visible" class="form-control" ng-model="awardJson.position[$index].font" ng-options="k as v for (k,v) in contentSetting.font"></select>
                                            </td>
                                            <td>
                                                <select ng-disabled="!awardJson.position[$index].visible" class="form-control" ng-model="awardJson.position[$index].size" ng-options="size as size for size in contentSetting.size"></select>
                                            </td>
                                            <td>
                                                <input ng-disabled="!awardJson.position[$index].visible" ng-model="awardJson.position[$index].xPos" class="form-control" name="xPos" type="text">
                                            </td>
                                            <td>
                                                <input ng-disabled="!awardJson.position[$index].visible" ng-model="awardJson.position[$index].yPos" class="form-control" name="yPos" type="text">
                                            </td>
                                            <td>
                                                <button ng-disabled="!awardJson.position[$index].visible || !awardJson.image" type="button" class="btn btn-default btn-sm pull-right view-btn btn-cursor-pointer" ng-click="setPosition( $index );">Set Position</button>				
                                            </td>
                                          </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group col-xs-12">
                                <label class="control-label col-xs-2 text-right">Content<span class="required">* </span></label>
                                <div class="col-xs-10">
                                   <textarea ng-model="awardJson.content" style="resize:none;" class="form-control" rows="6" name="content" cols="50"></textarea>
                                </div>
                            </div>
                            <div class="form-group col-xs-12">
                                <label class="control-label col-xs-2 text-right">Status</label>
                                <div class="col-xs-10">
                                   <select class="form-control" ng-model="awardJson.status">
                                        <option value='Active'>Active</option>
                                        <option value='Inactive'>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END FORM-->
                <div class="form-actions">
                    <div class="row">
                        <div class="col-xs-offset-2 col-xs-10">
                            <button type="submit" ng-click="submitAwardData();" class="btn green">Submit</button>
                            <a href="{{  action('StudentAwardsController@index')   }}" class="btn default">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END VALIDATION STATES-->
        </div>
    </div>
   <!-- Confirm Popup section.: Code Start -->
    <script type="text/ng-template" id="awardSetPopup">
        <div class="modal-header">
            <h3 class="modal-title">Set Position using drag element!</h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-xs-12 margin-bottom-20">
                    <div id="quesDrag[%positionIndex%]" style="width:[%containerwidth%]px;height:[%containerheight%]px;">
                        <div ng-bind-html="imageBind()"></div>
                        <span  class="draggable" id="option[%positionIndex%]" style="z-index:1; position:absolute; top:[%awardJson.position[positionIndex].yPos%]px; left:[%awardJson.position[positionIndex].xPos%]px; font-family:[%awardJson.position[positionIndex].font%]; font-size:[%awardJson.position[positionIndex].size%]px; padding:5px; cursor:crosshair;background-color: #7DB319;">
                               #[%awardJson.position[positionIndex].label%]
                        </span>
                    </div>   
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="button" ng-click="done()">Save</button>
        </div>
    </script>
    <!-- Confirm Popup section.: Code End -->
</div>
<!-- END PAGE CONTENT-->
@stop

@section('pagescripts') 
</div>
<!-- END PAGE CONTENT-->

<!-- BEGIN PAGE LEVEL PLUGINS -->
{!! HTML::style('bower_components/custom/css/main.css') !!}
{!! HTML::style('css/jquery-ui.min.css') !!}
{!! HTML::script('bower_components/angular/angular.js') !!}
{!! HTML::script('bower_components/angular-animate/angular-animate.js') !!}
{!! HTML::script('bower_components/angular-route/angular-route.js') !!}
{!! HTML::script('bower_components/angular-flash-alert/dist/angular-flash.js') !!}
{!! HTML::script('bower_components/angular-ui-bootstrap-bower/ui-bootstrap-tpls.js') !!}
{!! HTML::script('bower_components/custom/js/award.js') !!}
@stop