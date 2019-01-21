@extends('admin.student.studentform')

@section('form')
    {!! Form::model($user, ['route' => ['student.update', encryptParam($user['id'])], 'files' => true, 'method' => 'put', 'class' => 'form-horizontal panel','id' =>'studentfrm']) !!}
@stop