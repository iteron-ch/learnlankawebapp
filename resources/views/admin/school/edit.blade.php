@extends('admin.school.schoolform')

@section('form')
    {!! Form::model($user, ['route' => ['school.update', encryptParam($user['id'])], 'method' => 'put', 'files' => true, 'class' => 'form-horizontal','id' =>'schoolfrm']) !!}
@stop