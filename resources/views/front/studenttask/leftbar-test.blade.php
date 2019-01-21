<div class="hang">
    <div class="hMain mytest_mas"><h2>{{trans('front/front.test_center')}}</h2></div>
   <div class="hMid">
        <a href="{{ route('studenttask.testsubject','maths') }}" class="{{ isset($subject) && strtolower($subject) == strtolower(MATHS) ?'hTag_red':'hTag_blue' }}">{{trans('front/front.maths')}}
		@if(isset($subject) && strtolower($subject) == strtolower(MATHS))
            <span class="arrow">&nbsp;</span>
            @endif
		</a>
    </div>
    <div class="hLast">
        <a href="{{ route('studenttask.testsubject','english') }}" class="{{ isset($subject) && strtolower($subject) == strtolower(ENGLISH) ?'hTag_red':'hTag_blue' }}">{{trans('front/front.english')}}
		@if(isset($subject) && strtolower($subject) == strtolower(ENGLISH))
            <span class="arrow">&nbsp;</span>
            @endif
		</a>
    </div>
</div>