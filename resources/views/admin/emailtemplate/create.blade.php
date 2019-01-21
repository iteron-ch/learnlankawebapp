@extends('admin.emailtemplate.emailtemplateform')

@section('form')
    {!! Form::open(['route' => ['emailtemplate.store'], 'method' => 'post', 'class' => 'form-horizontal','id' =>'emailtemplatefrm']) !!}
@stop
