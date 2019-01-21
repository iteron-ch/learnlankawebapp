@extends('admin.group.groupform')

@section('form')
    {!! Form::open(['route' => ['managegroup.store'], 'method' => 'post', 'files' => true, 'class' => 'form-horizontal','id' =>'groupfrm']) !!}
@stop