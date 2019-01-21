@extends('admin.questionadmin.questionadminform')

@section('form')
    {!! Form::open(['route' => ['questionadmin.store'],'files' => true, 'method' => 'post', 'class' => 'form-horizontal','id' =>'questionadminfrm']) !!}
@stop