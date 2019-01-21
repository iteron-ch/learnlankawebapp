@extends('admin.cmspage.cmspageform')

@section('form')
    {!! Form::open(['route' => ['cmspage.store'], 'method' => 'post', 'class' => 'form-horizontal','id' =>'cmspagefrm']) !!}
@stop
