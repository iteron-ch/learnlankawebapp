@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.front.myresult-leftbar') 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid pt40">
            <div class="rArrow">&nbsp;</div>
            <h1>
                <a href="javascript:void(0)" class="back">&nbsp;</a>
                <div class="heading">My Tests</div>
            </h1>
            <div class="score">
                <div class="sScore">
                    <p class="sHeading">Starting Score</p>
                    <p class="spercent sRed">30%</p>
                </div>
                <div class="cScore">
                    <p class="sHeading">Current Score</p>
                    <p class="spercent sYellow">50%</p>
                </div>
            </div>

            <div id="tabs" style="height: 225px">
                <div id="chartContainer1" style="height: 240px; width: 100%;"></div>
            </div>

            <div class="success_sec green_bg_box">
                <div class="ssTitle">Great job! You've understood these topics</div>
                <ol>
                    <li class="list_Box darkGreen_bg_box"><span>1.</span> <span>Number and place value</span></li>
                    <li class="list_Box darkGreen_bg_box"><span>2.</span> <span>Measurement</span></li>
                    <li class="list_Box darkGreen_bg_box"><span>3.</span> <span>Addition, subtraction, multiplication and division (calculations)</span></li>
                    <li class="list_Box darkGreen_bg_box"><span>4.</span> <span>Ratio and proportion</span></li>
                    <li class="list_Box darkGreen_bg_box"><span>5.</span> <span>Algebra</span></li>
                </ol>
            </div>

            <div class="success_sec red_bg_box">
                <div class="ssTitle">You need to work on the following topics</div>
                <ol>
                    <li class="list_Box darkRed_bg_box"><span>1.</span> <span>Geometry - Properties of shapes</span></li>
                    <li class="list_Box darkRed_bg_box"><span>2.</span> <span>Statistics</span></li>
                    <li class="list_Box darkRed_bg_box"><span>3.</span> <span>Fractions, decimals and percentages</span></li>
                </ol>
            </div>
            <a href="#" class="orangeBtn">
                <p>Test Set 1 Results</p>
                <span><img src="images/btn_down_arrow.png" /></span>
            </a>
            <div class="threeCols">
                <div class="tCols">
                    <div class="tpTitle">Paper 1</div>
                    <div class="tpName">Mental Maths</div>
                    <div class="tScore">Raw score:
                        <span class="tsRed">4/20</span>
                    </div>
                    <div class="tScore">Percentage:
                        <span class="tsRed">20%</span>
                    </div>
                </div>
                <div class="tCols">
                    <div class="tpTitle">Paper 1</div>
                    <div class="tpName">Mental Maths</div>
                    <div class="tScore">Raw score:
                        <span class="tsYellow">10/20</span>
                    </div>
                    <div class="tScore">Percentage:
                        <span class="tsYellow">50%</span>
                    </div>
                </div>
                <div class="tCols">
                    <div class="tpTitle">Paper 1</div>
                    <div class="tpName">Mental Maths</div>
                    <div class="tScore">Raw score:
                        <span class="tsGreen">16/20</span>
                    </div>
                    <div class="tScore">Percentage:
                        <span class="tsGreen">80%</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="rbot">&nbsp;</div>
    </div>
    <div class="clear_fix"></div>
</div>
<!-- END PAGE CONTENT-->
@stop
@section('styles')
{!! HTML::style('css/jquery-ui.min.css') !!}
@stop
@section('scripts')
{!! HTML::script('js/jquery.canvasjs.min.js') !!}
<script>
    
    
$(function () {
	$(".qa_table .qa_progress .qa_pbar").each(function() {
				val = $(this).data("value");
				$(this).animate({
						width: val
					}, 1500);
			});
	//Better to construct options first and then pass it as a parameter
	var options1 = {
		title: {
			text: "My Progress Chart"
		},
                animationEnabled: true,
		data: [
		{
		type: "line", //change it to spline, line, area, bar, pie, etc
			dataPoints: [
				{ y: 10 },
				{ y: 6 },
				{ y: 14 },
				{ y: 12 },
				{ y: 19 },
				{ y: 14 },
				{ y: 26 },
				{ y: 10 },
				{ y: 22 },
				{ y: 10 },
				{ y: 6 },
				{ y: 14 },
				{ y: 12 },
				{ y: 19 },
				{ y: 14 },
				{ y: 26 },
				{ y: 10 },
				{ y: 22 }
			]
		}
		],
      axisX: {
        labelFontSize: 14
      },
         axisY: {
        labelFontSize: 14
      }
	};

	var options2 = {
		title: {
			text: "My Progress Chart"
		},
		data: [
		{
			type: "splineArea", //change it to line, area, bar, pie, etc
			dataPoints: [
				{ y: 10 },
				{ y: 6 },
				{ y: 14 },
				{ y: 12 },
				{ y: 19 },
				{ y: 14 },
				{ y: 26 },
				{ y: 10 },
				{ y: 22 }
			]
		}
		],
      axisX: {
        labelFontSize: 14
      },
       axisY: {
        labelFontSize: 14
      }
	};

	$("#tabs").tabs({
		create: function (event, ui) {
			//Render Charts after tabs have been created.
			$("#chartContainer1").CanvasJSChart(options1);
			$("#chartContainer2").CanvasJSChart(options2);
		},
		activate: function (event, ui) {
			//Updates the chart to its container's size if it has changed.
			ui.newPanel.children().first().CanvasJSChart().render();
		}
	});

});    
</script>
@stop 