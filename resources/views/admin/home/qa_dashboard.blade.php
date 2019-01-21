@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/admin.welcome') ])
<!-- END PAGE HEADER-->
{{ trim(session()->get('user')['first_name'].' '.session()->get('user')['last_name']) }}
<!-- END DASHBOARD STATS -->

<div class="clearfix"></div>


</div>
</div>
<!-- END CONTENT -->
@stop

@section('pagecss')
<link href="../../assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
{!! HTML::style('assets/global/plugins/jqvmap/jqvmap/jqvmap.css') !!}
@stop

@section('pagescripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->
{!! HTML::script('assets/global/plugins/flot/jquery.flot.min.js') !!}
{!! HTML::script('assets/global/plugins/flot/jquery.flot.resize.min.js') !!}
{!! HTML::script('assets/global/plugins/flot/jquery.flot.categories.min.js') !!}
{!! HTML::script('assets/global/plugins/jquery.pulsate.min.js') !!}
{!! HTML::script('assets/global/plugins/flot//jquery.flot.pie.js') !!}

<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support -->
{!! HTML::script('assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js') !!}
{!! HTML::script('assets/global/plugins/jquery.sparkline.min.js') !!}
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
@stop

