@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.messages.leftbar-message',['message' => $message]) 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid">
            <div id="content-l" class="msg_content">
                @if($threads->count() > 0)
                <div class="msg_table">
                    <ul>
                        @foreach($threads as $thread)
                        <li class="msg_icon1" id="num-{{ $thread->id }}">
                            @if(!empty($thread->creator()->image))
                            <img class="todo-userpic" src="{{ SITE_URL }}/userimg/{{ $thread->creator()->image }}?size=small" width="40px">
                            @else
                            <img class="todo-userpic" src="../../assets/admin/layout/img/avatar4.jpg" width="27px" height="27px">
                            @endif 
                            <span class="msg_text msg_black"><a href="{{ route('messages.show', ['sent',$thread->id]) }}">{{ $thread->subject }}</a></span>
                            <span><a href="javascript:void(0);" data-id="{{ $thread->id }}" class="delete_icon delete_row .delete_rowdelete_row">Delete</a></span>
                            <span>{{ outputDateTimeFormat($thread->updated_onetoone_at) }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @else
                <div class="not_completed">{{ trans('front/front.no_inbox') }}</div>
                @endif
            </div>
        </div>
        <div class="rbot">&nbsp;</div>
    </div>
    <div class="clear_fix"></div>
</div>
<!-- END PAGE CONTENT-->
@stop

@section('pagescripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script>
    var vars = {
        deleteUrl: "{{ route('messages.delete') }}",
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
        successMsg: "{!! trans('admin/admin.delete-success') !!}"
    };
</script>
{!! HTML::script('js/messagesfront.js') !!}
@stop

