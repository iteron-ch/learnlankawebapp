@extends('admin.layout._master')

@section('head')
@stop

@section('body')
<!-- BEGIN BODY -->
<body class="page-404-full-page">
    <div class="row">
        <div class="col-md-12 page-404">
            <div class="number">
                404
            </div>
            <div class="details">
                <h3>Oops! You're lost.</h3>
                <p>
                    We can not find the page you're looking for.<br/>
                    <a href="{{ route('index')}}">
                        Return home </a>
                </p>
            </div>
        </div>
    </div>

    @stop

    @section('styles')
    <!-- BEGIN PAGE LEVEL STYLES -->
    {!! HTML::style('assets/admin/pages/css/error.css') !!}
    <!-- END PAGE LEVEL SCRIPTS -->
    @stop

    
