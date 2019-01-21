@extends('admin.questionset.questionsetform')

@section('form')
{!! Form::model($questionset, ['route' => ['questionset.update', encryptParam($questionset['id'])], 'files' => true, 'method' => 'put', 'class' => 'form-horizontal panel','id' =>'questionsetfrm']) !!}

@stop