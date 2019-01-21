@extends('admin.questionvalidator.questionvalidatorform')

@section('form')
    {!! Form::model($QuestionAdminRepo, ['route' => ['questionvalidator.update', encryptParam($QuestionAdminRepo['id'])], 'method' => 'put', 'files' => true, 'class' => 'form-horizontal','id' =>'questionvalidatorfrm']) !!}
@stop