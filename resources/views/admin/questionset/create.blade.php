@extends('admin.questionset.questionsetform')

@section('form')
    {!! Form::open(['route' => ['questionset.store'], 'method' => 'post', 'class' => 'form-horizontal','id' =>'questionsetfrm']) !!}
@stop
