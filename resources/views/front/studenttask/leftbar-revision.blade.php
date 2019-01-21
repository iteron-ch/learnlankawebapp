<div class="hang">
    <div class="hMain myrevision_mas"><h2>{{trans('front/front.revision_zone')}}</h2></div>
    <div class="hMid">
        <a href="{{ route('studenttask.revisionsubject','maths') }}" class="{{ isset($subject) && strtolower($subject) == strtolower(MATHS) ?'hTag_darkBlue':'hTag_blue' }}">{{trans('front/front.maths')}}
		@if(isset($subject) && strtolower($subject) == strtolower(MATHS))
            <span class="arrow">&nbsp;</span>
            @endif
		</a>
    </div>
    <div class="hLast">
        <a href="{{ route('studenttask.revisionsubject','english') }}" class="{{ isset($subject) && strtolower($subject) == strtolower(ENGLISH) ?'hTag_darkBlue':'hTag_blue' }}">{{trans('front/front.english')}}
		@if(isset($subject) && strtolower($subject) == strtolower(ENGLISH))
            <span class="arrow">&nbsp;</span>
            @endif
		</a>
    </div>
</div>