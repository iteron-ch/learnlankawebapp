@extends('admin.layout._default')
@section('content')
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => $page_heading, 'trait_1' => $trait['trait_1'], 'trait_1_link' => $trait['trait_1_link'], 'trait_2' => $trait['trait_2']])
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN VALIDATION STATES-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    {{ $page_title }}
                </div>
                <div class="actions">
                    <a href="{{ route('helpcentre.index') }}" class="btn btn-default btn-sm">
                        <i class="glyphicon glyphicon-chevron-left"></i> {!! trans('admin/helpcentre.back') !!} </a>
                </div> 
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                @yield('form')
                <div class="form-body">
                    @include('admin.partials.form_error')
                    <div class="row">
                        <div class="form-group">
                            {!! Form::labelControl('title',trans('admin/helpcentre.title'),['class'=>'control-label col-md-2'], TRUE )  !!}
                            <div class="col-md-3">
                                {!! Form::text('title',null,['class'=>'form-control'])  !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::labelControl('category',trans('admin/helpcentre.category'),['class'=>'control-label col-md-2'], TRUE )  !!}
                            <div class="col-md-3">
                                {!! Form::select('category[]', $category, null, ['id'=>'category','multiple'=>'multiple','class'=>'form-control']) !!}
                                {!! Form::text('category_selected',isset($helpcentre['category']) ? 1 : '',['id' => 'category_selected'])  !!}
                            </div>

                        </div>

                        <div class="form-group" id="strands_div" style="display:none;">  
                            {!! Form::labelControl('strands_id',trans('admin/questionbuilder.strand'),[ 'class'=>'control-label col-md-2'])  !!}
                            <div class="col-md-3">
                                {!! Form::select('strands_id[]', array(), null, ['id'=>'strands_id','multiple'=>'multiple','class'=>'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group" id="substrands_div" style="display:none;">
                            {!! Form::labelControl('subStrands_id',trans('admin/helpcentre.substrand'),[ 'class'=>'control-label col-md-2'])  !!}
                            <div class="col-md-3">
                                {!! Form::select('sub_strands_id[]', array(), null, ['id'=>'sub_strands_id','multiple'=>'multiple','class'=>'form-control']) !!}
                            </div>
                        </div>


                        <div class="form-group">
                            {!! Form::labelControl('visible_to',trans('admin/helpcentre.visible_to'),['class'=>'control-label col-md-2'], TRUE )  !!}
                            <div class="col-md-3">
                                {!! Form::select('visible_to[]', $visible, null, ['id'=>'visible_to','multiple'=>'multiple','class'=>'form-control']) !!}
                                {!! Form::text('visible_to_selected',isset($helpcentre['visible_to']) ? 1 : '',['id' => 'visible_to_selected'])  !!}
                            </div>
                        </div>
                        <div id="file_upload_div"></div>
                        <div class="form-group"> 
                            <label for="document" class="control-label col-md-2">&nbsp;</label>
                            <div class="col-md-3">
                                <a href="javascript:void(0)" class="right" id="add_field_button">Add More Fields</a>
                            </div>
                        </div>
                        @if( isset($helpcentre['id']) && count($helpcentre['helpcentreFiles']) )
                        <div class="form-group">
                            {!! Form::labelControl('currentFiles',trans('admin/helpcentre.currentFiles'),['class'=>'control-label col-md-2'], TRUE )  !!}
                            <div class="col-md-6">
                                <div>
                                    <b>Check to delete any file</b>
                                </div><br>
                                <div class="input-group">
                                    <div class="icheck-inline">
                                        @foreach($helpcentre['helpcentreFiles'] as $keys=>$values)
                                        <label><input type="checkbox" name="toRemove[]" class="icheck" value="{{ $values['id'] }}"> 
                                            @if($values['media_type'] == 1)
                                            <a class="view_row" data-remote="{{ route('helpcentre.helpcentreview',$values['id']) }}"><i class="{{ $mediaTypeIcon[1] }}" style="font-size:20px; color: red;"></i></a>
                                            @elseif($values['media_type'] == 2)
                                            <a class="view_row" data-remote="{{ route('helpcentre.helpcentreview',$values['id']) }}"><i class="{{ $mediaTypeIcon[2] }}" style="font-size:20px;"></i></a>
                                            @elseif($values['media_type'] == 3)
                                            @if(getFileExtensionFromFilename($values['file_name']) == 'txt')
                                            <a target="_blank" href="{{ '/uploads/helpdocuments/'.$values['file_name'] }}"><i class="{{ $mediaTypeIcon[6] }}" style="font-size:20px; color: red;"></i></a>
                                            @elseif(getFileExtensionFromFilename($values['file_name']) == 'pdf')
                                            <a target="_blank" href="{{ '/uploads/helpdocuments/'.$values['file_name'] }}"><i class="{{ $mediaTypeIcon[7] }}" style="font-size:20px; color: red;"></i></a>
                                            @elseif(getFileExtensionFromFilename($values['file_name']) == 'ppt')
                                            <a target="_blank" href="{{ '/uploads/helpdocuments/'.$values['file_name'] }}"><i class="{{ $mediaTypeIcon[8] }}" style="font-size:20px; color: red;"></i></a>
                                            @elseif(getFileExtensionFromFilename($values['file_name']) == 'doc' || getFileExtensionFromFilename($values['file_name']) == 'docx')
                                            <a target="_blank" href="{{ '/uploads/helpdocuments/'.$values['file_name'] }}"><i class="{{ $mediaTypeIcon[9] }}" style="font-size:20px; color: red;"></i></a>
                                            @else
                                            <a target="_blank" href="{{ '/uploads/helpdocuments/'.$values['file_name'] }}"><i class="{{ $mediaTypeIcon[3] }}" style="font-size:20px; color: red;"></i></a>
                                            @endif
                                            @elseif($values['media_type'] == 4)
                                            <a target="_blank" href="{{ '/uploads/helpdocuments/'.$values['file_name'] }}"><i class="{{ $mediaTypeIcon[4] }}" style="font-size:20px;"></i></a>
                                            @elseif($values['media_type'] == 5)
                                            <a target="_blank" href="{{ $values['media_link'] }}"><i class="{{ $mediaTypeIcon[5] }}" style="font-size:20px;"></i></a>
                                            <br>
                                            @endif
                                        </label>
                                        @endforeach 

                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(isset($helpcentre['id']))
                        <div class="form-group">
                            {!! Form::labelControl('status',trans('admin/admin.status'),['class'=>'control-label col-md-2'],true)  !!}
                            <div class="col-md-2">
                                {!! Form::select('status', $status, null,['id' => 'status', 'class' => 'form-control select2me']) !!} 
                            </div>
                        </div> 
                        @endif
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                {!! Form::button('Submit', array('type' => 'submit', 'class' => 'btn green')) !!}
                                {!! HTML::link('/helpcentre', trans('admin/helpcentre.cancel'), array('class' => 'btn default')) !!}
                            </div>
                        </div>
                    </div>   
                    <!--	</form>-->
                    {!! Form::close() !!}
                    <!-- END FORM-->
                </div>
                <!-- END VALIDATION STATES-->

            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop
@section('pagescripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->
{!! HTML::style('assets/global/plugins/jquery-ui/jquery-ui.min.css') !!}
{!! HTML::style('css/jquery.multiselect.css') !!}
{!! HTML::style('css/jquery.multiselect.filter.css') !!}
{!! HTML::script('js/jquery.multiselect.js') !!}
{!! HTML::script('js/jquery.multiselect.filter.js') !!}	 
<!-- BEGIN PAGE LEVEL PLUGINS -->
{!! JsValidator::formRequest($JsValidator, '#helpcentrefrm'); !!}
<!-- END PAGE LEVEL SCRIPTS -->
<style>
    div.checker input{opacity:1}
    .ui-multiselect-filter input {
        border: 1px solid #ddd;
        color: #333;
        font-size: 11px;
        height: auto;
    }
    #category_selected,#visible_to_selected{
        visibility: hidden!Important;
        height:1px;
        width:1px;
        padding: 0;
    }
    #visible_to_selected-error,#category_selected-error{margin-top: -12px;}
</style>
<script>
    jsMain = new Main("{{ trans('admin/admin.select_option') }}");
    var categorySelected = '<?php echo isset($helpcentre['category_str']) ? $helpcentre['category_str'] : '' ?>';
    var selected_strands = '<?php echo isset($helpcentre['strands_id']) ? $helpcentre['strands_id'] : '' ?>';
    var selected_sub_strands = '<?php echo isset($helpcentre['substrands_id']) ? $helpcentre['substrands_id'] : '' ?>';
    $(document).ready(function() {
        $("[data-toggle=tooltip]").tooltip({placement: 'right'});
        $("#visible_to").multiselect({
            click: function(e) {
                jsMain.multiSelectValidationSlave($(this), $("#visible_to_selected"), $("#helpcentrefrm"));
            },
            checkAll: function() {
                jsMain.multiSelectValidationSlave($(this), $("#visible_to_selected"), $("#helpcentrefrm"));
            },
            uncheckAll: function() {
                jsMain.multiSelectValidationSlave($(this), $("#visible_to_selected"), $("#helpcentrefrm"));
            },
        });
        $("#category").multiselect({
            click: function(e) {
                jsMain.multiSelectValidationSlave($(this), $("#category_selected"), $("#helpcentrefrm"));
            },
            checkAll: function() {
                jsMain.multiSelectValidationSlave($(this), $("#category_selected"), $("#helpcentrefrm"));
            },
            uncheckAll: function() {
                jsMain.multiSelectValidationSlave($(this), $("#category_selected"), $("#helpcentrefrm"));
            },
        });
        if (categorySelected != '' && selected_strands) {
            getAllStrands(categorySelected.split(','), 'edit', selected_strands);
        }
        if (categorySelected != '' && selected_strands && selected_sub_strands) {
            getAllSubStrands(selected_strands.split(','), 'edit', selected_sub_strands);
        }
        mediaFormPart(0);
        $("#category").change(function() {
            getAllStrands($(this).val(), 'add', '');
        });
        $("#strands_id").on("change", function() {
            getAllSubStrands($(this).val(), 'add', '');
        });
        $("#add_field_button").click(function(e) {
            e.preventDefault();
            var chnum = $("#file_upload_div").children().length;
            mediaFormPart(chnum);
        });
        $("#file_upload_div").on('click', '.delete_field', function(e) {
            e.preventDefault();
            var num = $(this).data('num');
            $("#media_type_container-" + num).remove();
        });
        $("#file_upload_div").on('change', '.media_type', function() {
            var value = $(this).val();
            var num = $(this).data('num');
            var media_text_container = $("#media_text_container-" + num);
            var media_file_container = $("#media_file_container-" + num);
            media_text_container.hide();
            media_file_container.hide();
            if (value == 1 || value == 5) {
                media_text_container.show();
            } else if (value == 2 || value == 3 || value == 4) {
                media_file_container.show();
            }
        });
        $('.view_row').on('click', function(e) {
            e.preventDefault();
            var eleObj = $(this);
            jsMain.showModelIframe(eleObj);
        });
    });

    function getAllStrands(obj, action, selected_strands) {
        var id = obj;
        if ($.inArray('9', id) > -1 || $.inArray('10', id) > -1) {
            $.ajax({
                url: "/selectStrand",
                type: 'POST',
                data: {id: id, action: action, selected_strands: selected_strands},
                success: function(data) {
                    $("#strands_div").show();
                    $("#strands_id").html(data).multiselect({
                        autoOpen: false,
                        noneSelectedText: "Select",
                        open: function()
                        {
                            $("input[type='search']:first").focus();
                        }
                    }).multiselectfilter();
                    $("#strands_id").multiselect('refresh');
                }
            });
        }
        else {
            $("#strands_div").hide();
            $("#substrands_div").hide();
        }
    }

    function getAllSubStrands(obj, action, selected_sub_strands) {
        var id = obj;
        $("#substrands_div").hide();
        $.ajax({
            url: "/selectSubStrand",
            type: 'POST',
            data: {id: id, action: action, selected_strands: selected_sub_strands},
            success: function(data) {
                if (data != '') {
                    $("#substrands_div").show();
                }
                $("#sub_strands_id").html(data).multiselect({
                    autoOpen: false,
                    noneSelectedText: "Select",
                    open: function()
                    {
                        $("input[type='search']:first").focus();
                    }
                }).multiselectfilter();
                $("#sub_strands_id").multiselect('refresh');
            }
        });
    }

    function mediaFormPart(i) {
        var htm = '';
        var isDelete = i == 0 ? 'none' : 'block';
        htm += '<div class="form-group" id="media_type_container-' + i + '">';
        htm += '<label for="media_type" class="control-label col-md-2">Select Media File</label>';
        htm += '<div class="col-md-3">';
        htm += '<select data-num="' + i + '" class="form-control media_type" name="media_type[]"><option value="" selected="selected">Select</option><option value="1">Video (Youtube/vimeo) [Dimension: 340*200] </option><option value="2">Video (Upload)</option><option value="3">Document (pdf / doc / ppt / .notebook)</option><option value="4">image (jpg/png/gif)</option><option value="5">Website URL</option></select>';
        htm += '</div>';
        htm += '<div class="col-md-3" id="media_file_container-' + i + '" style="display:none;">';
        htm += '{!! Form::file("media_file[]",null, ["class" => "form-control"]) !!}';
        htm += '</div>';
        htm += '<div class="col-md-3" id="media_text_container-' + i + '" style="display:none;">';
        htm += '{!! Form::text("media_link[]",null,["class"=>"form-control"])  !!}';
        htm += '</div>';
        htm += '<div class="col-md-1"><a href="#" data-num="' + i + '" class="delete_field" style="display:' + isDelete + ';"><i class="glyphicon glyphicon-remove font-red"></i></a></div>';
        htm += '</div>';
        $("#file_upload_div").append(htm);
    }
</script>
@stop
