@extends('admin.notification.notificationform')

@section('form')
    {!! Form::open(['route' => ['notification.store'], 'method' => 'post', 'files' => true, 'class' => 'form-horizontal','id' =>'notificationfrm']) !!}
@stop