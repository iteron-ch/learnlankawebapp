<a class="pull-left" href="javascript:;">
    @if(!empty($message->user->image))
        <img class="todo-userpic" src="{{ SITE_URL }}/userimg/{{ $message->user->image }}?size=small" width="40px">
    @else
        <img class="todo-userpic" src="../../assets/admin/layout/img/avatar4.jpg" width="27px" height="27px">
    @endif         
         
</a>
<div class="media-body todo-comment">
    <p class="todo-comment-head">
        <span class="todo-comment-username">{!! $message->user->user_type ? $message->user->school_name : $message->user->username !!}</span> &nbsp; <span class="todo-comment-date">{!! outputDateTimeFormat($message->created_at) !!}</span>
    </p>
    <p class="todo-text-color">{!! nl2br(e($message->body)) !!}</p>
</div>
<hr>