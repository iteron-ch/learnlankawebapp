@extends(($layout == 'iframe') ? 'admin.layout._reports' : 'admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER -->
<?php if($layout != 'iframe') {?>
@include('admin.layout._page_header', ['title' => trans('admin/admin.manageaccount'), 'trait_1' => $trait['trait_1'], 'trait_1_link' => $trait['trait_1_link'],'trait_2' => $trait['trait_2']])
<?php } ?>
<style>
.select2-container{width:200px}
.table-scrollable{position:relative}
</style>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet for_reports_table">
            <div class="portlet-body">
                <ul class="nav nav-tabs">

                    <li @if($testtype == 'dashboard') class="active" @endif onclick="getreport(<?php echo $schoolId; ?>, 'dashboard', 'math')">
                         <a href="#tab_1_3" data-toggle="tab"><span>Dashboard</span></a>
                    </li>
                    <li @if($testtype == 'test') class="active" @endif onclick="getreport(<?php echo $schoolId; ?>, 'test', 'math')">
                         <a href="#tab_1_1" data-toggle="tab"><span>Test Summary</span></a>
                    </li>
                    <li @if($testtype == 'topic') class="active" @endif onclick="getreport(<?php echo $schoolId; ?>, 'topic', 'math')">
                         <a href="#tab_1_2" data-toggle="tab"><span>Topic Summary</span></a>
                    </li>

                </ul>
                <div class="tab-content">
                    @yield('report_dashboard')
                    @yield('report_test')
                    @yield('report_topic')
                </div>
                
            </div>
        </div>
    </div>
</div>
@stop
@section('pagescripts')
{!! HTML::script('js/jquery.table2excel.js') !!}
<script>
function setdata(id,report,tab){
     
    var paperId = $('#paperId').val();
    var setId = $('#setId').val();
    if(setId == ""){
        alert("Please select set");
        return false;
    }
    var layout = '<?php echo $layout ?>';
    
    if(paperId ==""){
        window.location.href = "/adminreport/schooloverview?id=" + id + "&report=" + report + "&tab=" + tab+"&setId="+setId+"&layout="+layout;
    }else{
       window.location.href = "/adminreport/schooloverview?id=" + id + "&report=" + report + "&tab=" + tab+"&setId="+setId+"&paperId="+paperId+"&layout="+layout; 
    }    
    
}

function exportdata(id,name){
    $("#"+id).table2excel({
        exclude: ".noExl",
        name: "Excel Document Name",
        filename: name,
    }); 

}
function getreport(id, report, tab) {
    var layout = '<?php echo $layout ?>'; 
    window.location.href = "/adminreport/schooloverview?id=" + id + "&report=" + report + "&tab=" + tab+"&layout="+layout;
}
function getsta(id,report,tab,strand,sustrand){
    var layout = '<?php echo $layout ?>'; 
    if(sustrand !=0){
        window.location.href = "/adminreport/schooloverview?id="+id+"&report="+report+"&tab="+tab+"&strand="+strand+"&substrand="+sustrand+"&layout="+layout;;
    }else{
        window.location.href = "/adminreport/schooloverview?id="+id+"&report="+report+"&tab="+tab+"&strand="+strand+"&layout="+layout;;
    }
}

function getsubstr(id,report,tab,sustrand,strand){
     var layout = '<?php echo $layout ?>'; 
    if(strand!=0){
        window.location.href = "/adminreport/schooloverview?id="+id+"&report="+report+"&tab="+tab+"&strand="+strand+"&substrand="+sustrand+"&layout="+layout;;
    }else{
        window.location.href = "/adminreport/schooloverview?id="+id+"&report="+report+"&tab="+tab+"&substrand="+sustrand+"&layout="+layout;;
    }
}
</script>
@yield('graphjs')
@stop