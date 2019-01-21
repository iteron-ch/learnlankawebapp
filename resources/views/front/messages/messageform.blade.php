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
                        {!! Form::labelControl('recipients',trans('admin/messages.recipients'),['class'=>'control-label col-md-2'], FALSE )  !!}
                        <div class="col-md-4">
                            {!! Form::select('recipients[]', $recipientsData, null, ['id'=>'recipients','multiple'=>'multiple','class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::labelControl('subject',trans('admin/messages.subject'),['class'=>'control-label col-md-3'], TRUE )  !!}
                        <div class="col-md-4">
                            {!! Form::text('subject',null,['class'=>'form-control'])  !!}
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
                                {!! Form::button(trans('admin/admin.submit'), array('type' => 'submit', 'class' => 'btn green')) !!}
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
@stop