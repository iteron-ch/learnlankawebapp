@extends('admin.tutor.tutorform')

@section('form')
    {!! Form::model($user, ['route' => ['tutor.update', encryptParam($user['id'])], 'method' => 'put', 'files' => true, 'class' => 'form-horizontal','id' =>'tutorfrm']) !!}
@stop