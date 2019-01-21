@extends(($layout == 'iframe') ? 'admin.layout._reports' : 'admin.layout._default')
@section('content')
<!-- BEGIN PAGE HEADER-->
<?php if($layout != 'iframe'){ ?>
@include('admin.layout._page_header', ['title' => trans('admin/admin.manageaccount'), 'trait_1' => trans('Student Report')])
<?php } ?>

<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<style>
.down {
    background: rgba(0, 0, 0, 0) url("../../../images/up.png") no-repeat scroll -5px center;
    //display: inline-block;
   // padding: 0 0 0 21px;
   // text-indent: -9999em;
}
.up {
    background: rgba(0, 0, 0, 0) url("../../../images/down.png") no-repeat scroll -5px center;
    //display: inline-block;
    //padding: 0 0 0 21px;
    //text-indent: -9999em;
}
.select2-container{width:200px}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet ">
            <div id="graph">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet box red">
                            <div class="portlet-title">
                                <div class="caption">
                                    Pupil Details 
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="table-scrollable">
                                    <table class="table no_border">
                                        <tr>
                                            <td><b>Pupil Name: </b><?php echo $studentDetail[0]->first_name . " " . $studentDetail[0]->last_name; ?></td>
                                            <td><b>Class: </b><?php echo $studentDetail[0]->className; ?></td>


                                        </tr>
                                        <tr>
                                            @if($testtype == 'test')
                                                <td><b>Group: </b><?php echo $studentDetail[0]->groupName; ?></td>
                                            @endif
                                            
                                            <td <?php if($testtype == 'topic') { echo "colspan='2'";}?> ><b>Number of Awards: </b><?php echo $studentDetail[0]->certificate; ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><b>Last Login: </b><?php echo $studentDetail[0]->last_login; ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet box blue">
                            <div class="portlet-title">
                                <div class="caption">
                                    <input type="button" class="btn default" id="exportgraph" name="exportgraph" value="Export Graph To Excel" />
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div id="dual_x_div"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portlet ">
            <div class="portlet-body margin-top_20" style="position: relative;">
                <ul class="nav nav-tabs">

                    <li @if($testtype == 'test') class="active" @endif onclick="getreport('<?php echo $studentId ?>', 'test', 'math')">
                         <a href="#tab_1_1" data-toggle="tab"><span>Test Summary</span></a>
                    </li>
                    <li @if($testtype == 'topic') class="active" @endif onclick="getreport('<?php echo $studentId ?>', 'topic', 'math')">
                         <a href="#tab_1_2" data-toggle="tab"><span>Topic Summary</span></a>
                    </li>
                </ul>
                <div class="tab-content">
                    @yield('report_test')
                    @yield('report_topic')
                </div>
            </div>
        </div>
    </div>
</div><?php

?>
<form name="graphexport" action="/adminreport/studentgraph" method="post" id="graphexport">
    <input type="hidden" name="graphdata" id="graphdata" value=""/>
</form>
<!-- END PAGE CONTENT-->
@stop
@section('pagescripts')
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['bar']}]}"></script>
{!! HTML::script('js/jquery.tablesorter.min.js') !!}
{!! HTML::script('js/jquery.table2excel.js') !!}
<script>
   
function exportdata(id,name){
    $("#"+id).table2excel({
        exclude: ".noExl",
        name: "Excel Document Name",
        filename: name,
    }); 

}
function sortgrid(id, report, tab, sortby){
    var layout = '<?php echo $layout ?>'; 
    window.location.href = "/adminreport/studenttest?id=" + id + "&report=" + report + "&tab=" + tab +"&sortby="+sortby+"&layout="+layout;
}
// $(function(){
  // $('#keywords').tablesorter(); 
  // $('#keywords1').tablesorter(); 
  // $('#keywords2').tablesorter(); 
  // $('#keywords3').tablesorter(); 
// });

var graphData = jQuery.parseJSON('<?php echo json_encode($graph); ?>');
function expendrowrev(id) {
    $('#subGridrev' + id).toggle();
     $('#tr' + id).toggleClass("down up");
}

function getreport(id, report, tab) { 
    var layout = '<?php echo $layout ?>'; 
    window.location.href = "/adminreport/studenttest?id=" + id + "&report=" + report + "&tab=" + tab +"&layout="+layout;
   
}
var dataArray = [];
dataArray.push(['', 'English', 'Math']);
var mathLenght = graphData.Math.length;
if(graphData.English)
    var englishLenght = graphData.English.length;
var loopLength = mathLenght > englishLenght ? mathLenght : englishLenght;
loopLength = loopLength > 20 ? loopLength : 20;
for (var i = 0; i < loopLength; i++) {
    dataArray.push([i + 1, 0, 0]);
}

$.each(graphData.English, function (i, v) {
    //dataArray[i+1][0] = i + 1;
    //dataArray[i + 1][1] = (graphData.English[i].MarksObtain/graphData.English[i].totalMarks)*100;
    dataArray[i + 1][1] = graphData.English[i];

});
$.each(graphData.Math, function (i, v) {
    //dataArray[i+1][0] = i + 1;
    //dataArray[i + 1][2] = (graphData.Math[i].MarksObtain/graphData.Math[i].totalMarks)*100;
    dataArray[i + 1][2] = graphData.Math[i];

});
var testtype = '<?php echo ucfirst($testtype) ?>';
if(testtype == 'Topic')
    testtype = 'Revision';

testtype = 'Test';
google.setOnLoadCallback(drawStuff(testtype));

function drawStuff(testtype) {

    var data = new google.visualization.arrayToDataTable(dataArray);
    // [
    // ['', 'English', 'Math'],
    // ['1', 8000, 23.3],
    // ['2', 24000, 4.5],
    // ['3', 30000, 14.3],
    // ['4', 50000, 0.9],
    // ['5', 60000, 13.1]
    // ]
    var options = {
        width:'80%',
        height:'300',
        vAxis: {title:'Percentage %'},
        hAxis: {title:testtype},
        chart: {
            title: 'Progress',
            subtitle: 'Student progress chart'
        },
        bars: 'verticals', // Required for Material Bar Charts.

    };

    var chart = new google.charts.Bar(document.getElementById('dual_x_div'));
    chart.draw(data, google.charts.Bar.convertOptions(options));
};
                                                                            
    $('#exportgraph').click(function(){
        $('#graphdata').val(JSON.stringify(dataArray));
        $('#graphdata').serializeArray()
        $('#graphexport').submit();
    });
    $(function () {

        var iFrames = $('iframe');

        function iResize() {

            for (var i = 0, j = iFrames.length; i < j; i++) {
                iFrames[i].style.height = (iFrames[i].contentWindow.document.body.offsetHeight + 100) +'px';
            }
        }

        if ($.browser.safari || $.browser.opera) {

            iFrames.load(function () {
                setTimeout(iResize, 0);
            });

            for (var i = 0, j = iFrames.length; i < j; i++) {
                var iSource = iFrames[i].src;
                iFrames[i].src = '';
                iFrames[i].src = iSource;
            }

        } else {
            iFrames.load(function () {
                this.style.height = (this.contentWindow.document.body.offsetHeight + 100)+'px';
            });
        }

    });
    function expendrow(subject,id) {
        $('#subGrid_'+subject+'_'+ id).toggle();
        $('#tr_'+subject+'_'+ id).toggleClass("down up");
    }
</script>
@stop
