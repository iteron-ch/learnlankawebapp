@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => $page_heading, 'trait_1' => trans('admin/strand.strands')])

<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">
            {{ $page_title }}
        </div>
        <div class="actions">
            <a href="{{ route('strand.index') }}" class="btn btn-default btn-sm">
                <i class="fa fa-plus"></i> {!! trans('admin/strand.add_strand') !!} </a>

        </div>  
    </div>
    <div class="portlet-body form">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet blue-hoki box">
                    <div class="portlet-body">
                        <div id="tree_1" class="tree-demo" style="display:none">
                            <ul>
                                <?php 
                                foreach ($strand as $stKay => $stVal) {
                                    echo "<li data-jstree='{ \"opened\" : false }'>";
                                    echo $stKay;
                                    echo '<ul>';
                                    foreach ($strand[$stKay] as $key => $val) {
                                        if (count($val['children']) > 0) {
                                            echo "<li data-jstree='{ \"opened\" : false }'>";
                                        } else {
                                            echo "<li data-jstree='{ \"disabled\" : true }'>";
                                        }
                                        echo $val['strand'].' ('.$val['reference_code'].')';
                                        if (count($val['children']) > 0) {
                                            foreach ($val['children'] as $key2 => $val2) {
                                                echo '<ul>';
                                                echo "<li data-jstree='{ \"disabled\" : true }'>";
                                                echo $val2['strand'].' ('.$val2['reference_code'].')';
                                                echo '<span><a href="javascript:void(0);" alt="' . trans('admin/admin.edit') . '" title="' . trans('admin/admin.edit') . '" onClick="editStrand(\'' . route('strand.edit', encryptParam($val2['id'])) . '\')"><i class="glyphicon glyphicon-edit"></i></a></span>';
                                               // echo '<span><a alt="' . trans("admin/admin.delete") . '" title="' . trans("admin/admin.delete") . '" href="javascript:void(0);" data-id="' . encryptParam($val2['id']) . '" class="delete_row"><i class="glyphicon glyphicon-trash font-red"></i></a></span>';
                                                echo '</li>';
                                                echo '</ul>';
                                            }
                                        }
                                        echo '<span><a href="javascript:void(0);" alt="' . trans('admin/admin.edit') . '" title="' . trans('admin/admin.edit') . '" onClick="editStrand(\'' . route('strand.edit', encryptParam($val['id'])) . '\')" ><i class="glyphicon glyphicon-edit"></i></a></span>';
                                        //echo '<span><a alt="' . trans("admin/admin.delete") . '" title="' . trans("admin/admin.delete") . '" href="javascript:void(0);" data-id="' . encryptParam($val['id']) . '" class="delete_row"><i class="glyphicon glyphicon-trash font-red"></i></a></span>';
                                        echo '</li>';
                                    }
                                    echo '</ul>';
                                    echo '</li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END VALIDATION STATES-->
    </div><!-- END PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN VALIDATION STATES-->
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption">
                        {{ $from_title }}
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    @yield('form')
                    <div class="form-body">
                        @include('admin.partials.form_error')
                        <div class="form-group">
                            {!! Form::labelControl('subject',trans('admin/strand.subject'),['class'=>'control-label col-md-3'], TRUE )  !!}
                            <div class="col-md-4">
                                {!! Form::select('subject', $subject ,null, ['id' => 'subject', 'class' => 'form-control select2me']) !!} 
                            </div>
                        </div> 
                        <div class="form-group">
                            <label class="control-label col-md-3">&nbsp;</label>
                            <div class="col-md-4">
                                {!! Form::checkbox('is_substrand', 1, null, ['id' => 'is_substrand', 'class' => 'form-control']) !!}
                                Is Substrand
                            </div>
                        </div> 
                        <div class="form-group" id="sub_st_div" style="display: {{ isset($strandResult['is_substrand']) && $strandResult['is_substrand'] ? 'show': 'none'}};">
                            {!! Form::labelControl('parent_id',trans('admin/strand.parent_strand'),['class'=>'control-label col-md-3'], TRUE )  !!}
                            <div class="col-md-4">
                                {!! Form::select('parent_id', array() ,null, ['id' => 'parent_id', 'class' => 'form-control select2me']) !!} 
                            </div>
                        </div>                     
                        <div class="form-group">
                            {!! Form::labelControl('strand',trans('admin/strand.strand_name'),['class'=>'control-label col-md-3'], TRUE )  !!}
                            <div class="col-md-4">
                                {!! Form::text('strand',null,['class'=>'form-control']  )  !!}
                            </div>
                        </div>                     
                        <div class="form-group">
                            {!! Form::labelControl('reference_code',trans('admin/strand.code'),['class'=>'control-label col-md-3'], TRUE )  !!}
                            <div class="col-md-4">
                                {!! Form::text('reference_code',null,['class'=>'form-control']  )  !!}
                            </div>
                        </div>  
                        <div class="form-group">
                            {!! Form::labelControl('alias_sub_strand',trans('admin/strand.display_name'),['class'=>'control-label col-md-3'], TRUE )  !!}
                            <div class="col-md-4">
                                {!! Form::text('alias_sub_strand',null,['class'=>'form-control']  )  !!}
                            </div>
                        </div>  
                        <div class="form-group">
                            {!! Form::labelControl('appendices',trans('admin/strand.description'),['class'=>'control-label col-md-3'])  !!}
                            <div class="col-md-4">
                                {!! Form::textarea('appendices',null,['class'=>'form-control']  )  !!}
                            </div>
                        </div> 

                        @if(isset($strandResult['id']))
                        <div class="form-group">
                            {!! Form::labelControl('status',trans('admin/admin.status'),['class'=>'control-label col-md-3'], TRUE )  !!}
                            <div class="col-md-4">
                                {!! Form::select('status', $status, null ,['id' => 'status', 'class' => 'form-control select2me']) !!} 
                            </div>
                        </div> 
                        @endif

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    {!! Form::button('Submit', array('type' => 'submit', 'class' => 'btn green')) !!}
                                    {!! HTML::link('/strand', trans('admin/strand.cancel'), array('class' => 'btn default')) !!}
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
    @stop
    @section('pagecss')
    {!! HTML::style('assets/global/plugins/jstree/dist/themes/default/style.min.css') !!}
    @stop()
    @section('pagescripts')
    {!! HTML::script('assets/global/plugins/jstree/dist/jstree.min.js') !!}
    {!! HTML::script('assets/admin/pages/scripts/ui-tree.js') !!}

    <script>
        jsMain = new Main("{{ trans('admin/admin.select_option') }}");
        var parentStrandJson = jQuery.parseJSON('<?php echo $parentStrandJson ?>');

        $(window).load(function () {

            UITree.init();
            $("#tree_1").show();
            var selected = "{{ !empty(Input::old('parent_id')) ? Input::old('parent_id') : ( !empty($strandResult['parent_id']) ? $strandResult['parent_id'] : '') }}";

            jsMain.makeDropDownJsonData(parentStrandJson, $("#parent_id"), $("#subject").val(), selected);

        });
        jQuery(document).ready(function () {
            $("#is_substrand").click(function () {
                if ($('#is_substrand').is(':checked'))
                    $("#sub_st_div").show();
                else
                    $("#sub_st_div").hide();
            });

            $("#subject").change(function () {
                jsMain.makeDropDownJsonData(parentStrandJson, $("#parent_id"), $(this).val(), '');
            });

            $('#tree_1').on('click', '.delete_row', function (e) {
                e.preventDefault();
                var eleObj = $(this);
                bootbox.confirm({
                    message: "{!! trans('admin/admin.delete-confirm') !!}",
                    callback: function (result) {
                        if (result) {
                            $.ajax({
                                url: "{{ route('strand.delete') }}",
                                method: 'POST',
                                data: {id: eleObj.data('id')},
                                beforeSend: function () {

                                },
                                success: function (returnData) {
                                    //delete row and reload table
                                    location.href = "{{ route('strand.index') }}";
                                },
                                error: function (xhr, textStatus, errorThrown) {
                                    //other stuff
                                },
                                complete: function () {

                                }
                            });
                        }
                    }
                });
            });
        });

        function editStrand(act) {
            location.href = act;
        }
    </script>
    {!! JsValidator::formRequest($JsValidator, '#strandfrm'); !!}  
    @stop