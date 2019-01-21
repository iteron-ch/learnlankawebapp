<div class="hang">
    <div class="hMain mytest_mas"><h2>{{trans('front/front.test_center')}}</h2></div>
   <div class="hMid">
        <a href="{{ route('studenttask.test','maths') }}" class="{{ isset($subject) && strtolower($subject) == strtolower(MATHS) ?'hTag_darkBlue':'hTag_blue' }}">{{trans('front/front.maths')}}</a>
    </div>
    <div class="hLast">
        <a href="{{ route('studenttask.test','english') }}" class="{{ isset($subject) && strtolower($subject) == strtolower(ENGLISH) ?'hTag_darkBlue':'hTag_blue' }}">{{trans('front/front.english')}}</a>
    </div>
</div>