@extends('admin.layout._master')

@section('head')
<!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
{!! HTML::style('assets/global/plugins/fullcalendar/fullcalendar.min.css') !!}
{!! HTML::style('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') !!}
<!-- END PAGE LEVEL PLUGIN STYLES -->
@stop
@section('body')
<body class="page-header-fixed page-quick-sidebar-over-content">
    <!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="{{ route('dashboard') }}">
                    {!! HTML::image(route('image','logo.png'),'logo',['class' => 'logo-default','height'=>'25']) !!}
                </a>
                <div class="menu-toggler sidebar-toggler hide">
                </div>
            </div>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            </a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            @include('admin.layout._navtop')
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END HEADER INNER -->
    </div>
    <!-- END HEADER -->
    <div class="clearfix">
    </div>
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN SIDEBAR -->
        @include('admin.layout._leftbar')
        <!-- END SIDEBAR -->
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <div class="page-content">
                <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
                <!-- Modal iframe -->
                <div id="modal-iframe" class="modal fade bs-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <iframe src="" width="100%" height="600" frameborder="0" allowtransparency="true"></iframe>  
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
                <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
                {{-- @include('admin.layout._theme_setting') --}}
                @yield('content')
            </div>
        </div>
        <!-- END CONTENT -->
        <!-- BEGIN QUICK SIDEBAR -->
        {{-- @include('admin.layout._rightquickbar')--}}
        <!-- END QUICK SIDEBAR -->
    </div>
    <!-- END CONTAINER -->
    <!-- BEGIN FOOTER -->
    @include('admin.layout._footer')
    <!-- END FOOTER -->
    @stop

    @section('styles')

    <!-- BEGIN PAGE STYLES -->
    @yield('pagecss')
    <!-- END PAGE STYLES -->
    @stop
    @section('scripts')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    {!! HTML::script('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') !!}
    {!! HTML::script('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') !!}
    {!! HTML::script('assets/global/plugins/fullcalendar/fullcalendar.min.js') !!}
    {!! HTML::script('assets/global/plugins/bootbox/bootbox.min.js') !!}
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    {!! HTML::script('assets/global/scripts/metronic.js') !!}
    {!! HTML::script('assets/admin/layout/scripts/layout.js') !!}
    {{--- HTML::script('assets/admin/layout/scripts/quick-sidebar.js') ---}}
    {{--- HTML::script('assets/admin/layout/scripts/demo.js') ---}}
    @yield('pagescripts')

    <!-- END PAGE LEVEL SCRIPTS -->
    <script>
        jQuery(document).ready(function() {
            Metronic.init(); // init metronic core componets
            Layout.init(); // init layout
            //QuickSidebar.init(); // init quick sidebar
            //Demo.init(); // init demo features
        });
    </script>
    @stop