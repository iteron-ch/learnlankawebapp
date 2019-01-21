@extends('admin.questionadmin.questionadminform')

@section('form')
    {!! Form::model($QuestionAdminRepo, ['route' => ['questionadmin.update', encryptParam($QuestionAdminRepo['id'])], 'method' => 'put', 'files' => true, 'class' => 'form-horizontal','id' =>'questionadminfrm']) !!}
@stop