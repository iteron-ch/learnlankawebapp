@extends('admin.tutor.tutorform')

@section('form')
    {!! Form::open(['route' => ['tutor.store'],'files' => true, 'method' => 'post', 'class' => 'form-horizontal','id' =>'tutorfrm']) !!}
@stop