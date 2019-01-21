@extends('admin.student.studentform')

@section('form')
    {!! Form::open(array('route' => 'student.store','files' => true,'class'=>'form-horizontal','id' =>'studentfrm' )) !!}
@stop