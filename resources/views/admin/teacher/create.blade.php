@extends('admin.teacher.teacherform')

@section('form')
    {!! Form::open(array('url' => 'teacher','files' => true, 'class'=>'form-horizontal','id' =>'teacherfrm' )) !!}
@stop