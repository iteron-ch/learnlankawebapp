@extends('admin.messages.messageform')

@section('form')
    {!! Form::open(['route' => ['messages.store'], 'method' => 'post', 'files' => true, 'class' => 'form-horizontal','id' =>'messagefrm']) !!}
@stop