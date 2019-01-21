<div class="hang">
    <div class="hMain myawards_mas"><h2>{{ trans('front/front.my_results') }}</h2></div>
     <div class="hMid">
        <a href="{{ route('result.myresultsubject','maths') }}" class="{{ isset($subject) && strtolower($subject) == strtolower(MATHS) ?'hTag_orange':'hTag_blue' }}">{{trans('front/front.maths')}}
		@if(isset($subject) && strtolower($subject) == strtolower(MATHS))
            <span class="arrow">&nbsp;</span>
            @endif
		</a>
    </div>
    <div class="hLast">
        <a href="{{ route('result.myresultsubject','english') }}" class="{{ isset($subject) && strtolower($subject) == strtolower(ENGLISH) ?'hTag_orange':'hTag_blue' }}">{{trans('front/front.english')}}
		@if(isset($subject) && strtolower($subject) == strtolower(ENGLISH))
            <span class="arrow">&nbsp;</span>
            @endif
		</a>
    </div>
</div>