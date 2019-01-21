@extends('admin.questionvalidator.questionvalidatorform')

@section('form')
    {!! Form::open(['route' => ['questionvalidator.store'],'files' => true, 'method' => 'post', 'class' => 'form-horizontal','id' =>'questionvalidatorfrm']) !!}
@stop