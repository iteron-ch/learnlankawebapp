@extends('auth._default')
@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    <div class="login_box ">
        <div class="content align_center">
            <a href="/" class="logo">&nbsp;</a>
            {!! Form::open(['url' => 'forgotpasswordpost', 'method' => 'post','id' => 'frmForgotPassword']) !!}
            <ul>
                <li><strong>Forgot Username ?</strong></li>
                <li>{!! Form::text('email', $value = null , $attributes = ['class' => 'input_text', 'placeholder' => trans('admin/admin.email')]) !!}</li>

                <li>{!! Form::button(trans('admin/admin.submit'), array('type' => 'submit', 'class' => 'button yellow')) !!}</li>

            </ul>
            <div class="form-actions">
                {!! HTML::link(route('login'),'Back to login' , array('id' => 'login-link','class' => 'forget-password')) !!}
            </div>  
            {!! Form::close() !!}
        </div>
        <div class="content second"> </div>
    </div>
</div>
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
    jQuery(document).ready(function () {
        Metronic.init(); // init metronic core components
        Layout.init(); // init current layout
    });
</script>
@stop
