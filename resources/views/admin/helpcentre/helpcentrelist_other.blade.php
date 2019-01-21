@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/helpcentre.manage_helpcentre'), 'trait_1' => trans('admin/helpcentre.template_heading')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! trans('admin/helpcentre.template_heading') !!}
                </div>
            </div>
            <div class="portlet-body">
                {!! Form::open(array('url' => 'helpcentre','class'=>'form-horizontal','id' =>'search-form' )) !!}
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('category',trans('admin/helpcentre.category'),['class'=>'control-label col-md-3'])  !!}
                                <div class="col-md-9">
                                    {!! Form::select('category[]', $category, null, ['id'=>'category','multiple'=>'multiple','class'=>'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">&nbsp;</div>
                        <div class="col-md-3">
                            <div class="form-group" id="strands_div" style="display:none;">  
                                {!! Form::labelControl('strands_id',trans('admin/questionbuilder.strand'),[ 'class'=>'control-label col-md-3'])  !!}
                                <div class="col-md-9">
                                    {!! Form::select('strands_id[]', array(), null, ['id'=>'strands_id','multiple'=>'multiple','class'=>'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="col-md-12">
                                    {!! Form::button(trans('admin/admin.search'), array('type' => 'submit', 'class' => 'btn btn-primary')) !!} 
                                    {!! Form::button(trans('admin/admin.reset'), array('type' => 'reset', 'id'=>'reset-helpcentre','class' => 'btn btn-primary grey')) !!} 
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr style="border-top: 1px solid #26a69a;" >
                </div>
                {!! Form::close() !!}  
                <div id="result_div" ></div>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop

@section('pagescripts')
{!! HTML::style('assets/global/plugins/jquery-ui/jquery-ui.min.css') !!}
{!! HTML::style('css/jquery.multiselect.css') !!}
{!! HTML::style('css/jquery.multiselect.filter.css') !!}
{!! HTML::script('js/jquery.multiselect.js') !!}
{!! HTML::script('js/jquery.multiselect.filter.js') !!}	 
<!-- BEGIN PAGE LEVEL PLUGINS -->
<style>
    div.checker input{opacity:1}
    .ui-multiselect-filter input {
        border: 1px solid #ddd;
        color: #333;
        font-size: 11px;
        height: auto;
    }
</style>
<script>

    jsMain = new Main("{{ trans('admin/admin.select_option') }}");
    var strands = <?php echo json_encode($strands) ?>;
    var selected_strands = '<?php
if (isset($helpCentre_list['strands_id'])) {
    echo $helpCentre_list['strands_id'];
} else {
    echo "";
}
?>';
    $(window).load(function() {
        serchResult();
        // jsMain.makeDropDownJsonData(strands, $("#strands_id"), $("#subject").val(), '');
    });
    $(document).ready(function() {
        $("#strands_div").hide();
        $("#helpcentre-table").hide();
        $("#substrands_div").hide();
        $("#category").multiselect();
        $("#category").change(function() {
            getAllStrands($(this).val(), 'add');
        });
        $("#strands_id").live("change", function() {
            getAllSubStrands($(this).val(), 'add');
        });

    });
    function showStrands() {
        $("#strands_id").multiselect();
        if (selected_strands != '')
            getAllSubStrands(selected_strands.split(','), 'edit');
    }
    function showSubStrands() {
        $("#sub_strands_id").multiselect('refresh');
    }

    var selected_sub_strands = '<?php
if (isset($helpCentre_list['substrands_id'])) {
    echo $helpCentre_list['substrands_id'];
} else {
    echo "";
}
?>';

    var selected_sub_strands = '<?php
if (isset($helpCentre_list['substrands_id'])) {
    echo $helpCentre_list['substrands_id'];
} else {
    echo "";
}
?>';
    function getAllStrands(obj, action) {
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
        }
    }
    function getAllSubStrands(obj, action) {
        var id = obj;
        $("#substrands_div").show();
        $.ajax({
            url: "/selectSubStrand",
            type: 'POST',
            data: {id: id, action: action, selected_strands: selected_sub_strands},
            success: function(data) {
                // setTimeout("aaa()","2000");
                $("#all_substrands").html('');
                $("#all_substrands").html(data);
                setTimeout("showSubStrands()", "2000")

                //window.location.reload();
            }

        });
    }

    $('#search-form').on('submit', function(e) {
        e.preventDefault();
        serchResult();
    });

    $("#reset-helpcentre").click(function() {
        setTimeout("$('#search-form').submit();", "400");
        $("#strands_div").hide();
        serchResult();
    });

    var vars = {
        dataTable: "#helpcentre-table",
        listUrl: "{{ route('helpcentre.listrecord') }}",
        addUrl: "{{ route('helpcentre.create') }}",
        deleteUrl: "{{ route('helpcentre.delete') }}",
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
        successMsg: "{!! trans('admin/admin.delete-success') !!}"
    };

    function serchResult() {
        $.ajax({
            url: "/helpcentreSearch",
            type: 'POST',
            data: {title: $('#title').val(), category: $('#category').val(), strands: $('#strands_id').val(), substrands: $('#sub_strands_id').val()},
            success: function(data) {
                $("#result_div").html(data);
            }
        });
    }


</script>

@stop