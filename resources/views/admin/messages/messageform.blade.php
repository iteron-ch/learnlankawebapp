@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => $page_heading, 'trait_1' => $trait['trait_1']])
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
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                @yield('form')
                <div class="form-body">
                    @include('admin.partials.form_error')
                    <div class="form-group">
                        {!! Form::labelControl('to',trans('admin/messages.to'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-6 checkbox-list">
                            @if(isset($userTypeEnable['school']))
                            {!! Form::checkbox('to[]', 2, null, ['class' => 'checkbox-inline chk_to','id' => 'to_school']) !!} {!! trans('admin/messages.school') !!}
                            @endif
                            @if(isset($userTypeEnable['teacher']))
                            {!! Form::checkbox('to[]', 3, null, ['class' => 'checkbox-inline chk_to','id' => 'to_teacher']) !!} {!! trans('admin/messages.teacher') !!}
                            @endif
                            @if(isset($userTypeEnable['parent']))
                            {!! Form::checkbox('to[]', 4, null, ['class' => 'checkbox-inline chk_to','id' => 'to_parent']) !!} {!! trans('admin/messages.tutor_parent') !!}
                            @endif
                            @if(isset($userTypeEnable['student']))
                            {!! Form::checkbox('to[]', 5, null, ['class' => 'checkbox-inline chk_to','id' => 'to_student']) !!} {!! trans('admin/messages.student') !!}
                            @endif
                        </div>
                    </div>
                    <div class="form-group" id="to_school_list" style="display:none;">
                        <label class="control-label col-md-3">{!! trans('admin/messages.school_recipients') !!}</label>
                        <div class="col-md-4">
                            {!! Form::select('recipients[]', $schoolArray, null, ['id'=>'to_school_recipients','multiple'=>'multiple','class'=>'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group" id="to_teacher_list" style="display:none;">
                        <label class="control-label col-md-3">{!! trans('admin/messages.teacher_recipients') !!}</label>
                        <div class="col-md-4">
                            {!! Form::select('recipients[]', $teacherArray, null, ['id'=>'to_teacher_recipients','multiple'=>'multiple','class'=>'form-control']) !!}
                        </div>
                    </div>


                    <div class="form-group" id="to_parent_list" style="display:none;">
                        <label class="control-label col-md-3">{!! trans('admin/messages.parent_recipients') !!}</label>
                        <div class="col-md-4">
                            {!! Form::select('recipients[]', $parentArray, null, ['id'=>'to_parent_recipients','multiple'=>'multiple','class'=>'form-control']) !!}
                        </div>
                    </div>


                    <div class="form-group" id="to_student_list" style="display:none;">
                        <label class="control-label col-md-3">{!! trans('admin/messages.student_recipients') !!}</label>
                        <div class="col-md-4">
                            {!! Form::select('recipients[]', $studentArray, null, ['id'=>'to_student_recipients','multiple'=>'multiple','class'=>'form-control']) !!}
                        </div>
                    </div>

                </div>


                <div class="form-group">
                    {!! Form::labelControl('subject',trans('admin/messages.subject'),['class'=>'control-label col-md-3'], TRUE )  !!}
                    <div class="col-md-4">
                        {!! Form::text('subject',null,['subject' => 'subject','class'=>'form-control'])  !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::labelControl('message',trans('admin/messages.message'),['class'=>'control-label col-md-3'], TRUE )  !!}
                    <div class="col-md-4">
                        {!! Form::textarea('message',null,['class'=>'form-control']  )  !!}
                    </div>
                </div>

                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            {!! Form::button(trans('admin/admin.send'), array('type' => 'submit', 'class' => 'btn green')) !!}
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
{!! JsValidator::formRequest($JsValidator, '#messagefrm'); !!} 
{!! HTML::style('assets/global/plugins/jquery-ui/jquery-ui.min.css') !!}
{!! HTML::style('css/jquery.multiselect.css') !!}
{!! HTML::style('css/jquery.multiselect.filter.css') !!}
{!! HTML::script('js/jquery.multiselect.js') !!}
{!! HTML::script('js/jquery.multiselect.filter.js') !!}	
<script>
    jQuery(document).ready(function () {
        if ($('input[id="to_school"]').is(':checked')) {
            setTimeout(function () {
                $("#to_school_list").show();
            });

        }
        if ($('input[id="to_teacher"]').is(':checked')) {
            setTimeout(function () {
                $("#to_teacher_list").show();
            });
        }
        if ($('input[id="to_parent"]').is(':checked')) {
            setTimeout(function () {
                $("#to_parent_list").show();
            });

        }
        if ($('input[id="to_student"]').is(':checked')) {
            setTimeout(function () {
                $("#to_student_list").show();
            });

        }

        $(".chk_to").click(function () {
            $("#" + $(this).attr('id') + "_list").toggle();
            if (!$(this).is(':checked')) {
                $("#" + $(this).attr('id') + "_recipients option:selected").removeAttr("selected");
            }
        });
        $("#to_school_recipients,#to_teacher_recipients, #to_parent_recipients, #to_student_recipients").multiselect({
            autoOpen: false,
            noneSelectedText: "Select",
            open: function ()
            {
                $("input[type='search']:first").focus();
            }
        }).multiselectfilter();
//            $("#teacher_recipients").multiselect({});
        //          $("#parent_recipients").multiselect({});
        //        $("#student_recipients").multiselect({});
    });
    
    
</script>
<style>
    div.checker input{opacity:1}
    .ui-multiselect-filter input {
        border: 1px solid #ddd;
        color: #333;
        font-size: 11px;
        height: auto;
    }
</style>
@stop