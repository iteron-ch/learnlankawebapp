@extends('admin.helpcentre.helpcentreform')

@section('form')
    {!! Form::open(['route' => ['helpcentre.store'], 'method' => 'post', 'files' => true, 'class' => 'form-horizontal','id' =>'helpcentrefrm']) !!}
@stop