@extends('admin.layout._master')

@section('head')
@stop

@section('body')
<!-- BEGIN BODY -->
<body class="page-404-full-page">
    <div class="row">
        <div class="col-md-12 page-404">
            <div class="details">
                <h3>Coming Soon!</h3>
                <p>
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

    
