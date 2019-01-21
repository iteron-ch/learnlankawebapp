@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.front.revision-leftbar') 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid">
            <div class="revisionMaths_zone">
                <div class="revisionMaths_topic_bg">
                    <div class="colMain"><a href="#">Maths<br>Topics</a></div>
                    <div class="colAlgebra">
                        <a href="/revision-subject">
                            <img src="images/algebra_icon.png" />
                            <span>Algebra</span>
                        </a>
                    </div>
                    <div class="colNumber">
                        <a href="/revision-subject">
                            <img src="images/number_icon.png" />
                            <span>Number and<br>Place Value</span>
                        </a>
                    </div>
                    <div class="colStats">
                        <a href="/revision-subject">
                            <img src="images/stats_icon.png" />
                            <span>Statistics</span>
                        </a>
                    </div>
                    <div class="colGeo">
                        <a href="/revision-subject">
                            <img src="images/geometry_icon.png" />
                            <span>Geometry -<br>properties of shape</span>
                        </a>
                    </div>
                    <div class="colFract">
                        <a href="/revision-subject">
                            <img src="images/fraction_icon.png" />
                            <span>Fractions, Decimals<br>and Percentages</span>
                        </a>
                    </div>
                    <div class="colRatio">
                        <a href="/revision-subject">
                            <img src="images/ratio_icon.png" />
                            <span>Ratio &amp;<br>Proportion</span>
                        </a>
                    </div>
                    <div class="colMeasure">
                        <a href="/revision-subject">
                            <img src="images/measure_icon.png" />
                            <span>Measurement</span>
                        </a>
                    </div>
                    <div class="colAdd">
                        <a href="/revision-subject">
                            <img src="images/addition_icon.png" />
                            <span>Addition,<br>subtraction,<br>multiplication and<br>division</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="rbot">&nbsp;</div>
    </div>
    <div class="clear_fix"></div>
</div>

@stop