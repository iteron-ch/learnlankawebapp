@extends('admin.schoolclass.schoolclassform')

@section('form')
    {!! Form::model($schoolClass, ['route' => ['manageclass.update', encryptParam($schoolClass['id'])], 'method' => 'put', 'class' => 'form-horizontal','id' =>'schoolclassfrm']) !!}
@stop