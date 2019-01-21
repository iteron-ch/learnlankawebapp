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