@extends('admin.layout._master')

@section('head')
@stop
@section('body')
<body>
    <!-- BEGIN HEADER -->
    
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <div class="page-content" style="min-height:600px;">
                @yield('content')
            </div>
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
    
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    {!! HTML::script('assets/global/scripts/metronic.js') !!}
    @yield('pagescripts')

    <!-- END PAGE LEVEL SCRIPTS -->
    <script>
        jQuery(document).ready(function() {
            Metronic.init();
        });
    </script>
    @stop