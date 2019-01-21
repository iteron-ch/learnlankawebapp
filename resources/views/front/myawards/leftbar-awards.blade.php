<div class="hang">
    <div class="hMain mymessage_mas"><h2>My Awards</h2></div>
    <div class="hMid">
        <a href="{{ route('myawards.subject','maths') }}" class="{{ isset($subject) && strtolower($subject) == strtolower(MATHS) ?'hTag_red':'hTag_blue' }}">{{trans('front/front.maths')}}</a>
    </div>
    <div class="hLast">
        <a href="{{ route('myawards.subject','english') }}" class="{{ isset($subject) && strtolower($subject) == strtolower(ENGLISH) ?'hTag_red':'hTag_blue' }}">{{trans('front/front.english')}}</a>
    </div>
</div>