@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.messages.leftbar-message',['message' => $message]) 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid">
            <div id="content-l" class="msg_content">
                <div class="msgSubject_reply">
                    <div class="msgSubject msg_blue">{!! $thread->subject !!}</div>
                    <div class="msgReply" id="btn_replay">{{ trans('front/front.reply') }}</div>
                </div>
                <div id="thread_{{$thread->id}}">
                    @foreach($messages as $message)
                    @include('front.messages.html-message', $message)
                    @endforeach
                </div>
                {!! Form::open(['route' => ['messages.update', $thread->id], 'method' => 'PUT']) !!}
                <!-- Message Form Input -->
                <div class="form-body reply_box">
                    <div class="form-group">
                        <div class="col-md-4">
                            {!! Form::textarea('message',null,['class'=>'form-control','id' => 'message']  )  !!}
                           
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <div class="button_row">
                                     <span id="error_msg" style="color:red;"></span>{!! Form::button(trans('front/front.send'), array('type' => 'submit', 'class' => 'submit_button green','id'=>'submitmessage')) !!}

                                    <!--<a href="#" class="cancel" id="cancel">{{ trans('front/front.delete') }}</a>-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--	</form>-->
                    {!! Form::close() !!}
                    <!-- END FORM-->
                </div>
                <div class="back_btn">
                    <a href="{{ $back_link }}">Back</a>
                </div>
            </div>
        </div>
        <div class="rbot">&nbsp;</div>
    </div>
    <div class="clear_fix"></div>
</div>
@stop
@section('pagescripts') 
<script>
    $(document).ready(function () {
        $("#btn_replay").click(function (e) {
            e.preventDefault();
            $("#message").focus();
        });
        $("#cancel").click(function (e) {
            e.preventDefault();
            $("#message").val("");
        });
    });

    $('#submitmessage').bind('click', function (e) {
        if ($.trim($('#message').val()) === '') {
            $("#error_msg").html('Please enter the message.');
            $("#message").focus();
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    })
</script>
@stop
