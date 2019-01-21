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
                <h3>Unauthorized action.</h3>
                <p>
                    You are not authorized to view this page.<br/>
                    <a href="{{ route('home')}}">
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

    
