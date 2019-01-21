<div class="hang messgae_box">
<div class="hMain mymessage_mas"><h2>{{trans('front/front.messages')}}@include('front.messages.unread-count')</h2></div>
    <!--h2>{{trans('front/front.messages')}}@include('front.messages.unread-count')</h2-->

		<div class="hMid">
		 <a href="{{ route('messages.inbox') }}" class="{{ $message == 'inbox' ? 'active' : 'hTag_blue' }}">{{trans('front/front.inbox')}} 
            @if($message == 'inbox')
            <span class="arrow">&nbsp;</span>
            @endif
			
        </a>
		</div>
		<div class="hLast">
		<a href="{{ route('messages.sent') }}" class="{{ $message == 'sent' ? 'active' : 'hTag_blue' }}">{{trans('front/front.sent')}} 
            @if($message == 'sent')
            <span class="arrow">&nbsp;</span>
            @endif 
			
        </a></div>

</div>
           
