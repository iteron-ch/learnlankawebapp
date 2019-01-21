@extends('auth._master')

@section('head')
@stop
@section('body')
<body class="login_bg">
    @if($errors->any())
    <div class="wrapper error_top">
        @foreach($errors->all() as $error)
        <div class="main_container">{{ $error }}</div>
        @endforeach
    </div>
    @endif      
    @if(session()->has('error'))
    <div class="wrapper error_top" id="js_flash_message">
        <div class="main_container">{{ session('error') }}</div>
    </div>
    @endif
    @if(session()->has('ok'))
    <div class="wrapper success space_for_message" >
        <div class="main_container">{{ session('ok') }}</div>
    </div>
    @endif
    <div class="wrapper body_container">

        <!-- BEGIN CONTENT -->
        @yield('content')
        <!-- END CONTENT -->

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
    @stop