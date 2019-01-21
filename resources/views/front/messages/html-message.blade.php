<div class="inbox_messgae_details"><div class="msg_table">
    <ul class="from">
        <li class="msg_icon1">
            @if(!empty($message->user->image))
        <img class="todo-userpic" src="{{ SITE_URL }}/userimg/{{ $message->user->image }}?size=small" width="40px">
    @else
        <img class="todo-userpic" src="../../assets/admin/layout/img/avatar4.jpg" width="27px" height="27px">
    @endif  
            <span class="msg_text msg_blue">{{ $message->user->username }}</span>
            <span>{!! outputDateTimeFormat($message->created_at) !!}</span>
        </li>
    </ul>
</div>
<div class="msgBody">{!! nl2br(e($message->body)) !!}</div>
</div>