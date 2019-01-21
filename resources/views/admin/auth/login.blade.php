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
        <!-- BEGIN LOGIN FORM -->
        @if(session()->has('ok'))
        @include('admin.partials.error', ['type' => 'success', 'message' => session('ok')])
        @endif
        <h3 class="form-title">{{ trans('admin/auth/login.login_title') }}</h3>
        @if(session()->has('error'))
        @include('admin/partials/error', ['type' => 'danger', 'message' => session('error')])
        @endif
        @include('admin.partials.form_error')
        {!! Form::open(['url' => 'auth/login', 'method' => 'post', 'id' => 'frmLogin']) !!}
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">trans('admin/auth/login.username')</label>
            {!! Form::text('log', $value = null , $attributes = ['class' => 'form-control form-control-solid placeholder-no-fix', 'placeholder' => trans('admin/auth/login.username')]) !!}

        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">trans('admin/auth/login.password')</label>
            {!! Form::password('password', array('class' => 'form-control form-control-solid placeholder-no-fix','autocomplete' => 'off', 'placeholder' => trans('admin/auth/login.password'))) !!}

        </div>
        <div class="form-actions">
            {!! Form::button(trans('admin/auth/login.login'), array('type' => 'submit', 'class' => 'btn btn-success uppercase')) !!}
            <label class="rememberme check">
                <input type="checkbox" name="memory" value="1"/>{{ trans('admin/auth/login.remind') }} 
            </label>
            {!! HTML::link(route('forgotpassword'),trans('admin/auth/login.forget_password') , array('id' => 'forget-password','class' => 'forget-password')) !!}
        </div>

        {!! Form::close() !!}
        <!-- END LOGIN FORM -->
    </div>
    <!-- END LOGIN -->
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
