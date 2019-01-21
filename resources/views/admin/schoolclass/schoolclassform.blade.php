@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
@if(isset($pgAct) && $pgAct == 'addmore')
@include('admin.layout._page_header', ['title' => $page_heading, 'trait_1' => $trait['trait_1'], 'trait_1_link' => $trait['trait_1_link'], 'trait_2' =>'Add Student'])
@else
@include('admin.layout._page_header', ['title' => $page_heading, 'trait_1' => $trait['trait_1'], 'trait_1_link' => $trait['trait_1_link'], 'trait_2' => $trait['trait_2']])
@endif    
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN VALIDATION STATES-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                   @if(isset($pgAct) && $pgAct == 'addmore')
                    Add Student
                    @else 
                    {{ $page_title }}
                    @endif 
                </div>
                <div class="actions">
                    <a href="{{ route('manageclass.index') }}" class="btn btn-default btn-sm">
                        <i class="glyphicon glyphicon-chevron-left"></i> {!! trans('admin/admin.back') !!} </a>
                </div> 
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                @yield('form')
                <div class="form-body">
                    @include('admin.partials.form_error')

                    <div class="form-group">
                        {!! Form::labelControl('class_name',trans('admin/schoolclass.class_label'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        </label>
                        <div class="col-md-4">

                            {!! Form::text('class_name',null,['class'=>'form-control',(isset($pgAct) && $pgAct == 'addmore')?'readonly':'']  )  !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('student_label',trans('admin/schoolclass.student_label'),['class'=>'control-label col-md-3'], FALSE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('selected_students[]', $studentsData, null, ['id'=>'selected_students','multiple'=>'multiple','class'=>'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::labelControl('year',trans('admin/schoolclass.year'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        </label>
                        <div class="col-md-4">
                            {!! Form::select('year',$year, null, ['id'=>'year','class'=>'form-control select2me', (isset($pgAct) && $pgAct == 'addmore')?'readonly':'']) !!}
                        </div>
                    </div>
                    <div class="form-group last">
                        {!! Form::labelControl('status',trans('admin/schoolclass.status'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">

                            {!! Form::select('status',$status, null, ['id'=>'status','class'=>'form-control select2me', (isset($pgAct) && $pgAct == 'addmore')?'readonly':'']) !!}

                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">

                            {!! Form::button('Submit', array('type' => 'submit', 'class' => 'btn green')) !!}
                            <a href="{{  action('SchoolClassController@index')   }}" class="btn default">Cancel</a>

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
<!-- END PAGE CONTENT-->
@stop
@section('pagescripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->
{!! HTML::style('assets/global/plugins/jquery-ui/jquery-ui.min.css') !!}
{!! HTML::style('css/jquery.multiselect.css') !!}
{!! HTML::style('css/jquery.multiselect.filter.css') !!}
{!! HTML::script('js/jquery.multiselect.js') !!}
{!! HTML::script('js/jquery.multiselect.filter.js') !!}	 
<!-- END PAGE LEVEL SCRIPTS -->
<script>
    $(document).ready(function () {
        $("#selected_students").multiselect({
            autoOpen: false,
            noneSelectedText: "Select",
            open: function ()
            {
                $("input[type='search']:first").focus();
            }
        }).multiselectfilter();
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
{!! JsValidator::formRequest($JsValidator, '#schoolclassfrm'); !!}   
@stop