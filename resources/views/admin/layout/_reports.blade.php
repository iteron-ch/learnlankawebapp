@extends('admin.layout._master')

@section('head')
<!-- BEGIN PAGE LEVEL PLUGIN STYLES -->

<!-- END PAGE LEVEL PLUGIN STYLES -->
@stop
@section('body')
<style>
.page-container .page-content > .row{width:100%; margin:0px !important}
</style>
<body>
    <!-- BEGIN HEADER -->
    
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        
        <!-- BEGIN CONTENT -->
        
            <div class="page-content" style="min-height:600px;">
                @yield('content')
            </div>
        
        <!-- END CONTENT -->
    </div>
    <!-- END CONTAINER -->
    
    @stop

    @section('styles')
    <style>
    .bar,
    .progress_bar{height: 15px; font-size: 11px; text-align: center; border-radius:10px 0  0 10px !important ;}
    .progress_bar{ width: 100%; background: #ccc; border-radius: 10px !important; }
    .bar.zero{ width:100% !important}
    .bar.red{ background: #fe0000;}
    .bar.yellow{background: #ffcc00;}
    .bar.orange{background: #fb901d;}
    .bar.green{background: #2a9c45;}
    .bar.blue{background: #5aacd7;}
    .progress_bar span{width: 100%; float:left; margin-top:-15px;}
    .bar.full{border-radius: 10px !important; }
    .heder_filer{border: 1px solid #ccc; margin-bottom: 10px; padding: 10px; width: 100%;}
    .usabilla_live_button_container{
        bottom: 20px !important;
        top: auto !important;
    }
    </style>
    <!-- BEGIN PAGE STYLES -->
    @yield('pagecss')
    <!-- END PAGE STYLES -->
    @stop
    @section('scripts')
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    {!! HTML::script('assets/global/scripts/metronic.js') !!}
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    
    @yield('pagescripts')

    <!-- END PAGE LEVEL SCRIPTS -->
    <script>
        jQuery(document).ready(function() {
            Metronic.init();
        });
    </script>
    @stop