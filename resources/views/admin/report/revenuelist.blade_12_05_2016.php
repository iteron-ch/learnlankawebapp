@extends('admin.layout._default')
<?php

?>
@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/report.manage_revenue_report'), 'trait_1' => trans('admin/report.revenue_reports')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    @if(isset($total_revenue_till_date))
                    {{ $total_revenue_till_date.' '. CURRENCY_CODE }}
                    @else
                    {{ '0' }}
                    @endif
                </div>
                <div class="desc">
                    {!! trans('admin/admin.total_revenue') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    @if(isset($last_month_registrations))
                    {{ $last_month_registrations.' '. CURRENCY_CODE }}
                    @else
                    {{ '0' }}
                    @endif
                </div>
                <div class="desc">
                    {!! trans('admin/admin.total_revenue_tutor') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    @if($school_revenue)
                    {{ $school_revenue.' '. CURRENCY_CODE }}
                    @else
                    {{ '0' }}
                    @endif
                </div>
                <div class="desc">
                    {!! trans('admin/admin.total_revenue_school') !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="radio-list" data-error-container="#form_2_membership_error">
            <label style="display: inline">
                {!! Form::radio('graph_type','monthly' ,true, ['id'=>'monthly']) !!}
                Monthly</label> &nbsp;&nbsp;

            <label style="display: inline">
                {!! Form::radio('graph_type', 'yearly', false,['id'=>'yearly']) !!}
                Yearly</label>
        </div>
        <div id="graph_div" style="height:400px; width:1100px;margin-top:3%;"></div>
        <div id="graph_div2" style="height:400px; width:1100px;margin-top:3%;display:none"></div>
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! trans('admin/report.revenue_reports') !!}
                </div>
            </div>
            <div class="portlet-body">
                {!! Form::open(array('url' => 'revenue','class'=>'form-horizontal','id' =>'search-form' )) !!}
                <div class="form-body">
                    <div class="row">   
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <div class="radio-list" data-error-container="#form_2_membership_error">
                                        <label style="display: inline">
                                            {!! Form::radio('user_type', TUTOR,true, ['id'=>'tutor']) !!}
                                            Parent/Tutor </label> &nbsp;&nbsp;

                                        <label style="display: inline">
                                            {!! Form::radio('user_type', SCHOOL, false,['id'=>'school']) !!}
                                            School</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('username',trans('admin/report.username'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-6">
                                    {!! Form::text('username',null,['id'=>'username','placeholder'=>trans('admin/report.username'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::labelControl('amount',trans('admin/report.amount'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-6">
                                    {!! Form::text('amount',null,['id'=>'amount','placeholder'=>trans('admin/report.amount'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="col-md-12">
                                    {!! Form::button(trans('admin/admin.search'), array('type' => 'submit', 'class' => 'btn btn-primary')) !!} 
                                    {!! Form::button(trans('admin/admin.reset'), array('type' => 'reset', 'id'=>'reset','class' => 'btn btn-primary grey')) !!} 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
            <hr style="border-top: 1px solid #26a69a;" >
        </div>
        {!! Form::close() !!}
        <div class="info"></div>
        <table id="users-table" class="table table-striped table-bordered table-hover">
            <thead>
                <tr class="heading">
                    <th>{!! trans('admin/tutor.tutor_name') !!}</th>
                    <th>{!! trans('admin/tutor.description') !!}</th>
                    <th>{!! trans('admin/tutor.voucher') !!}</th>
                    <th>{!! trans('admin/tutor.date') !!}</th>
                    <th>{!! trans('admin/report.amount_gbp') !!}</th>
                    <th>{!! trans('admin/school.actions') !!}</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
</div>
<!-- END EXAMPLE TABLE PORTLET-->
</div>
<!-- END PAGE CONTENT-->
@stop
@section('pagescripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<!-- END PAGE LEVEL SCRIPTS -->

<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['bar']}]}"></script>
<script type="text/javascript">
        $(document).ready(function () {
$('input[type=radio][name=graph_type]').on('change', function() {
var radioValue = $("input[name='graph_type']:checked").val();
        if (radioValue == 'monthly'){
$("#graph_div").show();
        $("#graph_div2").hide();
}
else if (radioValue == 'yearly'){
$("#graph_div").hide();
        $("#graph_div2").show();
        drawStuff2()
}
})
});
        google.setOnLoadCallback(drawStuff);
        function drawStuff() {
        var data = new google.visualization.arrayToDataTable([<?php echo $recordRegistredArray ?>]);
                var options = {
                width: '30%',
                        vAxis: {title: 'Pounds(£)'},
                        hAxis: {title: 'Months'},
                        chart: {
                        title: 'Monthly Chart',
                        },
                        bars: 'verticals', // Required for Material Bar Charts.

                };
                var chart = new google.charts.Bar(document.getElementById('graph_div'));
                chart.draw(data, google.charts.Bar.convertOptions(options));
        }


function drawStuff2() {
var data = new google.visualization.arrayToDataTable([<?php echo $recordRegistredArrayYearly ?>]);
        var options = {
        width: '30%',
                vAxis: {title: 'Pounds(£)'},
                hAxis: {title: 'Months'},
                chart: {
                title: 'Yearly Chart',
                },
                bars: 'verticals', // Required for Material Bar Charts.

        };
        var chart = new google.charts.Bar(document.getElementById('graph_div2'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
}


</script>
<script>
    var vars = {
    dataTable: "#users-table",
            listUrl: "{{ route('report.listrevenue') }}",
    };
            $("input:radio[name=user_type]").click(function () {
    $("#search-form").submit();
    });
</script>
{!! HTML::script('js/dataTables.tableTools.js') !!}
{!! HTML::script('js/revenuereport.js') !!}
@stop