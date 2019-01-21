@extends('admin.school.schoolform')

@section('form')
    {!! Form::open(['route' => ['school.store'], 'method' => 'post', 'files' => true, 'class' => 'form-horizontal','id' =>'schoolfrm']) !!}
@stop