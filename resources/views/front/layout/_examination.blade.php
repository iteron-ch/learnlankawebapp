@extends('front.layout._master')

@section('head')
<!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
{!! HTML::style('assets/global/plugins/fullcalendar/fullcalendar.min.css') !!}

<!-- END PAGE LEVEL PLUGIN STYLES -->
@stop
@section('body')
<body class="">
    <!-- BEGIN HEADER -->
    <!-- BEGIN HEADER INNER -->
    <!-- BEGIN TOP NAVIGATION MENU -->
        @if(session()->has('ok'))
        @include('front.partials.error', ['type' => 'success', 'message' => session('ok')])
        @endif
        @if(session()->has('error'))
        @include('front.partials.error', ['type' => 'danger', 'message' => session('error')])
        @endif
    <!-- END TOP NAVIGATION MENU -->
    <!-- END HEADER INNER -->
    <!-- END HEADER -->
    <!-- BEGIN CONTAINER -->
    <div class="wrapper body_container">

        <!-- BEGIN CONTENT -->
        @yield('content')
        <!-- END CONTENT -->
        <!-- BEGIN QUICK SIDEBAR -->
        {{-- @include('front.layout._rightbar') --}}
        <!-- END QUICK SIDEBAR -->
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        @include('front.layout._footer')
    </div>
    <!-- END FOOTER -->
    @stop

    @section('styles')

    <!-- BEGIN PAGE STYLES -->
    @yield('pagecss')
    <!-- END PAGE STYLES -->
    @stop
    @section('scripts')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    @yield('pagescripts')

    <!-- END PAGE LEVEL SCRIPTS -->
    <script>
        jQuery(document).ready(function() {
            //Metronic.init(); // init metronic core componets
            //Layout.init(); // init layout
            // QuickSidebar.init(); // init quick sidebar
            // Demo.init(); // init demo features
        });
    </script>
    @stop