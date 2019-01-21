<div class="hang">
    <div class="hMain myrevision_mas"><h2>{{trans('front/front.help_centre')}}</h2></div>
    <div class="hMid">
        <a href="{{ route('helpcentre.subject','maths') }}" class="{{ isset($subject) && strtolower($subject) == strtolower(MATH) ?'hTag_darkBlue':'hTag_blue' }}">{{trans('front/front.maths')}}</a>
    </div>
    <div class="hLast">
        <a href="{{ route('helpcentre.subject','english') }}" class="{{ isset($subject) && strtolower($subject) == strtolower(ENGLISH) ?'hTag_darkBlue':'hTag_blue' }}">{{trans('front/front.english')}}</a>
    </div>
</div>