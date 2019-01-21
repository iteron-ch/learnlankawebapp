@extends('admin.layout._master')

@section('head')
@stop

@section('body')
<!-- BEGIN BODY -->
<body class="login">
    <!-- BEGIN LOGO -->
    <div class="logo">
        {!! HTML::image(route('image','logo.png'),'logo',['class' => 'logo-default','width' => '200']) !!}
    </div>
    <!-- END LOGO -->
    <!-- BEGIN LOGIN -->
    <div class="content">
        <!-- BEGIN PASSWORD RESET FORM -->
        <h3 class="form-title">{{ trans('admin/auth/forgotpassword.resetpassword_title') }}</h3>
        <p>
            {{ trans('admin/auth/forgotpassword.reset_text') }}
        </p>
        @if(session()->has('error'))
        @include('admin/partials/error', ['type' => 'danger', 'message' => session('error')])
        @endif	
        @include('admin.partials.form_error')
        {!! Form::open(['url' => 'password/reset', 'method' => 'post','id' => 'frmPasswordReset']) !!}
        {!! Form::hidden('token', $token) !!}
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">trans('admin/admin.email')</label>
            {!! Form::text('email', $value = null , $attributes = ['class' => 'form-control form-control-solid placeholder-no-fix', 'placeholder' => trans('admin/admin.email')]) !!}

        </div>
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">trans('admin/admin.password')</label>
            {!! Form::password('password',['class' => 'form-control form-control-solid placeholder-no-fix', 'placeholder' => trans('admin/admin.password')]  )  !!}
        </div>
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">trans('admin/admin.confirm_password')</label>
            {!! Form::password('password_confirmation',['class' => 'form-control form-control-solid placeholder-no-fix', 'placeholder' => trans('admin/admin.confirm_password')]  )  !!}
        </div>

        <div class="form-actions">
            {!! Form::button(trans('admin/admin.submit'), array('type' => 'submit', 'class' => 'btn btn-success uppercase')) !!}
        </div>

        {!! Form::close() !!}
        <!-- END PASSWORD RESET FORM -->
    </div>
    @stop

    @section('styles')
    <!-- BEGIN PAGE LEVEL STYLES -->
    {!! HTML::style('assets/admin/pages/css/login.css') !!}
    <!-- END PAGE LEVEL SCRIPTS -->
    @stop

    @section('scripts')
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    {!! HTML::script('assets/global/scripts/metronic.js') !!}
    {!! HTML::script('assets/admin/layout/scripts/layout.js') !!}
    <!-- END PAGE LEVEL SCRIPTS -->
    {!! JsValidator::formRequest('App\Http\Requests\Auth\LoginRequest', '#frmLogin'); !!}   
    <script>
        jQuery(document).ready(function() {
            Metronic.init(); // init metronic core components
            Layout.init(); // init current layout
        });
    </script>
    @stop