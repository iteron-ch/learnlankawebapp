@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => 'Test Set', 'trait_1' => $trait['trait_1']])
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
                    
                    
                        
                </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">

                            {!! Form::button('Submit', array('type' => 'submit', 'class' => 'btn green')) !!}
                            <a href="{{  route('questionset.index')   }}" class="btn default">Cancel</a>

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
 <!-- END PAGE LEVEL SCRIPTS -->
    {!! JsValidator::formRequest($JsValidator, '#questionsetfrm'); !!}   
  
    @stop