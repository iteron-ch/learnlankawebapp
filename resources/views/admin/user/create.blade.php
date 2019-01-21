@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => $title, 'trait' => '<li><a href="#">Data Tables</a><i class="fa fa-angle-right"></i></li><li><a href="#">Ajax Datatables</a></li>'])
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN VALIDATION STATES-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    Create User
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                {!! Form::open(array('url' => 'user','class'=>'form-horizontal','id' =>'form_sample_3' )) !!}
                <div class="form-body">
                    @include('admin.partials.form_error')
                    <div class="form-group">
                        {!! Form::labelControl('username','First Name',['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::text('username',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('name','Last Name',['class'=>'control-label col-md-3'])  !!}
                        
                        <div class="col-md-4">

                            {!! Form::text('name',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('email','Email Address',['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::email('email',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('occupation','Occupation',['class'=>'control-label col-md-3'])  !!}
                        <div class="col-md-4">
                            {!! Form::text('occupation',null,['class'=>'form-control']  )  !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('size','Select2 Dropdown',['class'=>'control-label col-md-3'], TRUE)  !!}
                        <div class="col-md-4">
                            {!! Form::select('size', array('Option 1' => 'Option 1', 'Option 2' => 'Option 2'), null, ['placeholder' => 'Select an option...','id'=>'select1','class'=>'select2-container form-control']) !!}
                        </div>
                    </div>
                    <!--<div class="form-group">
                       {!! Form::labelControl('size','Select2 Tags',['class'=>'control-label col-md-3'], TRUE)  !!}
                        <div class="col-md-4">
                            {!! Form::select('tag', array('Tag 1' => 'Tag 1', 'Tag 2' => 'Tag 12'), null, ['placeholder' => 'Select a tag...','id'=>'select2_tags','class'=>'form-control']) !!}
                        </div>
                    </div>-->
                    <div class="form-group">
                        {!! Form::labelControl('datepicker','Datepicker',['class'=>'control-label col-md-3'])  !!}
                        <div class="col-md-4">
                            <div class="input-group date date-picker" data-date-format="dd-mm-yyyy">

                                {!! Form::text('datepicker',null,['class'=>'form-control','readonly'=>'']  )  !!}

                                <span class="input-group-btn">
                                    <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                                </span>
                            </div>
                            <!-- /input-group -->
                            <span class="help-block">
                                select a date </span>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('membership','Membership',['class'=>'control-label col-md-3'])  !!}
                        <div class="col-md-4">
                            <div class="radio-list" data-error-container="#form_2_membership_error">
                                <label>
                                    {!! Form::radio('membership', '1') !!}
                                    Fee </label>
                                <label>
                                    {!! Form::radio('membership', '2') !!}
                                    Professional </label>

                            </div>
                            <div id="form_2_membership_error">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('service','Services',['class'=>'control-label col-md-3'])  !!}
                        <div class="col-md-4">
                            <div class="checkbox-list" data-error-container="#form_2_services_error">
                                <label>
                                    {!! Form::checkbox('service', '1') !!}   
                                    Service 1 </label>
                                <label>
                                    {!! Form::checkbox('service', '2') !!}    Service 2 </label>
                                <label>
                                    {!! Form::checkbox('service', '3') !!}    Service 3 </label>
                            </div>
                            <span class="help-block">
                                (select at least two) </span>
                            <div id="form_2_services_error">
                            </div>
                        </div>
                    </div>

                    <div class="form-group last">
                        {!! Form::labelControl('editor2','CKEditor',['class'=>'control-label col-md-3'])  !!}
                        <div class="col-md-9">
                            {!! Form::textarea('editor2',null,['class'=>'ckeditor form-control','rows'=>'6','data-error-container'=>'#editor2_error']  )  !!}
                            <div id="editor2_error">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">

                            {!! Form::button('Submit', array('type' => 'submit', 'class' => 'btn green')) !!}
                            {!! Form::button('Cancel', array('type' => 'submit', 'class' => 'btn default')) !!}

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
{!! HTML::script('assets/global/plugins/ckeditor/ckeditor.js') !!}
<!-- END PAGE LEVEL SCRIPTS -->
{!! JsValidator::formRequest('App\Http\Requests\UserCreateRequest', '#form_sample_3'); !!}
<script>
    var form3 = $("#form_sample_3");
    $(document).ready(function() {
        //initialize datepicker
        $('#date-picker').datepicker({
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd/mm/yy',
            maxDate: new Date,
            yearRange: "-100:+0"
    });

        // initialize select2 tags
        $("#select1").change(function() {
            form3.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input 
        }).select2();
        /*$("#select2_tags").change(function() {
            form3.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input 
        }).select2({
            tags: ["red", "green", "blue", "yellow", "pink"]
        });*/

    });
</script>
@stop