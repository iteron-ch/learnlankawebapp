<div class="hang">
    <div class="hMain mytask_mas"><h2>{{ trans('front/front.task') }}</h2></div>
    <div class="hMid">
        <a href="{{ route('studenttask.index','pending') }}" class="{!! Request::segment(1) == 'task' && (empty(Request::segment(2)) || Request::segment(2) == 'pending') ? 'hTag_green': 'hTag_blue' !!}">{{trans('front/front.task_pending')}}
            @if(Request::segment(1) == 'task' && (empty(Request::segment(2)) || Request::segment(2) == 'pending'))
            <span class="arrow">&nbsp;</span>
            @endif
        </a>
    </div>
    <div class="hMid">
        <a href="{{ route('studenttask.index','overdue') }}" class="{!! Request::segment(1) == 'task' && (Request::segment(2) == 'overdue') ? 'hTag_green': 'hTag_blue' !!}">{{trans('front/front.task_overdue')}}
            @if(Request::segment(1) == 'task' && (Request::segment(2) == 'overdue') ? 'hTag_green': 'hTag_blue')
            <span class="arrow">&nbsp;</span>
            @endif
        </a>
    </div>
    <div class="hLast">
        <a href="{{ route('studenttask.index','completed') }}" class="{!! Request::segment(1) == 'task' && (Request::segment(2) == 'completed') ? 'hTag_green': 'hTag_blue' !!}">{{trans('front/front.task_completed')}}
            @if(Request::segment(1) == 'task' && (Request::segment(2) == 'completed') ? 'hTag_green': 'hTag_blue')
            <span class="arrow">&nbsp;</span>
            @endif
        </a>
    </div>
</div>