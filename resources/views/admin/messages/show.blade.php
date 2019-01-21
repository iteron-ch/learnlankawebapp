@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/messages.manage_message_centre'), 'trait_1' => $message])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! $message !!}
                </div>  
                <div class="actions">
                    <a href="{{ $back_link }}" class="btn btn-default btn-sm">
                        <i class="glyphicon glyphicon-chevron-left"></i> {!! trans('admin/admin.back') !!} </a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <h2>{!! $thread->subject !!}</h2>
                    <!--<div><strong>{!! trans('admin/messages.participants') !!}:</strong>  {{ ltrim($thread->participantsString($thread->id, ['first_name','last_name','school_name']),',')}}</div>-->
                    
                    <br>
                    <ul class="media-list">
                        <li class="media" id="thread_{{$thread->id}}">  
                            @foreach($messages as $message)
                            @include('admin.messages.html-message', $message)
                            @endforeach
                        </li>
                    </ul>
                    <div class="row">
                        {!! Form::open(['route' => ['messages.update', $thread->id], 'method' => 'PUT']) !!}
                        <div class="col-md-12">
                            <ul class="media-list">
                                <li class="media">
                                    @if(!empty($message->user->image))
                                        <img class="todo-userpic pull-left" src="{{ SITE_URL }}/userimg/{{ auth()->user()->image }}?size=small" width="27px">
                                    @else    
                                        <img class="todo-userpic pull-left" src="../../assets/admin/layout/img/avatar4.jpg" width="27px">
                                    @endif
                                    <div class="media-body">
                                        {!! Form::textarea('message',null,['class'=>'form-control todo-taskbody-taskdesc','rows' => '4','id'=>'message']  )  !!}
                                    </div>
                                </li>
                            </ul>
                            <span id="error_msg" style="color:red;margin-left:25px;"></span>
                            {!! Form::button(trans('admin/admin.submit'), array('type' => 'submit', 'class' => 'pull-right btn green','id'=>'submitmessage')) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>


            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
    <!-- END PAGE CONTENT-->
</div>
@stop

@section('pagescripts') 
<script>
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