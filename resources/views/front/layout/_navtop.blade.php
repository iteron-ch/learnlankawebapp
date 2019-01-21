<div class="wrapper nav">
    <div class="main_container">
        <ul>
            <li><a href="/" {!! (Request::segment(1) == '' || Request::segment(1) == 'index') ? 'class="active"' : '' !!}>{{ trans('front/front.dashboard') }}</a></li>
            <li><a href="{{ route('messages.inbox') }}" {!! (Request::segment(1) == 'messages' ) ? 'class="active"' : '' !!}>{{ trans('front/front.messages') }}</a></li>
             @if(!session()->get('user')['tutor_id'])
            <li><a href="{{ route('studenttask.index') }}" {!! (Request::segment(1) == 'task') ? 'class="active"' : '' !!}>{{ trans('front/front.tasks') }}</a></li>
            @endif
            <li><a href="{{ route('studenttask.revision') }}" {!! (Request::segment(1) == 'revision' || Request::segment(1) == 'revision-details' || Request::segment(1) == 'revision-questions') ? 'class="active"' : '' !!} >{{ trans('front/front.revision_zone') }}</a></li>
            <li><a href="{{ route('studenttask.test') }}" {!! (Request::segment(1) == 'test') ? 'class="active"' : '' !!}>{{ trans('front/front.test_centre') }}</a></li>
            <li><a href="{{ route('myawards.myawards') }}" {!! (Request::segment(1) == 'myawards' ) ? 'class="active"' : '' !!}>{{ trans('front/front.my_awards') }}</a></li>
            <li><a href="{{ route('result.myresult') }}" {!! (Request::segment(1) == 'myresult' || Request::segment(1) == 'myresult-progresschart') ? 'class="active"' : '' !!}>{{ trans('front/front.my_results') }}</a></li>
            <li><a href="{{ route('helpcentre.helpcentre') }}" {!! (Request::segment(1) == 'help-centre' || Request::segment(1) == 'help-centre') ? 'class="active"' : '' !!}>{{ trans('front/front.help_centre') }}</a></li>
        </ul>
        <div class="clear_fix">&nbsp;</div>
    </div>
</div>