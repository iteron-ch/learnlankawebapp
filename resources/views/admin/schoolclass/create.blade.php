@extends('admin.schoolclass.schoolclassform')

@section('form')
    {!! Form::open(['route' => ['manageclass.store'], 'method' => 'post', 'class' => 'form-horizontal','id' =>'schoolclassfrm']) !!}
@stop
