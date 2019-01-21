@extends('front.layout._default')

@section('content')
@if(!session()->get('user')['tutor_id'])
    <div class="main_container bashboard_landing test">
@else        
    <div class="main_container bashboard_landing">
@endif    

    <div class="revision"><a href="/revision">{{trans('front/front.revision')}}</a></div>
    <div class="tests"><a href="/test">{{trans('front/front.tests')}}</a></div>
    <div class="results"><a href="/myresult">{{trans('front/front.results')}}</a></div>
    <div class="help_Centre"><a href="{{ route('helpcentre.helpcentre') }}">{{trans('front/front.help_centre')}}</a></div>
    <div class="awards"><a href="{{ route('myawards.myawards') }}">{{trans('front/front.awards')}}</a></div>
    <div class="messages"><a href="{{ route('messages.inbox') }}" style="width: 200px;">{{trans('front/front.messages')}}&nbsp;@include('front.messages.unread-count')</a></div>
    @if(!session()->get('user')['tutor_id'])
        <div class="tasks"><a href="/task">{{trans('front/front.tasks')}}</a></div>
    @endif    

</div>

<div id="login_div">
    <div class="main_container">
        <div class="portlet-body form">
            <div class="error-class" id="error_msg" style="display:none;"></div>
            {!! Form::open(['url' => 'auth/login','files' => true, 'method' => 'post', 'class' => 'form-horizontal','id' =>'frmLogin']) !!}
            <div class="form-body" id="user_login" style="display:none;">
                <div class="form-group">
                    {!! Form::labelControl('log',trans('front/front.username'),['class'=>'control-label col-md-3'], TRUE )  !!}
                    <div class="col-md-4">
                        {!! Form::text('log',null,['class'=>'form-control']  )  !!}
                    </div>
                </div>  
                <div class="form-group">
                    {!! Form::labelControl('password',trans('front/front.password'),['class'=>'control-label col-md-3'], TRUE )  !!}
                    <div class="col-md-4">
                        {!! Form::password('password',null,['class'=>'form-control']  )  !!}
                    </div>
                </div> 
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            {!! Form::button(trans('front/front.log_in'), array('type' => 'button','id'=>'loginSubmit', 'class' => 'btn green')) !!}
                            {!! Form::button(trans('front/front.cancel'), array('type' => 'button','class' => 'btn default','id'=>'cancel_login',)) !!}
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div> 
</div> 
<!-- BEGIN PAGE CONTENT-->
@stop
@section('scripts')
{!! JsValidator::formRequest('App\Http\Requests\Auth\LoginRequest', '#frmLogin'); !!} 
<script>
    jQuery(document).ready(function () {
        $("#loginSubmit").click(function () {
            $("#error_msg").hide();
            if ($("#frmLogin").valid()) {
                var postData = $("#frmLogin").serializeArray();
                var formURL = $("#frmLogin").attr("action");
                $.ajax(
                        {
                            url: formURL,
                            type: "POST",
                            data: postData,
                            success: function (data, textStatus, jqXHR)
                            {
                                if (data.success == true) {
                                    window.location.href = data.action;
                                }
                                else {
                                    $("#error_msg").html(data.message);
                                    $("#error_msg").show();
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown)
                            {
                                //if fails      
                            }
                        });

            }
        });
        $("#cancel_login").click(function () {
            $("#user_login").hide();
        });
    });
    function showLogin() {
        $("#user_login").show();
    }

</script>
@stop