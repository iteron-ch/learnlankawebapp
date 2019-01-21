@extends('admin.teacher.teacherform')

@section('form')
    {!! Form::model($user, ['route' => ['teacher.update', encryptParam($user['id'])], 'files' => true,'method' => 'put', 'class' => 'form-horizontal panel','id' =>'teacherfrm']) !!}
@stop